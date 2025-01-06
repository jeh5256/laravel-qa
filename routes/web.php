<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\AnswersController;
use App\Http\Controllers\DashboarController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\QuestionsController;
use App\Http\Controllers\VoteAnswerController;
use App\Http\Controllers\AcceptAnswerController;
use App\Http\Controllers\VoteQuestionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
require __DIR__.'/auth.php';

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth', 'verified'])->group(function() {
    Route::get('/dashboard', DashboarController::class)
        ->name('dashboard');

    Route::post('/answers/{answer}/accept', AcceptAnswerController::class)
        ->name('answers.accept');

    Route::post('/questions/{question}/favorites', FavoritesController::class)
        ->name('questions.favorite');

    Route::get('/questions/create', [QuestionsController::class, 'create'])
        ->name('questions.create');

    Route::post('/questions', [QuestionsController::class, 'store'])
        ->name('questions.store');

    Route::get('/questions/{question:slug}/edit', [QuestionsController::class, 'edit'])
        ->name('questions.edit');

    Route::patch('/questions/{question:slug}', [QuestionsController::class, 'update'])
        ->name('questions.update');

    Route::delete('/questions/{question:slug}', [QuestionsController::class, 'delete'])
        ->name('questions.delete');

    Route::post('/answers/{answer}/vote', VoteAnswerController::class)
        ->name('answers.vote');

    Route::post('/questions/{question}/vote', VoteQuestionController::class)
        ->name('questions.upvote');

    Route::post('/questions/{question}/answers', [AnswersController::class, 'store'])
        ->name('questions.answers.store');

    Route::patch('/questions/{question}/answers/{answer}', [AnswersController::class, 'update'])
        ->name('questions.answers.update');

    Route::delete('/questions/{question}/answers/{answer', [AnswersController::class, 'delete'])
        ->name('questions.answers.delete');
});

Route::get('/questions', [QuestionsController::class, 'index'])
    ->name('questions.index');

Route::get('/questions/{question:slug}', [QuestionsController::class, 'show'])
    ->name('questions.show');