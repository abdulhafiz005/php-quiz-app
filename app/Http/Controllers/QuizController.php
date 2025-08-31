<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Option;
use App\Models\User;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    public function start(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $user = User::firstOrCreate(['name' => $request->name]);
        session(['user_id' => $user->id]);

        return response()->json(['success' => true]);
    }

    public function quiz()
    {
        $userId = session('user_id') ?? null;
        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $nextQuestion = Question::whereNotExists(fn ($query) => 
            $query->select(DB::raw(1))
                ->from('answers')
                ->whereColumn('answers.question_id', 'questions.id')
                ->where('answers.user_id', $userId)
        )->inRandomOrder()->first();

        if (!$nextQuestion) {
            return response()->json(['finished' => true]);
        }

        $options = $nextQuestion->options->mapWithKeys(fn ($opt) => [$opt->id => $opt->text]);

        return response()->json([
            'question' => $nextQuestion->text,
            'options' => $options,
            'question_id' => $nextQuestion->id,
        ]);
    }

    public function answer(Request $request)
    {
        $userId = session('user_id') ?? null;
        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'option_id' => 'nullable|exists:options,id',
        ]);

        $questionId = $request->input('question_id');
        $optionId = $request->input('option_id');

        if (Answer::where('user_id', $userId)->where('question_id', $questionId)->exists()) {
            return response()->json(['error' => 'Already answered'], 400);
        }

        if ($optionId) {
            $option = Option::find($optionId);
            if ($option?->question_id !== (int) $questionId) {
                return response()->json(['error' => 'Invalid option'], 400);
            }
        }

        Answer::create([
            'user_id' => $userId,
            'question_id' => $questionId,
            'option_id' => $optionId,
        ]);

        $nextQuestion = Question::whereNotExists(fn ($query) => 
            $query->select(DB::raw(1))
                ->from('answers')
                ->whereColumn('answers.question_id', 'questions.id')
                ->where('answers.user_id', $userId)
        )->inRandomOrder()->first();

        if (!$nextQuestion) {
            return response()->json(['finished' => true]);
        }

        $options = $nextQuestion->options->mapWithKeys(fn ($opt) => [$opt->id => $opt->text]);

        return response()->json([
            'question' => $nextQuestion->text,
            'options' => $options,
            'question_id' => $nextQuestion->id,
        ]);
    }

    public function result()
    {
        $userId = session('user_id') ?? null;
        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $total = Question::count();
        $answered = Answer::where('user_id', $userId)->count();
        $correct = Answer::where('user_id', $userId)
            ->join('options', 'answers.option_id', '=', 'options.id')
            ->where('options.is_correct', 1)
            ->count();
        $skipped = Answer::where('user_id', $userId)->whereNull('option_id')->count();
        $wrong = $answered - $correct - $skipped;

        return response()->json([
            'correct' => $correct,
            'wrong' => $wrong,
            'skipped' => $skipped,
        ]);
    }
}