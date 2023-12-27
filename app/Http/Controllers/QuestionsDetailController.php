<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Questions;
use App\Models\Topics;
use App\Models\Questionsanswers;
use App\Models\UsersAnswer;
use App\Charts\InfographicsChart as charts;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\indexController;
use Carbon\Carbon;

class QuestionsDetailController extends Controller
{
    //
    public function questions_details(Request $request, $location, $category)
    {
        $cononical_location = $location;
        $cononical_category = $category;

        if (Auth::check()) {
            // User is logged in
            $clientIP = Auth::id();
            if (!Auth::user()->hasVerifiedEmail()) {
                // User is not verified, redirect to a new route
                return redirect()->route('verification.notice');
            }
            $user_details = DB::table('users')->select('*')->where('id', $clientIP)->get();
            foreach ($user_details as $user_detail) {
                $user_status = $user_detail->user_type;
            }
        } else {
            // User is not logged in
            $clientIP = $this->getClientIP($request);
            $user_status = 0;
        }
        // return $id;
        $category = str_replace('-', ' ', $category);
        $location = str_replace('-', ' ', $location);
        $category = str_replace('&#039;', "'", $category);
        // check if information is correct 
        // $CountLocation = DB::table('topics')->select('*')->where('topic_name', $location)->count();
        // $CountCategory = DB::table('questions')->select('*')->where('question', $category)->count();
        // if($CountLocation == 0 || $CountCategory == 0){
        //     $indexController = new indexController(); // Instantiate the IndexController
        //     return $indexController->not_found($request); // Call the notFound method
        // }
        $topic_id = DB::table('topics')->where('topic_name', $location)->pluck('id');
        $topic_id = $topic_id[0];
        $question_id = DB::table('questions')->where('question', $category)->where('topic_id', $topic_id)->pluck('id');
        $id = $question_id[0];
        $question_id = $id;
        $get_user_answers = UsersAnswer::select('user_answers.id', 'questions_answer.answers', 'user_answers.answer_id')->join('questions_answer', 'user_answers.answer_id', 'questions_answer.id')->where('user_answers.user_ip_address', '=', $clientIP)->where('user_answers.question_id', '=', $id)->get();
        $question_answers = Questions::select('questions.id', 'questions.question')
            ->join('questions_answer', 'questions.question_category', '=', 'questions_answer.questions_category')
            ->where('questions.id', '=', $question_id)
            ->select('questions_answer.id as answer_id', 'questions_answer.answers', 'questions_answer.vote_count')
            ->orderBy('questions_answer.vote_count', 'desc')
            ->limit(300)
            ->get();

        $question_details = Questions::select('topic_id', 'id')
            ->where('questions.id', '=', $question_id)
            ->get();
        foreach ($question_details as $details) {
            $header_info = Questions::select('questions.question', 'topics.topic_name', 'questions.id', 'questions.question_category')
                ->join('topics', 'questions.topic_id', 'topics.id')
                // ->where('questions.topic_id', '=', $details['topic_id'])
                ->where('questions.id', '=', $details['id'])
                ->get();
        }
        //$get_comments = DB::table('comments')->select('*')->where('question_id', $question_id)->get();
        $keywords = '';
        foreach ($header_info as $keys) {
            $keywords .= $keys->question . ',' . $keys->topic_name;
        }
        $question_answers = $question_answers->sortByDesc('vote_count');
        $meta_description = 'Top ' . $category . ' in ' . $location . ': ';
        foreach ($question_answers as $index => $description) {
            if ($index <= 4) {
                $meta_description .= $index + 1 . '. ' . $description->answers . ' ';
            }
        }
        // $page_title = 'iFave - ' . $category;
        $page_title = $category . ' in ' . $location . ': Explore Literary Excellence at ifave.com';
        $meta_description = substr($meta_description, 0, -1);

        $get_comments = DB::table('comments')
            ->select('comments.*', 'users.id as user_id', 'users.name')
            ->selectRaw('(upvotes - downvotes) as difference')
            ->leftJoin('users', 'comments.comment_by', '=', 'users.id')
            ->where('comments.question_id', $question_id)
            ->orderBy('difference', 'DESC')
            // ->limit(5)
            ->get();
        $replies = DB::table('comments')->select('comments.*', 'users.id as user_id', 'users.name')->leftJoin('users', 'comments.comment_by', '=', 'users.id')->where('question_id', $question_id)->where('parent_comment_id', '<>', 0)->get();

        $currentDate = date('Y-m-d');
        $posts = DB::table('posts')
            ->select('*')
            ->where('question_id', $question_id)
            ->where('status', 1)
            ->whereDate('last_displayed_at', '<', $currentDate)
            ->orderBy('last_displayed_at', 'ASC')
            ->limit(2)
            ->get();
        if (count($posts) == 0) {
            $posts = DB::table('posts')->select('*')->where('question_id', $question_id)->where('status', 1)->orderBy('created_at', 'DESC')->limit(2)->get();
        }
        // Update the last_displayed_at column for the selected posts
        // $excludedPostIds = [];
        // foreach ($posts as $post) {
        //     DB::table('posts')
        //         ->where('id', $post->id)
        //         ->update(['last_displayed_at' => now()]);
        //     $excludedPostIds[] = $post->id;
        // }
        // //  return $excludedPostIds;
        // $perPage = 12; // Number of items per page
        // $page = request()->get('page', 1); // Get the current page from the request
        // $all_posts = DB::table('posts')->select('*')->where('question_id', $question_id)->where('status', 1)->whereNotIn('id', $excludedPostIds)->orderBy('created_at', 'DESC')->paginate($perPage, ['*'], 'page', $page);


        $thoughts_details = DB::table('question_thoughts')->where('question_id', $question_id)->pluck('thoughts');
        //$thoughts=$thoughts_details[0];
        if (count($thoughts_details) > 0) {
            $thoughts = $thoughts_details[0];
        } else {
            $thoughts = '';
        }

        // top 5 answers 
        $top_answers_query = DB::table('questions_answer')->select('questions_answer.vote_count', 'questions_answer.answers')->join('questions', 'questions.question_category', 'questions_answer.questions_category')->where('questions.id', $question_id)->orderby('questions_answer.vote_count', 'DESC')->limit(10)->get();

        $top_answers = '';
        $top_answers_votes = '';
        foreach ($top_answers_query as $top_answers_fetch) {
            $top_answers .= $top_answers_fetch->answers . 'line_break';
            $top_answers_votes .= $top_answers_fetch->vote_count . ',';
        }
        $top_answers = substr($top_answers, 0, -10);
        $top_answers_votes = substr($top_answers_votes, 0, -1);
        // return 0;
        return view('questions', compact('header_info', 'question_answers', 'thoughts', 'get_user_answers', 'get_comments', 'posts', 'keywords', 'meta_description', 'page_title', 'user_status', 'question_id', 'replies', 'top_answers', 'top_answers_votes', 'location', 'cononical_location', 'cononical_category'));
    }
    public function onLoadPageDetails(Request $request)
    {
        $question_id = $request->question_id;
        $get_comments = DB::table('comments')
            ->select('comments.*', 'users.id as user_id', 'users.name')
            ->selectRaw('(upvotes - downvotes) as difference')
            ->leftJoin('users', 'comments.comment_by', '=', 'users.id')
            ->where('comments.question_id', $question_id)
            ->orderBy('difference', 'DESC')
            // ->limit(5)
            ->get();
        $replies = DB::table('comments')->select('comments.*', 'users.id as user_id', 'users.name')->leftJoin('users', 'comments.comment_by', '=', 'users.id')->where('question_id', $question_id)->where('parent_comment_id', '<>', 0)->get();
        $currentDate = date('Y-m-d');
        $posts = DB::table('posts')
            ->select('*')
            ->where('question_id', $question_id)
            ->where('status', 1)
            ->whereDate('last_displayed_at', '<', $currentDate)
            ->orderBy('last_displayed_at', 'ASC')
            ->limit(2)
            ->get();
        if (count($posts) == 0) {
            $posts = DB::table('posts')->select('*')->where('question_id', $question_id)->where('status', 1)->orderBy('created_at', 'DESC')->limit(2)->get();
        }
        // Update the last_displayed_at column for the selected posts
        $excludedPostIds = [];
        foreach ($posts as $post) {
            DB::table('posts')
                ->where('id', $post->id)
                ->update(['last_displayed_at' => now()]);
            $excludedPostIds[] = $post->id;
        }
        //  return $excludedPostIds;
        // $perPage = 12; // Number of items per page
        // $page = request()->get('page', 1); // Get the current page from the request
        // $all_posts = DB::table('posts')->select('*')->where('question_id', $question_id)->where('status', 1)->whereNotIn('id', $excludedPostIds)->orderBy('created_at', 'DESC')->paginate($perPage, ['*'], 'page', $page);


        $thoughts_details = DB::table('question_thoughts')->where('question_id', $question_id)->pluck('thoughts');
        //$thoughts=$thoughts_details[0];
        if (count($thoughts_details) > 0) {
            $thoughts = $thoughts_details[0];
        } else {
            $thoughts = '';
        }
        $perPage = 9; // Number of items per page
        $page = request()->get('page', 1); // Get the current page from the request
        $all_posts = DB::table('posts')->select('*')->where('question_id', $question_id)->where('status', 1)->whereNotIn('id', $excludedPostIds)->orderBy('created_at', 'DESC')->paginate($perPage, ['*'], 'page', $page);

        return json_encode([
            'success' => 1,
            'thoughts' => $thoughts,
            'searchResults' => view('postspagination', compact('all_posts'))->render(),
            'paginationLinks' => $all_posts->links('pagination::bootstrap-5')->render(),
        ]);
    }
    public function getClientIP(Request $request)
    {
        $ip = $request->getClientIp();
        return $ip;
    }
}
