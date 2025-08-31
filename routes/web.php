<?php

use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/start', [QuizController::class, 'start']);
Route::get('/quiz', [QuizController::class, 'quiz']);
Route::post('/answer', [QuizController::class, 'answer']);
Route::get('/result', [QuizController::class, 'result']);