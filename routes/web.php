<?php

// use Spatie\Sitemap\Sitemap;
// use Spatie\Sitemap\Tags\Url;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\indexController;
use App\Http\Controllers\QuestionsDetailController;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\DB;
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

// Route::get('/', function () {
//     return view('index');
// });
Route::get('/', [indexController::class, 'index'])->name('/');

// Route::post('indexonloadRequest', [indexController::class, 'indexonloadRequest'])->name('indexonload.post');
Route::get('/indexonloadRequest', [indexController::class, 'indexonloadRequest']);
Route::get('/comments/{name}', [indexController::class, 'comments_route'])->name('comments_route');
Route::get('/getquestionanswers', [indexController::class, 'getquestionanswers']);
Route::get('/searchAnswers', [indexController::class, 'searchAnswers']);
Route::post('/entervote', [indexController::class, 'entervote']);
Route::get('/searchQuestionsTopics', [indexController::class, 'searchQuestionsTopics']);
Route::get('/searchQuestions', [indexController::class, 'searchQuestions']);
Route::get('/searchcategories', [indexController::class, 'searchcategories']);
//Route::get('questions_details/{question}', [indexController::class, 'questions_details'])->name('questions_details');
Route::post('/delete_vote', [indexController::class, 'delete_vote']);
Route::post('/add_user_answer', [App\Http\Controllers\indexController::class, 'add_user_answer'])->name('add_user_answer');
Route::post('/add_user_comments', [App\Http\Controllers\indexController::class, 'add_user_comments'])->name('add_user_comments');
Route::post('/add_user_comments_ajax', [App\Http\Controllers\indexController::class, 'add_user_comments_ajax'])->name('add_user_comments_ajax');
Route::post('/add_user_comments_posts', [App\Http\Controllers\BlogController::class, 'add_user_comments_posts'])->name('add_user_comments_posts');

// routes/web.php

Route::post('comments/reply', [App\Http\Controllers\indexController::class, 'storeReply'])->name('comments.storeReply');
Route::post('postscomments/reply', [App\Http\Controllers\BlogController::class, 'storeReply'])->name('postscomments.storeReplyPost');
Route::post('/upvote_comment', [indexController::class, 'upvote_comment']);
Route::post('/downvote_comment', [indexController::class, 'downvote_comment']);
Route::get('/uncover_answers', [indexController::class, 'uncover_answers']);
Route::get('/get_topics', [indexController::class, 'get_topics']);
Route::get('/search_topics', [indexController::class, 'search_topics']);
Route::get('/get_blogger', [indexController::class, 'get_blogger']);

Route::get('/search_bloggers', [indexController::class, 'search_bloggers']);
Route::get('/get_comments_list', [indexController::class, 'get_comments_list'])->name('get_comments_list');
Route::get('/get_comments_list_by_name', [indexController::class, 'get_comments_list_by_name'])->name('get_comments_list_by_name');

Route::get('/get_comments_list_all', [indexController::class, 'get_comments_list_all'])->name('get_comments_list_all');
Route::get('/get_comments_list_by_username_all', [indexController::class, 'comments_list_by_username_all'])->name('comments_list_by_username_all');
Route::get('/contact-us', [App\Http\Controllers\ContactController::class, 'index'])->name('contact_us_index');
Route::post('/contact-us', [App\Http\Controllers\ContactController::class, 'contact_us'])->name('contact_us');
Route::get('/about-us', [App\Http\Controllers\AboutController::class, 'about_us'])->name('about_us');
Route::get('/blog', [App\Http\Controllers\BlogController::class, 'show_blogs'])->name('blog');
Route::get('blog/{slug}', [App\Http\Controllers\BlogController::class, 'blog_details'])->name('blog_details');
Route::post('/upvote_post', [App\Http\Controllers\BlogController::class, 'upvote_post']);
Route::post('/downvote_post', [App\Http\Controllers\BlogController::class, 'downvote_post']);
Route::get('blogs/{topic_slug}/{question_slug}', [App\Http\Controllers\BlogController::class, 'filter_blog'])->name('filter_blog');
Route::get('blogger/{user_name}/{topic_slug}/{question_slug}', [App\Http\Controllers\BlogController::class, 'blogger_location_filter'])->name('filter_blog');
Route::get('blogger/{user_name}', [App\Http\Controllers\BlogController::class, 'blogger_filter'])->name('filter_blog');
Route::get('/searchBlogs', [App\Http\Controllers\BlogController::class, 'searchBlogs'])->name('searchBlogs');

