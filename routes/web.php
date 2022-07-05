<?php

use App\Http\Controllers\DashboarController;
use App\Http\Controllers\QuestionsController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    ->except('show')
    ->names([
        'index' => 'questions'
    ]);
