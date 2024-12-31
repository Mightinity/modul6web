<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\QuizController;
use App\Http\Controllers\API\QuestionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Route untuk Users
Route::apiResource('users', UserController::class);

// Route untuk Quizzes
Route::apiResource('quizzes', QuizController::class);

// Route tambahan untuk menambahkan pertanyaan ke quiz
Route::post('quizzes/{quiz}/questions', [QuizController::class, 'addQuestion']);
Route::get('quizzes/code/{code}', [QuizController::class, 'getQuizByCode']);
Route::delete('questions/{id}', [QuestionController::class, 'destroy']);

