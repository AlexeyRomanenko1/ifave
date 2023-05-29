<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\indexController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('index');
});

// Route::post('indexonloadRequest', [indexController::class, 'indexonloadRequest'])->name('indexonload.post');
Route::get('/indexonloadRequest',[indexController::class, 'indexonloadRequest']);
Route::get('/getquestionanswers',[indexController::class, 'getquestionanswers']);
Route::get('/searchAnswers',[indexController::class, 'searchAnswers']);
Route::post('/entervote',[indexController::class, 'entervote']);
Route::get('/searchQuestionsTopics',[indexController::class, 'searchQuestionsTopics']);


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
