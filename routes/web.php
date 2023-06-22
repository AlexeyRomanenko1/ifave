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
Route::get('/indexonloadRequest', [indexController::class, 'indexonloadRequest']);
Route::get('/getquestionanswers', [indexController::class, 'getquestionanswers']);
Route::get('/searchAnswers', [indexController::class, 'searchAnswers']);
Route::post('/entervote', [indexController::class, 'entervote']);
Route::get('/searchQuestionsTopics', [indexController::class, 'searchQuestionsTopics']);
Route::get('/searchQuestions', [indexController::class, 'searchQuestions']);
Route::get('questions_details/{question}', [indexController::class, 'questions_details'])->name('questions_details');
Route::post('/delete_vote', [indexController::class, 'delete_vote']);
Route::post('/add_user_answer', [App\Http\Controllers\indexController::class, 'add_user_answer'])->name('add_user_answer');
Route::post('/add_user_comments', [App\Http\Controllers\indexController::class, 'add_user_comments'])->name('add_user_comments');
Route::post('/upvote_comment', [indexController::class, 'upvote_comment']);
Route::post('/downvote_comment', [indexController::class, 'downvote_comment']);
Route::get('/uncover_answers', [indexController::class, 'uncover_answers']);
Route::get('/get_topics', [indexController::class, 'get_topics']);
Route::get('/search_topics', [indexController::class, 'search_topics']);
Route::get('topics/{topic_name}', [indexController::class, 'topic_name'])->name('topic_name');
// Route::get('redirect/{id}', 'YourController@redirectToUrlWithId')->name('url.redirect');
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/import_questions', [App\Http\Controllers\HomeController::class, 'import_questions'])->name('import_questions');
Route::post('/import_answer', [App\Http\Controllers\HomeController::class, 'import_answer'])->name('import_answer');
Route::get('/dashboard_questions', [App\Http\Controllers\HomeController::class, 'dashboard_questions'])->name('dashboard_questions');
Route::get('/dashboard_question_details', [App\Http\Controllers\HomeController::class, 'dashboard_question_details'])->name('dashboard_question_details');
Route::post('/update_dashboard_question', [App\Http\Controllers\HomeController::class, 'update_dashboard_question'])->name('update_dashboard_question');
Route::post('/delete_dashboard_question', [App\Http\Controllers\HomeController::class, 'delete_dashboard_question'])->name('delete_dashboard_question');
Route::get('answers/{category}', [App\Http\Controllers\HomeController::class, 'question_answers'])->name('question_answers');
Route::get('/dashboard_answer_details', [App\Http\Controllers\HomeController::class, 'dashboard_answer_details'])->name('dashboard_answer_details');
Route::post('/update_dashboard_answer', [App\Http\Controllers\HomeController::class, 'update_dashboard_answer'])->name('update_dashboard_answer');
Route::post('/delete_dashboard_answer', [App\Http\Controllers\HomeController::class, 'delete_dashboard_answer'])->name('delete_dashboard_answer');
Route::post('/export_users', [App\Http\Controllers\HomeController::class, 'export_users'])->name('export_users');

// Route::get('/login', function () {
//     return view('auth.login');
// });
// Route::post('/login', function () {
//     return view('auth.login');
// });