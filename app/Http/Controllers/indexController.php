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

use Carbon\Carbon;

class indexController extends Controller
{
    //
    public function index(Request $request)
    {
        $topicName = "The World";
        $perPage = 20; // Number of items per page
        $page = request()->get('page', 1); // Get the current page from the request
        $userIpAddress = $this->getClientIP($request);
        if (Auth::check()) {
            // User is logged in
            if (!Auth::user()->hasVerifiedEmail()) {
                // User is not verified, redirect to a new route
                return redirect()->route('verification.notice');
            }
            $userId = Auth::id();
            $subQuery = UsersAnswer::select('question_id')
                ->where('user_ip_address', $userId)
                ->get();
          
            $get_this_user_votes = DB::table('user_answers')->select('questions.question', 'questions_answer.answers')
                ->join('questions', 'user_answers.question_id', 'questions.id')
                ->join('questions_answer', 'user_answers.answer_id', 'questions_answer.id')
                ->where('user_answers.user_ip_address', $userId)
                ->get();

            
            $get_last_three_locations = DB::table('recent_locations')
                ->where('user_id', $userId)
                ->orderBy('id', 'desc')
                ->offset(0)
                ->limit(3)
                ->get();
        } else {
            // User is not logged in
            $subQuery = UsersAnswer::select('question_id')
                ->where('user_ip_address', $userIpAddress)
                ->get();
            $get_this_user_votes = '';
            $get_last_three_locations = DB::table('recent_locations')
                ->where('user_id', $userIpAddress)
                ->orderBy('id', 'desc')
                ->offset(0)
                ->limit(3)
                ->get();
        }
        $questions = DB::table('questions')
            ->select(
                'questions.id AS question_id',
                'questions.question',
                'questions.question_category',
                DB::raw("SUBSTRING_INDEX(GROUP_CONCAT(DISTINCT CONCAT(answers, ' (Faves: ', vote_count, ')') ORDER BY vote_count DESC SEPARATOR '}'), '}', 3) AS top_answers"),
                DB::raw("SUM(COALESCE(questions_answer.vote_count, 0)) AS total_votes")
            )
            ->join('topics', 'questions.topic_id', '=', 'topics.id')
            ->leftJoin('questions_answer', function ($join) {
                $join->on('questions.question_category', '=', 'questions_answer.questions_category');
            })
            ->where('topics.topic_name', '=', $topicName)
            ->groupBy('questions.id', 'questions.question', 'questions.question_category')
            ->orderBy('total_votes', 'DESC')
            ->paginate($perPage, ['*'], 'page', $page);


        $get_topic_details = Topics::select('*')->where('topic_name', $topicName)->get();
        foreach ($get_topic_details as $get_topic_detail) {
            $topic_id = $get_topic_detail['id'];
        }

        $comments = DB::table('comments')
            ->select('users.name', DB::raw('SUM(comments.upvotes  - comments.downvotes) as upvotes'))
            ->join('users', 'comments.comment_by', '=', 'users.id')
            ->join('questions', 'comments.question_id', '=', 'questions.id')
            ->where('questions.topic_id', '=', $topic_id)
            ->orderByDesc('upvotes')
            ->limit(5)
            ->groupBy('users.name')
            ->get();

        //query to get posts data 
        $posts = DB::table('posts')
            ->select('posts.title', 'posts.blog_content', 'posts.featured_image', 'users.name', 'posts.created_at', 'posts.slug')
            ->join('users', 'posts.user_id', 'users.id')
            ->where('posts.topic_id', $topic_id)
            ->where('posts.status', 1)
            ->orderByDesc('posts.vote_count')
            ->limit(4)
            ->get();

        $keywords = 'ifave,' . $topicName;
        $meta_description = 'Rank and compare the best of everything in ' . $topicName . '. Save and share your faves among :';
        foreach ($questions as $index => $description) {
            if ($index <= 5) {
                $meta_description .= $index + 1 . '. ' . $description->question . ' ';
            }
        }
        $meta_description = substr($meta_description, 0, -1);
        $page_title = 'iFave - ' . $topicName;

        return view('index', compact('questions', 'subQuery', 'comments', 'topic_id', 'posts', 'keywords', 'topicName', 'meta_description', 'page_title', 'get_last_three_locations'));
    }
    public function indexonloadRequest(Request $request)
    {

        if ($request->task == 'get_questions') {
            $userIpAddress = $this->getClientIP($request);
            if (Auth::check()) {
                // User is logged in
                $userId = Auth::id();
                $get_this_user_votes = DB::table('user_answers')->select('questions.question', 'questions_answer.answers', 'topics.topic_name')
                    ->join('questions', 'user_answers.question_id', 'questions.id')
                    ->join('questions_answer', 'user_answers.answer_id', 'questions_answer.id')
                    ->join('topics', 'questions.topic_id', 'topics.id')
                    ->where('user_answers.user_ip_address', $userId)
                    ->get();
            } else {
                $get_this_user_votes = '';
            }
            if (isset($request->topic_name)) {
                $topicName = $request->topic_name;
            } else {
                $topicName = "The World";
            }

            $get_topic_details = Topics::select('*')->where('topic_name', $topicName)->get();
            foreach ($get_topic_details as $get_topic_detail) {
                $topic_id = $get_topic_detail['id'];
            }
            $questions_slider = Questions::select('*')->where('topic_id', $topic_id)->get();
            $personality_potrait=DB::table('personality_potrait')->select('personality')->where('user_id',$userId)->orderBy('id','desc')->limit(1)->get();
            $personality='';
            foreach($personality_potrait as $user_personality){
                $personality=$user_personality->personality;
            }
            return json_encode(['success' => 1, 'topic_name' => $topicName, 'questions_slider' => $questions_slider, 'myfaves' => $get_this_user_votes, 'topic_id' => $topic_id,'personality'=>$personality]);
        }
        // return response()->json(['sucess' => 'hello']);
    }
    public function getquestionanswers(Request $request)
    {
        if ($request->task == 'get_question_answers') {
            $question_id = $request->question_id;
            $question_answers = Questions::select('questions.id', 'questions.question')
                ->join('questions_answer', 'questions.question_category', '=', 'questions_answer.questions_category')
                ->where('questions.id', '=', $question_id)
                ->orderBy('questions_answer.answers', 'desc')
                ->select('questions_answer.id as answer_id', 'questions_answer.answers', 'questions_answer.vote_count')
                ->get();

            $question_details = Questions::select('question')
                ->where('id', '=', $question_id)
                ->get();
            return json_encode([
                'success' => 1,
                'answers' => $question_answers,
                'question' => $question_details
            ]);
        }
        return json_encode([
            'sucess' => 0,
            'data' => 'Invalid request'
        ]);
    }
    public function searchAnswers(Request $request)
    {
        if ($request->task == 'searchAnswers') {
            $tosearch = $request->search;
            $question_id = $request->question_id;
            // if(strlen($tosearch) >= 3){
            $question_answers = Questions::select('questions.id', 'questions.question')
                ->join('questions_answer', 'questions.question_category', '=', 'questions_answer.questions_category')
                ->where('questions.id', '=', $question_id)
                ->where('questions_answer.answers', 'like', '%' . $tosearch . '%')
                ->orderBy('questions_answer.answers', 'desc')
                ->select('questions_answer.id as answer_id', 'questions_answer.answers', 'questions_answer.vote_count')
                ->get();
            return json_encode([
                'success' => 1,
                'data' => $question_answers
            ]);
        }
        return json_encode([
            'success' => 0,
            'data' => 'Invalid request'
        ]);
    }
    public function entervote(Request $request)
    {
        if (Auth::check()) {
            // User is logged in
            $clientIP = Auth::id();
        } else {
            // User is not logged in
            $clientIP = $this->getClientIP($request);
        }

        // $clientIP = $this->getClientIP($request);
        $answer_id = $request->answer_id;
        $query = Questionsanswers::select('questions_answer.id', 'questions_answer.vote_count')
            ->join('questions', 'questions_answer.questions_category', '=', 'questions.question_category')
            ->where('questions_answer.id', '=', $answer_id)
            ->select('questions.id as question_id', 'questions_answer.id', 'questions_answer.vote_count')
            ->get();
        foreach ($query as $result) {
            $question_id = $result->question_id;
            $vote_count = $result->vote_count;
        }
        $vote_count = $vote_count + 1;
        $count_answer = UsersAnswer::where('user_ip_address', $clientIP)->where('question_id', $question_id)->count();
        if ($count_answer < 3) {
            $if_voted = UsersAnswer::where('user_ip_address', $clientIP)->where('question_id', $question_id)->where('answer_id', $answer_id)->count();
            if ($if_voted == 0) {
                $update_votes = DB::table('questions_answer')
                    ->where('id', $answer_id)
                    ->update([
                        'vote_count' => $vote_count
                    ]);
                if ($update_votes) {
                    $insert_user_vote = DB::table('user_answers')->insert([
                        'question_id' => $question_id,
                        'answer_id' => $answer_id,
                        'user_ip_address' => $clientIP
                    ]);
                    if ($insert_user_vote) {
                        return json_encode([
                            'success' => 1,
                            'data' => 'Vote added successfully!'
                        ]);
                    } else {
                        return json_encode([
                            'success' => 0,
                            'data' => 'Something went wrong!'
                        ]);
                    }
                } else {
                    return json_encode([
                        'success' => 0,
                        'data' => 'Something went wrong!'
                    ]);
                }
            } else {
                return json_encode([
                    'success' => 0,
                    'data' => 'You can not vote on same answer twice!'
                ]);
            }
        } else {
            return json_encode([
                'success' => 0,
                'data' => 'You have already voted for this question'
            ]);
        }
    }
    public function searchQuestionsTopics(Request $request)
    {
        if ($request->task == 'searchQuestionsTopics') {
            $tosearch = $request->search;
            // $question_id = $request->question_id;
            // if(strlen($tosearch) >= 3){
            if (strlen($tosearch) > 0) {
                $questions = Questionsanswers::select('*')
                    ->where('answers', 'like', '%' . $tosearch . '%')
                    ->where('questions_category', '=', $request->id)
                    ->orderBy('answers', 'desc')
                    ->get();
            } else {
                $questions = Questionsanswers::select('*')
                    ->where('questions_category', '=', $request->id)
                    ->orderBy('answers', 'desc')
                    ->get();
            }
            // $topics = DB::table('topics')
            //     ->join('questions', 'topics.id', '=', 'questions.topic_id')
            //     ->where('questions.question', 'like', '%' . $tosearch . '%')
            //     ->select('topics.id', 'topics.topic_name')
            //     ->distinct()
            //     ->get();
            return json_encode([
                'success' => 1,
                'data' => $questions
            ]);
        }
        return json_encode([
            'success' => 0,
            'data' => 'Invalid request'
        ]);
    }

