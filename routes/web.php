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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', DashboarController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__.'/auth.php';

Route::resource('questions', QuestionsController::class)
    ->names([
        'index' => 'questions.index',
        'show' => 'questions.show'
    ])
    ->parameters([
        'questions' => 'question:slug'
    ]);

Route::post('/questions/{question}/vote', VoteQuestionController::class)->name('questions.upvote');
Route::post('/questions/{question}/favorites', FavoritesController::class)->name('questions.favorite');

Route::resource('questions.answers', AnswersController::class)->except(['index', 'create', 'show']);

Route::post('/answers/{answer}/accept', AcceptAnswerController::class)->name('answers.accept');
Route::post('/answers/{answer}/vote', VoteAnswerController::class);