Route::get('/search_blog_cat', [App\Http\Controllers\BlogController::class, 'search_blog_cat'])->name('search_blog_cat');
//Route::get('/search_blog_cat/{q}', [App\Http\Controllers\BlogController::class, 'search_blog_cat_q'])->name('search_blog_cat_q');
Route::post('/get_categories_onchange', [App\Http\Controllers\BlogController::class, 'get_categories_onchange'])->name('get_categories_onchange');
Route::get('/location/{topic_name}', [indexController::class, 'topic_name'])->name('topic_name');
// Route::get('category/{location}/{category}', [indexController::class, 'questions_details'])->name('questions_details');
Route::get('category/{location}/{category}', [QuestionsDetailController::class, 'questions_details'])->name('questions_details');
Route::get('onLoadPageDetails', [QuestionsDetailController::class, 'onLoadPageDetails'])->name('onLoadPageDetails');

Route::get('/test', [App\Http\Controllers\BlogController::class, 'test_controller'])->name('test_controller');
Route::get('/generate-infographics',[App\Http\Controllers\BlogController::class, 'generateInfographics'])->name('generate.infographics');
Route::get('/personality-potrait',[App\Http\Controllers\GptController::class, 'create_personality_potrait'])->name('personality-potrait');
//Route::get('/non-existent-page', [indexController::class, 'not_found'])->name('not_found');
Route::get('/generate-info-graphics/{location}/{category}',[App\Http\Controllers\InfographicsController::class, 'generate_image'])->name('generateHistogram');
Route::post('/save-chart-image',[App\Http\Controllers\InfographicsController::class, 'saveChartImage'])->name('save-chart-image');

Auth::routes([
    'verify' => true
]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('verified');
Route::get('/blog-requests', [App\Http\Controllers\HomeController::class, 'blog_requests'])->name('blog_requests')->middleware('verified');
Route::post('/approve-post', [App\Http\Controllers\HomeController::class, 'approve_post'])->name('approve_post');

Route::get('/blog-request/{slug}', [App\Http\Controllers\HomeController::class, 'blog_request_slug'])->name('blog_request_slug')->middleware('verified');
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
Route::post('/import_images', [App\Http\Controllers\HomeController::class, 'import_images'])->name('import_images');
//Blogging routes
Route::get('/create-blog', [App\Http\Controllers\BlogController::class, 'index'])->name('create-blog-index')->middleware('verified');
Route::post('/create_blog', [App\Http\Controllers\BlogController::class, 'create_blog'])->name('create_blog')->middleware('verified');
Route::get('create-blog/{topic}/{question}', [App\Http\Controllers\BlogController::class, 'create_blog_topic_question'])->name('create_blog_topic_question')->middleware('verified');
Route::post('/upload_content_image', [App\Http\Controllers\BlogController::class, 'upload_content_image'])->name('upload_content_image')->middleware('verified');
//User profiles routes
Route::get('/update-profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('update-profile')->middleware('verified');
Route::post('/update-user-profile', [App\Http\Controllers\ProfileController::class, 'user_profile_update'])->name('user_profile_update')->middleware('verified');

Route::get('/edit/blog/{username}/{slug}/{blog_id}', [App\Http\Controllers\BlogController::class, 'editBlog'])->name('editBlog')->middleware('verified');
Route::post('/edit_blog', [App\Http\Controllers\BlogController::class, 'edit_blog'])->name('edit_blog')->middleware('verified');
Route::post('/add_thoughts', [indexController::class, 'add_thoughts']);

// Route::get('/verify_notification', [App\Http\Controllers\HomeController::class, 'verify_notification'])->name('verify_notification')->middleware('verified');

// Route::get('/login', function () {
//     return view('auth.login');
// });
// Route::post('/login', function () {
//     return view('auth.login');
// });
Route::fallback([indexController::class, 'not_found']);