    public function searchQuestions(Request $request)
    {
        // if ($request->task == 'searchQuestions') {
        $tosearch = $request->search;
        $topicId = $request->id;
        $topicName = $request->topicName;
        //    $topicName='The World';
        $perPage = 20; // Number of items per page
        $page = request()->get('page', 1); // Get the current page from the request
        $userIpAddress = $this->getClientIP($request);
        if (Auth::check()) {
            // User is logged in
            $userId = Auth::id();
            $subQuery = UsersAnswer::select('question_id')
                ->where('user_ip_address', $userId)
                ->get();

            $get_this_user_votes = DB::table('user_answers')->select('questions.question', 'questions_answer.answers')
                ->join('questions', 'user_answers.question_id', 'questions.id')
                ->join('questions_answer', 'user_answers.answer_id', 'questions_answer.id')
                ->where('user_answers.user_ip_address', $userId)
                ->get();
        } else {
            // User is not logged in
            $subQuery = UsersAnswer::select('question_id')
                ->where('user_ip_address', $userIpAddress)
                ->get();
            $get_this_user_votes = '';
        }
        if (strlen($tosearch) > 0) {
            // $questions = Questions::select(
            //     'questions.id AS question_id',
            //     'questions.question',
            //     'questions.question_category',
            //     DB::raw('SUBSTRING_INDEX(GROUP_CONCAT(DISTINCT CONCAT(answers, " ( Faves: ", vote_count, ")") ORDER BY vote_count DESC SEPARATOR "}"), "}", 3) AS top_answers'),
            //     DB::raw('SUM(vote_count) AS total_votes')
            // )
            //     ->join('questions_answer', 'questions.question_category', '=', 'questions_answer.questions_category')
            //     ->join('topics', 'questions.topic_id', '=', 'topics.id')
            //     ->where('topics.id', $topicName)
            //     ->where('questions.question', 'like', '%' . $tosearch . '%')
            //     ->groupBy('questions.id', 'questions.question', 'questions.question_category')
            //     ->orderBy('total_votes', 'desc') // Sort by total_votes in descending order
            //     ->paginate($perPage);
            $questions = DB::table('questions')
                ->select(
                    'questions.id AS question_id',
                    'questions.question',
                    'questions.question_category',
                    DB::raw("SUBSTRING_INDEX(GROUP_CONCAT(DISTINCT CONCAT(answers, ' (Faves: ', vote_count, ')') ORDER BY vote_count DESC SEPARATOR '}'), '}', 3) AS top_answers"),
                    DB::raw("SUM(COALESCE(questions_answer.vote_count, 0)) AS total_votes")
                )
                ->join('topics', 'questions.topic_id', '=', 'topics.id')
                ->leftJoin('questions_answer', function ($join) {
                    $join->on('questions.question_category', '=', 'questions_answer.questions_category');
                })
                ->where('topics.id', '=', $topicId)
                ->where('questions.question', 'like', '%' . $tosearch . '%')
                ->groupBy('questions.id', 'questions.question', 'questions.question_category')
                ->orderBy('total_votes', 'DESC')
                ->paginate($perPage);
            $topic_id = $topicId;
            $comments = DB::table('comments')
                ->select('users.name', DB::raw('SUM(comments.upvotes  - comments.downvotes) as upvotes'))
                ->join('users', 'comments.comment_by', '=', 'users.id')
                ->join('questions', 'comments.question_id', '=', 'questions.id')
                ->where('questions.topic_id', '=', $topicId)
                ->orderByDesc('upvotes')
                ->limit(5)
                ->groupBy('users.name')
                ->get();
            //query to get posts data 
            $posts = DB::table('posts')
                ->select('posts.title', 'posts.blog_content', 'posts.featured_image', 'users.name', 'posts.created_at', 'posts.slug')
                ->join('users', 'posts.user_id', 'users.id')
                ->where('posts.topic_id', $topic_id)
                ->where('posts.status', 1)
                ->orderByDesc('posts.vote_count')
                ->limit(4)
                ->get();
            // return view('pagination', compact('questions', 'subQuery', 'comments', 'topic_id', 'posts'));
            return response()->json([
                'searchResults' => view('pagination', compact('questions', 'subQuery', 'comments', 'topic_id', 'posts', 'topicName'))->render(),
                'paginationLinks' => $questions->links('pagination::bootstrap-5')->render(),
            ]);
        } else {
            // $topicName = $request->id;
            // $questions = Questions::select(
            //     'questions.id AS question_id',
            //     'questions.question',
            //     'questions.question_category',
            //     DB::raw('SUBSTRING_INDEX(GROUP_CONCAT(DISTINCT CONCAT(answers, " ( Faves: ", vote_count, ")") ORDER BY vote_count DESC SEPARATOR "}"), "}", 3) AS top_answers'),
            //     DB::raw('SUM(vote_count) AS total_votes')
            // )
            //     ->join('questions_answer', 'questions.question_category', '=', 'questions_answer.questions_category')
            //     ->join('topics', 'questions.topic_id', '=', 'topics.id')
            //     ->where('topics.id', $topicName)
            //     ->groupBy('questions.id', 'questions.question', 'questions.question_category')
            //     ->orderBy('total_votes', 'desc') // Sort by total_votes in descending order
            //     ->paginate($perPage);
            $questions = DB::table('questions')
                ->select(
                    'questions.id AS question_id',
                    'questions.question',
                    'questions.question_category',
                    DB::raw("SUBSTRING_INDEX(GROUP_CONCAT(DISTINCT CONCAT(answers, ' (Faves: ', vote_count, ')') ORDER BY vote_count DESC SEPARATOR '}'), '}', 3) AS top_answers"),
                    DB::raw("SUM(COALESCE(questions_answer.vote_count, 0)) AS total_votes")
                )
                ->join('topics', 'questions.topic_id', '=', 'topics.id')
                ->leftJoin('questions_answer', function ($join) {
                    $join->on('questions.question_category', '=', 'questions_answer.questions_category');
                })
                ->where('topics.id', '=', $topicId)
                ->groupBy('questions.id', 'questions.question', 'questions.question_category')
                ->orderBy('total_votes', 'DESC')
                ->paginate($perPage);
            $topic_id = $topicId;
            $comments = DB::table('comments')
                ->select('users.name', DB::raw('SUM(comments.upvotes  - comments.downvotes) as upvotes'))
                ->join('users', 'comments.comment_by', '=', 'users.id')
                ->join('questions', 'comments.question_id', '=', 'questions.id')
                ->where('questions.topic_id', '=', $topicId)
                ->orderByDesc('upvotes')
                ->limit(5)
                ->groupBy('users.name')
                ->get();
            $posts = DB::table('posts')
                ->select('posts.title', 'posts.blog_content', 'posts.featured_image', 'users.name', 'posts.created_at', 'posts.slug')
                ->join('users', 'posts.user_id', 'users.id')
                ->where('posts.topic_id', $topic_id)
                ->where('posts.status', 1)
                ->orderByDesc('posts.vote_count')
                ->limit(4)
                ->get();
            // return view('pagination', compact('questions', 'subQuery', 'comments', 'topic_id', 'posts'));
            return response()->json([
                'searchResults' => view('pagination', compact('questions', 'subQuery', 'comments', 'topic_id', 'posts', 'topicName'))->render(),
                'paginationLinks' => $questions->links('pagination::bootstrap-5')->render(),
            ]);
        }
        // }
        // return json_encode([
        //     'success' => 0,
        //     'data' => 'Invalid request'
        // ]);
    }
    public function searchcategories(Request $request)
    {
        $tosearch = $request->search;
        $topicName = $request->id;
        if (strlen($tosearch) > 0) {
            //$query = DB::table('questions')->where('topic_id', $topicName)->where('question', 'like', '%' . $tosearch . '%')->get();
            $query = DB::table(function ($query) use ($topicName, $tosearch) {
                $query->select([
                    'cat_group AS question_category',
                    'question',
                    DB::raw('@row_number := IF(@prev_cat_group = cat_group, @row_number + 1, 1) AS question_order'),
                    DB::raw('@prev_cat_group := cat_group'),
                ])
                    ->from('questions')
                    ->where('topic_id', '=', $topicName)
                    ->where('question', 'like', '%' . $tosearch . '%')
                    ->orderBy('cat_group');
            }, 'subquery')
                ->groupBy('question_category')
                ->select([
                    'question_category',
                    DB::raw("GROUP_CONCAT(CONCAT(question) ORDER BY question_order SEPARATOR ' break_statement') AS questions")
                ])
                ->orderByDesc('questions')
                ->get();
        } else {
            // $query = DB::table('questions')->where('topic_id', $topicName)->get();
            $query = DB::table(DB::raw('(SELECT
            cat_group AS question_category,
            question,
            @row_number := IF(@prev_cat_group = cat_group, @row_number + 1, 1) AS question_order,
            @prev_cat_group := cat_group
        FROM
            questions
         WHERE
            topic_id = ' . $topicName . '
        ORDER BY
            cat_group) AS subquery'))
                ->select([
                    'question_category',
                    DB::raw("GROUP_CONCAT(CONCAT(question) ORDER BY question_order SEPARATOR ' break_statement') AS questions")
                ])
                ->groupBy('question_category')
                ->orderBy('questions', 'DESC')
                ->get();
        }
        return json_encode([
            'success' => 1,
            'data' => $query
        ]);
    }
    public function questions_details(Request $request, $location, $category)
    {
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
        $page_title = 'iFave - ' . $category;
        $meta_description = substr($meta_description, 0, -1);
        // $get_comments = DB::table('comments')->select('*')
        //     ->selectRaw('(upvotes - downvotes) as difference')
        //     ->where('question_id', $question_id)
        //     ->orderBy('difference', 'DESC')
        //     ->limit(5)
        //     ->get();
        $get_comments = DB::table('comments')
            ->select('comments.*', 'users.id as user_id', 'users.name')
            ->selectRaw('(upvotes - downvotes) as difference')
            ->leftJoin('users', 'comments.comment_by', '=', 'users.id')
            ->where('comments.question_id', $question_id)
            ->orderBy('difference', 'DESC')
            // ->limit(5)
            ->get();
        $replies = DB::table('comments')->select('comments.*', 'users.id as user_id', 'users.name')->leftJoin('users', 'comments.comment_by', '=', 'users.id')->where('question_id', $question_id)->where('parent_comment_id', '<>', 0)->get();
        // $data=[
        //     'question_details'=>$question_details,
        //     'question_answers'=>$question_answers
        // ];
        $currentDate = date('Y-m-d');
        // $posts = DB::table('posts')->select('*')->where('question_id', $question_id)->where('status', 1)->orderBy('created_at', 'DESC')->limit(2)->get();
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
        $perPage = 12; // Number of items per page
        $page = request()->get('page', 1); // Get the current page from the request
        $all_posts = DB::table('posts')->select('*')->where('question_id', $question_id)->where('status', 1)->whereNotIn('id', $excludedPostIds)->orderBy('created_at', 'DESC')->paginate($perPage, ['*'], 'page', $page);


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
        
        return view('questions', compact('header_info', 'question_answers', 'get_user_answers', 'get_comments', 'posts', 'keywords', 'meta_description', 'page_title', 'user_status', 'question_id', 'thoughts', 'replies', 'all_posts', 'top_answers','top_answers_votes','location'));
    }
    public function delete_vote(Request $request)
    {
        $to_delete = $request->user_answer_id;
        $delete = DB::table('user_answers')->where('id', $to_delete)->delete();
        if ($delete) {
            $deduct_vote_count = Questionsanswers::select('*')->where('id', $request->answer_id)->get();
            foreach ($deduct_vote_count as $result) {
                $vote_count = $result->vote_count;
            }
            $vote_count = $vote_count - 1;
            $update_votes = DB::table('questions_answer')
                ->where('id', $request->answer_id)
                ->update([
                    'vote_count' => $vote_count
                ]);
            return json_encode([
                'success' => 1,
                'data' => 'Vote delete successfully'
            ]);
        }
    }
    public function add_user_answer(Request $request)
    {
        $errors = '';
        $success = 'Your answers are added successfully!';
        $category = $request->category;
        // $add_answer = $request->add_answer;
        if (Auth::check()) {
            // User is logged in
            $clientIP = Auth::id();
        } else {
            // User is not logged in
            $clientIP = $this->getClientIP($request);
        }
        $userAnswers = $request->input('add_answer');
        //return redirect()->back()->with('success', $userAnswers);
        foreach ($userAnswers as $add_user_answer) {
            if ($add_user_answer != '') {
                // return redirect()->back()->with('success', $add_user_answer);
                $check_query = DB::table('questions_answer')->where('added_by', $clientIP)->where('questions_category', $category)->count();
                if ($check_query < 3) {
                    $check_answer = DB::table('questions_answer')->where('answers', $add_user_answer)->where('questions_category', $category)->count();
                    if ($check_answer == 0) {
                        $query = DB::table('questions_answer')->insert([
                            'answers' => $add_user_answer,
                            'vote_count' => 0,
                            'questions_category' => $category,
                            'added_by' => $clientIP,
                            'added_at' => Date('Y-m-d H:i:s')
                        ]);
                        $get_this_query = Questionsanswers::select('*')->where('added_by', $clientIP)->where('questions_category', $category)->where('answers', $add_user_answer)->get();
                        foreach ($get_this_query as $this_answer) {
                            $this_answer_id = $this_answer['id'];
                        }
                        $query = Questionsanswers::select('questions_answer.id', 'questions_answer.vote_count')
                            ->join('questions', 'questions_answer.questions_category', '=', 'questions.question_category')
                            ->where('questions_answer.id', '=', $this_answer_id)
                            ->select('questions.id as question_id', 'questions_answer.id', 'questions_answer.vote_count')
                            ->get();
                        foreach ($query as $result) {
                            $question_id = $result->question_id;
                            // $vote_count = $result->vote_count;
                        }
                        //return redirect()->back()->with('success', $this_answer_id);
                        $count_answer = UsersAnswer::where('user_ip_address', $clientIP)->where('question_id', $question_id)->count();
                        if ($count_answer < 3) {

                            $vote_count = 1;
                            $update_votes = DB::table('questions_answer')
                                ->where('id', $this_answer_id)
                                ->update([
                                    'vote_count' => $vote_count
                                ]);
                            if ($update_votes) {
                                $insert_user_vote = DB::table('user_answers')->insert([
                                    'question_id' => $question_id,
                                    'answer_id' => $this_answer_id,
                                    'user_ip_address' => $clientIP
                                ]);

                                // if ($insert_user_vote) {
                                //     return json_encode([
                                //         'success' => 1,
                                //         'data' => 'Vote added successfully!'
                                //     ]);
                                // } else {
                                //     return json_encode([
                                //         'success' => 0,
                                //         'data' => 'Something went wrong!'
                                //     ]);
                                // }
                            }
                        }
                        // return redirect()->back()->with('success', "Answer added successfully");
                    } else {
                        $errors .= 'This <b> ' . $add_user_answer . ' </b> answer is already listed <br>';
                        //return redirect()->back()->with('error', "This answer is already listed");
                    }
                } else {
                    $get_last_entry = DB::table('questions_answer')->where('questions_category', $category)->where('added_by', $clientIP)->max("added_at");
                    //foreach($get_last_entry as $last_entry){
                    $last_entry = Carbon::parse($get_last_entry);
                    $current_time = Carbon::parse(Date('Y-m-d H:i:s'));
                    $hourDifference = $current_time->diffInHours($last_entry);
                    if ($hourDifference >= 24) {
                        $query = DB::table('questions_answer')->insert([
                            'answers' => $add_user_answer,
                            'vote_count' => 0,
                            'questions_category' => $category,
                            'added_by' => $clientIP,
                            'added_at' => Date('Y-m-d H:i:s')
                        ]);
                    } else {
                        // }
                        $errors .= 'You can not add more than 3 answer for a question with 24 hours<br>';
                    }
                    // return redirect()->back()->with('error', "You have already added one answer for this question");
                }
            }
        }
        if (strlen($errors) > 0) {
            return redirect()->back()->with('warning', $success . "<br>" . $errors);
        } else {
            return redirect()->back()->with('success', $success);
        }
    }

    public function add_user_comments(Request $request)
    {
        if (Auth::check()) {
            // User is logged in
            $clientIP = Auth::id();
        } else {
            // User is not logged in
            $clientIP = $this->getClientIP($request);
        }
        $comments = $request->comments;
        $question_id = $request->question_id;
        if ($this->isURLComment($comments) == true) {
            return redirect()->back()->with('error', 'external website links are not allowed to add!');
        }
        $pattern = '/\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}\b/';

        if (preg_match($pattern, $comments)) {
            // Email address found in textarea, handle the error
            return redirect()->back()->with('error', 'You can not add email addresses in comments.');
        }
        if (strlen($comments) > 2000) {
            return redirect()->back()->with('error', 'You comments length is exceeding the limit of 2000 characters!');
        } else {
            $insert_comments = DB::table('comments')->insert([
                'comments' => $comments,
                'question_id' => $question_id,
                'comment_by' => $clientIP
            ]);

            if ($insert_comments) {
                if (strlen($comments) > 300) {
                    return redirect()->back()->with('success', 'Comment added successfully!');
                } else {
                    return redirect()->back()->with('success', 'Comment added successfully!');
                }
            } else {
                return redirect()->back()->with('error', 'Something went wrong!');
            }
        }
    }
    public function add_user_comments_ajax(Request $request)
    {
        if (Auth::check()) {
            // User is logged in
            $clientIP = Auth::id();
        } else {
            // User is not logged in
            $clientIP = $this->getClientIP($request);
        }
        $comments = $request->comments;
        $question_id = $request->question_id;
        if ($this->isURLComment($comments) == true) {
            return redirect()->back()->with('error', 'external website links are not allowed to add!');
        }
        $pattern = '/\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}\b/';

        if (preg_match($pattern, $comments)) {
            // Email address found in textarea, handle the error
            return redirect()->back()->with('error', 'You can not add email addresses in comments.');
        }
        if (strlen($comments) > 2000) {
            return redirect()->back()->with('error', 'You comments length is exceeding the limit of 2000 characters!');
        } else {
            $insert_comments = DB::table('comments')->insert([
                'comments' => $comments,
                'question_id' => $question_id,
                'comment_by' => $clientIP
            ]);

            if ($insert_comments) {
                if (strlen($comments) > 300) {
                    return json_encode([
                        'success' => 2,
                        'data' => 'Looks like you have left a thorough and detailed comment. Thanks you! Please share your comment across the social media'
                    ]);
                } else {
                    return json_encode([
                        'success' => 1,
                        'data' => 'Comment added successfully!'
                    ]);
                }
            } else {
                return json_encode([
                    'success' => 0,
                    'data' => 'Something went wrong!'
                ]);
            }
        }
    }
    //store replty to comment
    public function storeReply(Request $request)
    {
        // Validation for replies
        $this->validate($request, [
            'parent_comment_id' => 'required|exists:comments,id',
            'reply_text' => 'required|max:2000',
        ]);
        if (Auth::check()) {
            // User is logged in
            $clientIP = Auth::id();
        } else {
            // User is not logged in
            $clientIP = $this->getClientIP($request);
        }
        // $comments = $request->comments;
        // $question_id = $request->question_id;
        if ($this->isURLComment($request->input('reply_text')) == true) {
            return redirect()->back()->with('error', 'external website links are not allowed to add!');
        }
        $pattern = '/\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}\b/';

        if (preg_match($pattern, $request->input('reply_text'))) {
            // Email address found in textarea, handle the error
            return redirect()->back()->with('error', 'You can not add email addresses in comments.');
        }
        if (strlen($request->input('reply_text')) > 2000) {
            return redirect()->back()->with('error', 'You reply length is exceeding the limit of 2000 characters!');
        } else {
            $insert_comments = DB::table('comments')->insert([
                'parent_comment_id' => $request->input('parent_comment_id'),
                'comments' => $request->input('reply_text'),
                'comment_by' => $clientIP,
                'question_id' => $request->question_id
            ]);
        }
        if ($insert_comments) {
            return redirect()->back()->with('success', 'Reply added successfully!');
        } else {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
        // Storing the reply in the database
        // $comment = new Comment();
        // $comment->parent_comment_id = $request->input('parent_comment_id');
        // $comment->comments = $request->input('reply_text');
        // Add other fields like user ID, etc.
        // $comment->save();

        // return redirect()->back()->with('success', 'Reply added successfully!');
    }
    public function upvote_comment(Request $request)
    {
        if (Auth::check()) {
            // User is logged in
            $clientIP = Auth::id();
        } else {
            // User is not logged in
            $clientIP = $this->getClientIP($request);
        }
        $check_if_voted = DB::table('comment_votes_history')->select('*')->where('comment_id', $request->comment_id)->where('vote_by', $clientIP)->count();
        if ($check_if_voted == 0) {
            $insert_upvote = DB::table('comment_votes_history')->insert([
                'vote_by' => $clientIP,
                'vote_type' => 'Upvote',
                'comment_id' => $request->comment_id
            ]);
            if ($insert_upvote) {
                $vote_count = $request->upvote + 1;
                $update_votes = DB::table('comments')
                    ->where('id', $request->comment_id)
                    ->update([
                        'upvotes' => $vote_count
                    ]);
                if ($update_votes) {
                    return json_encode([
                        'success' => 1,
                        'data' => 'Comment upvoted successfully'
                    ]);
                } else {
                    return json_encode([
                        'success' => 0,
                        'data' => 'Something went wrong'
                    ]);
                }
            } else {
                return json_encode([
                    'success' => 0,
                    'data' => 'Something went wrong'
                ]);
            }
        } else {
            return json_encode([
                'success' => 0,
                'data' => 'You have already voted for this comment'
            ]);
        }
    }

    public function downvote_comment(Request $request)
    {
        if (Auth::check()) {
            // User is logged in
            $clientIP = Auth::id();
        } else {
            // User is not logged in
            $clientIP = $this->getClientIP($request);
        }
        $check_if_voted = DB::table('comment_votes_history')->select('*')->where('comment_id', $request->comment_id)->where('vote_by', $clientIP)->count();
        if ($check_if_voted == 0) {
            $insert_upvote = DB::table('comment_votes_history')->insert([
                'vote_by' => $clientIP,
                'vote_type' => 'Downvotevote',
                'comment_id' => $request->comment_id
            ]);
            if ($insert_upvote) {
                $vote_count = $request->upvote + 1;
                $update_votes = DB::table('comments')
                    ->where('id', $request->comment_id)
                    ->update([
                        'downvotes' => $vote_count
                    ]);
                if ($update_votes) {
                    return json_encode([
                        'success' => 1,
                        'data' => 'Comment down voted successfully'
                    ]);
                } else {
                    return json_encode([
                        'success' => 0,
                        'data' => 'Something went wrong'
                    ]);
                }
            } else {
                return json_encode([
                    'success' => 0,
                    'data' => 'Something went wrong'
                ]);
            }
        } else {
            return json_encode([
                'success' => 0,
                'data' => 'You have already voted for this comment'
            ]);
        }
    }

    public function uncover_answers(Request $request)
    {
        $query = DB::table('questions_answer')->select('*')->where('questions_category', $request->question_id)->orderby('vote_count', 'DESC')->get();
        return json_encode([
            'success' => 1,
            'data' => $query
        ]);
    }

    public function get_topics(Request $request)
    {
        $query = DB::table('topics')->select('*')->orderBy('topic_name', 'desc')->limit(150)->get();
        return json_encode([
            'success' => 1,
            'data' => $query
        ]);
    }

    public function get_blogger(Request $request)
    {
        $bloggers = DB::table('users as u')
            ->join('posts as p', 'u.id', '=', 'p.user_id')
            ->select('u.name as username', 'u.image', 'u.bio', 'u.location', DB::raw('SUM(p.vote_count) as rating'))
            ->groupBy('u.id', 'u.name', 'u.image', 'u.bio', 'u.location')
            ->orderByDesc('rating')
            ->get();
        return json_encode([
            'success' => 1,
            'data' => $bloggers
        ]);
    }
    public function search_bloggers(Request $request)
    {
        $searchTerm = '%' . $request->to_search . '%';
        $bloggers = DB::table('users as u')
            ->join('posts as p', 'u.id', '=', 'p.user_id')
            ->select('u.name as username', 'u.image', 'u.bio', 'u.location', DB::raw('SUM(p.vote_count) as rating'))
            ->where('u.name', 'like', $searchTerm) // Adding the where condition for name search
            ->groupBy('u.id', 'u.name', 'u.image', 'u.bio', 'u.location')
            ->orderByDesc('rating')
            ->get();
        return json_encode([
            'success' => 1,
            'data' => $bloggers
        ]);
    }
    public function search_topics(Request $request)
    {
        $tosearch = $request->to_search;
        // $question_id = $request->question_id;
        // if(strlen($tosearch) >= 3){
        if (strlen($tosearch) > 0) {
            $topics = DB::table('topics')->select('*')
                ->where('topic_name', 'like', '%' . $tosearch . '%')
                ->orderBy('topic_name', 'desc')
                ->limit(100)
                ->get();
        } else {
            $topics = DB::table('topics')->select('*')
                ->orderBy('topic_name', 'desc')
                ->get();
        }
        // $topics = DB::table('topics')
        //     ->join('questions', 'topics.id', '=', 'questions.topic_id')
        //     ->where('questions.question', 'like', '%' . $tosearch . '%')
        //     ->select('topics.id', 'topics.topic_name')
        //     ->distinct()
        //     ->get();
        return json_encode([
            'success' => 1,
            'data' => $topics
        ]);
    }

    public function topic_name(Request $request)
    {
        $header_info = str_replace('-', ' ', $request->topic_name);
        $get_topic = DB::table('topics')->select('*')->where('topic_name', $header_info)->first();
        $topicName = str_replace('-', ' ', $request->topic_name);
        $perPage = 20; // Number of items per page
        $page = request()->get('page', 1); // Get the current page from the request
        $userIpAddress = $this->getClientIP($request);
        if (Auth::check()) {
            // User is logged in
            $userId = Auth::id();
            $get_last_three_locations = DB::table('recent_locations')
                ->where('user_id', $userId)
                ->orderBy('id', 'desc')
                ->offset(0)
                ->limit(3)
                ->get();
            $link_exsistance = 0;
            foreach ($get_last_three_locations as $recent_location) {
                if ($recent_location->location == $header_info) {
                    $link_exsistance = 1;
                }
            }
            if ($link_exsistance == 0) {
                $recent_location = DB::table('recent_locations')->insert([
                    'user_id' => $userId,
                    'location' => $header_info,
                    'location_link' => $request->topic_name
                ]);
            }
            if (!Auth::user()->hasVerifiedEmail()) {
                // User is not verified, redirect to a new route
                return redirect()->route('verification.notice');
            }
            $subQuery = UsersAnswer::select('question_id')
                ->where('user_ip_address', $userId)
                ->get();

            $get_this_user_votes = DB::table('user_answers')->select('questions.question', 'questions_answer.answers')
                ->join('questions', 'user_answers.question_id', 'questions.id')
                ->join('questions_answer', 'user_answers.answer_id', 'questions_answer.id')
                ->where('user_answers.user_ip_address', $userId)
                ->get();
        } else {
            // User is not logged in
            $subQuery = UsersAnswer::select('question_id')
                ->where('user_ip_address', $userIpAddress)
                ->get();
            $get_this_user_votes = '';
            $get_last_three_locations = DB::table('recent_locations')
                ->where('user_id', $userIpAddress)
                ->orderBy('id', 'desc')
                ->offset(0)
                ->limit(3)
                ->get();

            $link_exsistance = 0;
            foreach ($get_last_three_locations as $recent_location) {
                if ($recent_location->location_link == $header_info) {
                    $link_exsistance = 1;
                }
            }
            if ($link_exsistance == 0) {
                $recent_location = DB::table('recent_locations')->insert([
                    'user_id' => $userIpAddress,
                    'location' => $request->topic_name,
                    'location_link' => $header_info
                ]);
            }
        }

        // $questions = Questions::select(
        //     'questions.id AS question_id',
        //     'questions.question',
        //     'questions.question_category',
        //     DB::raw('SUBSTRING_INDEX(GROUP_CONCAT(DISTINCT CONCAT(answers, " ( Faves: ", vote_count, ")") ORDER BY vote_count DESC SEPARATOR "}"), "}", 3) AS top_answers'),
        //     DB::raw('SUM(vote_count) AS total_votes')
        // )
        //     ->join('questions_answer', 'questions.question_category', '=', 'questions_answer.questions_category')
        //     ->join('topics', 'questions.topic_id', '=', 'topics.id')
        //     ->where('topics.topic_name', $topicName)
        //     ->groupBy('questions.id', 'questions.question', 'questions.question_category')
        //     ->orderBy('total_votes', 'desc') // Sort by total_votes in descending order
        //     ->paginate($perPage, ['*'], 'page', $page);

        $questions = DB::table('questions')
            ->select(
                'questions.id AS question_id',
                'questions.question',
                'questions.question_category',
                DB::raw("SUBSTRING_INDEX(GROUP_CONCAT(DISTINCT CONCAT(answers, ' (Faves: ', vote_count, ')') ORDER BY vote_count DESC SEPARATOR '}'), '}', 3) AS top_answers"),
                DB::raw("SUM(COALESCE(questions_answer.vote_count, 0)) AS total_votes")
            )
            ->join('topics', 'questions.topic_id', '=', 'topics.id')
            ->leftJoin('questions_answer', function ($join) {
                $join->on('questions.question_category', '=', 'questions_answer.questions_category');
            })
            ->where('topics.topic_name', '=', $topicName)
            ->groupBy('questions.id', 'questions.question', 'questions.question_category')
            ->orderBy('total_votes', 'DESC')
            ->paginate($perPage, ['*'], 'page', $page);
        $get_topic_details = Topics::select('*')->where('topic_name', $topicName)->get();
        foreach ($get_topic_details as $get_topic_detail) {
            $topic_id = $get_topic_detail['id'];
        }

        $comments = DB::table('comments')
            ->select('users.name', DB::raw('SUM(comments.upvotes  - comments.downvotes) as upvotes'))
            ->join('users', 'comments.comment_by', '=', 'users.id')
            ->join('questions', 'comments.question_id', '=', 'questions.id')
            ->where('questions.topic_id', '=', $topic_id)
            ->orderByDesc('upvotes')
            ->limit(5)
            ->groupBy('users.name')
            ->get();

        $posts = DB::table('posts')
            ->select('posts.title', 'posts.blog_content', 'posts.featured_image', 'users.name', 'posts.created_at', 'posts.slug')
            ->join('users', 'posts.user_id', 'users.id')
            ->where('posts.topic_id', $topic_id)
            ->where('posts.status', 1)
            ->orderByDesc('posts.vote_count')
            ->limit(4)
            ->get();
        $keywords = 'ifave,' . $topicName;
        $meta_description = 'Rank and compare the best of everything in ' . $topicName . '. Save and share your faves among :';
        foreach ($questions as $index => $description) {
            if ($index <= 5) {
                $meta_description .= $index + 1 . '. ' . $description->question . ' ';
            }
        }
        $meta_description = substr($meta_description, 0, -1);
        $page_title = 'iFave - ' . $topicName;
        return view("topics", compact('header_info', 'get_topic', 'questions', 'comments', 'subQuery', 'topic_id', 'posts', 'keywords', 'topicName', 'meta_description', 'page_title', 'get_last_three_locations'));
    }

    public function comments_route(Request $request, $name)
    {
        $user_name = str_replace('-', " ", $name);
        $user_id = DB::table('users')
            ->where('name', $user_name)
            ->pluck('id');
        $user_id = $user_id[0];
        $query = DB::table('comments')
            ->select('comments.comments', 'comments.upvotes', 'questions.question', 'comments.id', 'comments.downvotes', 'topics.topic_name')
            ->join('questions', 'comments.question_id', 'questions.id')
            ->join('topics', 'questions.topic_id', 'topics.id')
            ->where('comments.comment_by', $user_id)
            ->get();
        $comments = DB::table('comments')
            ->select('users.name', DB::raw('SUM(comments.upvotes  - comments.downvotes) as upvotes'))
            ->join('users', 'comments.comment_by', '=', 'users.id')
            ->join('questions', 'comments.question_id', '=', 'questions.id')
            ->orderByDesc('upvotes')
            ->limit(5)
            ->groupBy('users.name')
            ->get();
        return view("comments.comments", compact('query', 'user_name', 'comments'));
    }
    public function getClientIP(Request $request)
    {
        $ip = $request->getClientIp();
        return $ip;
    }
    public function isURLComment($comment)
    {
        // Regular expression pattern to match a URL
        $pattern = '/\b(?:https?:\/\/|www\.)\S+\b/i';

        // Check if the comment contains a URL
        if (preg_match($pattern, $comment)) {
            return true; // URL found in the comment
        } else {
            return false; // No URL found in the comment
        }
    }
    public function get_comments_list(Request $request)
    {
        $topic_id = $request->topic_id;

        $comments = DB::table('comments')
            ->select('users.name', DB::raw('SUM(comments.upvotes  - comments.downvotes) as upvotes'))
            ->join('users', 'comments.comment_by', '=', 'users.id')
            ->join('questions', 'comments.question_id', '=', 'questions.id')
            ->where('questions.topic_id', '=', $topic_id)
            ->orderByDesc('upvotes')
            ->groupBy('users.name')
            ->get();

        return json_encode(['success' => 1, 'data' => $comments]);
    }
    public function get_comments_list_by_name(Request $request)
    {
        $comments = DB::table('comments')
            ->select('users.name', DB::raw('SUM(comments.upvotes - comments.downvotes) as upvotes'))
            ->join('users', 'comments.comment_by', '=', 'users.id')
            ->join('questions', 'comments.question_id', '=', 'questions.id')
            ->where('questions.topic_id', '=', $request->topic_id)
            ->where('users.name', 'like', '%' . $request->user_name . '%')
            ->orderByDesc('upvotes')
            ->groupBy('users.name')
            ->get();

        return json_encode(['success' => 1, 'data' => $comments]);
    }
    public function get_comments_list_all(Request $request)
    {
        //$topic_id = $request->topic_id;

        $comments = DB::table('comments')
            ->select('users.name', DB::raw('SUM(comments.upvotes - comments.downvotes) as upvotes'))
            ->join('users', 'comments.comment_by', '=', 'users.id')
            ->join('questions', 'comments.question_id', '=', 'questions.id')
            ->orderByDesc('upvotes')
            ->groupBy('users.name')
            ->get();

        return json_encode(['success' => 1, 'data' => $comments]);
    }
    public function add_thoughts(Request $request)
    {
        $query = DB::table('question_thoughts')->select('*')->where('question_id', $request->question_id)->get();
        if (count($query) > 0) {
            $add_though = DB::table('question_thoughts')->where('question_id', $request->question_id)->update([
                'thoughts' => $request->thoughts,
            ]);
        } else {
            $add_though = DB::table('question_thoughts')->insert([
                'question_id' => $request->question_id,
                'thoughts' => $request->thoughts,
            ]);
        }
        if ($add_though) {
            return json_encode([
                'success' => 1,
                'data' => 'Thoughts added successfully'
            ]);
        } else {
            return json_encode([
                'success' => 0,
                'data' => 'Something went wrong'
            ]);
        }
    }
    public function not_found(Request $request)
    {
        $topicName = "The World";
        $perPage = 20; // Number of items per page
        $page = request()->get('page', 1); // Get the current page from the request
        $userIpAddress = $this->getClientIP($request);
        if (Auth::check()) {
            // User is logged in
            if (!Auth::user()->hasVerifiedEmail()) {
                // User is not verified, redirect to a new route
                return redirect()->route('verification.notice');
            }
            $userId = Auth::id();
            $subQuery = UsersAnswer::select('question_id')
                ->where('user_ip_address', $userId)
                ->get();

            $get_this_user_votes = DB::table('user_answers')->select('questions.question', 'questions_answer.answers')
                ->join('questions', 'user_answers.question_id', 'questions.id')
                ->join('questions_answer', 'user_answers.answer_id', 'questions_answer.id')
                ->where('user_answers.user_ip_address', $userId)
                ->get();
            $get_last_three_locations = DB::table('recent_locations')
                ->where('user_id', $userId)
                ->orderBy('id', 'desc')
                ->offset(0)
                ->limit(3)
                ->get();
        } else {
            // User is not logged in
            $subQuery = UsersAnswer::select('question_id')
                ->where('user_ip_address', $userIpAddress)
                ->get();
            $get_this_user_votes = '';
            $get_last_three_locations = DB::table('recent_locations')
                ->where('user_id', $userIpAddress)
                ->orderBy('id', 'desc')
                ->offset(0)
                ->limit(3)
                ->get();
        }
        $questions = DB::table('questions')
            ->select(
                'questions.id AS question_id',
                'questions.question',
                'questions.question_category',
                DB::raw("SUBSTRING_INDEX(GROUP_CONCAT(DISTINCT CONCAT(answers, ' (Faves: ', vote_count, ')') ORDER BY vote_count DESC SEPARATOR '}'), '}', 3) AS top_answers"),
                DB::raw("SUM(COALESCE(questions_answer.vote_count, 0)) AS total_votes")
            )
            ->join('topics', 'questions.topic_id', '=', 'topics.id')
            ->leftJoin('questions_answer', function ($join) {
                $join->on('questions.question_category', '=', 'questions_answer.questions_category');
            })
            ->where('topics.topic_name', '=', $topicName)
            ->groupBy('questions.id', 'questions.question', 'questions.question_category')
            ->orderBy('total_votes', 'DESC')
            ->paginate($perPage, ['*'], 'page', $page);


        $get_topic_details = Topics::select('*')->where('topic_name', $topicName)->get();
        foreach ($get_topic_details as $get_topic_detail) {
            $topic_id = $get_topic_detail['id'];
        }

        $comments = DB::table('comments')
            ->select('users.name', DB::raw('SUM(comments.upvotes  - comments.downvotes) as upvotes'))
            ->join('users', 'comments.comment_by', '=', 'users.id')
            ->join('questions', 'comments.question_id', '=', 'questions.id')
            ->where('questions.topic_id', '=', $topic_id)
            ->orderByDesc('upvotes')
            ->limit(5)
            ->groupBy('users.name')
            ->get();

        //query to get posts data 
        $posts = DB::table('posts')
            ->select('posts.title', 'posts.blog_content', 'posts.featured_image', 'users.name', 'posts.created_at', 'posts.slug')
            ->join('users', 'posts.user_id', 'users.id')
            ->where('posts.topic_id', $topic_id)
            ->where('posts.status', 1)
            ->orderByDesc('posts.vote_count')
            ->limit(4)
            ->get();

        $keywords = 'ifave,' . $topicName;
        $meta_description = 'Rank and compare the best of everything in ' . $topicName . '. Save and share your faves among :';
        foreach ($questions as $index => $description) {
            if ($index <= 5) {
                $meta_description .= $index + 1 . '. ' . $description->question . ' ';
            }
        }
        $meta_description = substr($meta_description, 0, -1);
        $page_title = 'iFave - ' . $topicName;

        return view('errors.404', compact('questions', 'subQuery', 'comments', 'topic_id', 'posts', 'keywords', 'topicName', 'meta_description', 'page_title', 'get_last_three_locations'));
    }
    public function comments_list_by_username_all(Request $request)
    {
        $comments = DB::table('comments')
            ->select('users.name', DB::raw('SUM(comments.upvotes  - comments.downvotes) as upvotes'))
            ->join('users', 'comments.comment_by', '=', 'users.id')
            ->join('questions', 'comments.question_id', '=', 'questions.id')
            ->where('users.name', 'like', '%' . $request->user_name . '%')
            ->orderByDesc('upvotes')
            ->groupBy('users.name')
            ->get();
        return json_encode(['success' => 1, 'data' => $comments]);
    }
}
