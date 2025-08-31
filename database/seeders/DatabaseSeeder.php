<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Option;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $q = Question::create(['text' => 'What does PHP stand for?']);
        Option::create(['question_id' => $q->id, 'text' => 'Personal Hypertext Processor', 'is_correct' => false]);
        Option::create(['question_id' => $q->id, 'text' => 'Private Home Page', 'is_correct' => false]);
        Option::create(['question_id' => $q->id, 'text' => 'Personal Home Page', 'is_correct' => false]);
        Option::create(['question_id' => $q->id, 'text' => 'PHP: Hypertext Preprocessor', 'is_correct' => true]);

        $q = Question::create(['text' => 'PHP files have a default file extension of_______']);
        Option::create(['question_id' => $q->id, 'text' => '.html', 'is_correct' => false]);
        Option::create(['question_id' => $q->id, 'text' => '.xml', 'is_correct' => false]);
        Option::create(['question_id' => $q->id, 'text' => '.php', 'is_correct' => true]);
        Option::create(['question_id' => $q->id, 'text' => '.ph', 'is_correct' => false]);

        $q = Question::create(['text' => 'What should be the correct syntax to write a PHP code?']);
        Option::create(['question_id' => $q->id, 'text' => '&lt; ? php &gt;', 'is_correct' => false]);
        Option::create(['question_id' => $q->id, 'text' => '&lt; ? ? &gt;', 'is_correct' => false]);
        Option::create(['question_id' => $q->id, 'text' => '&lt; ? ? &gt;', 'is_correct' => false]);
        Option::create(['question_id' => $q->id, 'text' => '&lt; ?php ?&gt;', 'is_correct' => true]);

        $q = Question::create(['text' => 'Which of the following is the correct way to end a PHP statement?']);
        Option::create(['question_id' => $q->id, 'text' => ';', 'is_correct' => true]);
        Option::create(['question_id' => $q->id, 'text' => '.', 'is_correct' => false]);
        Option::create(['question_id' => $q->id, 'text' => '!', 'is_correct' => false]);
        Option::create(['question_id' => $q->id, 'text' => 'New Line', 'is_correct' => false]);
    }
}
