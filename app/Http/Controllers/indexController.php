<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Questions;
use App\Models\Topics;
use App\Models\Questionsanswers;
use App\Models\UsersAnswer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class indexController extends Controller
{
    //
    public function indexonloadRequest(Request $request)
    {

        if ($request->task == 'get_questions') {
            $userIpAddress = $this->getClientIP($request);
            if (Auth::check()) {
                // User is logged in
                $userId = Auth::id();
                $subQuery = UsersAnswer::select('question_id')
                    ->where('user_ip_address', $userId)
                    ->get();
            } else {
                // User is not logged in
                $subQuery = UsersAnswer::select('question_id')
                    ->where('user_ip_address', $userIpAddress)
                    ->get();
            }
            if (isset($request->topic_name)) {
                $topicName = $request->topic_name;
            } else {
                $topicName = "movies";
            }
            $questions = Questions::select('questions.id as question_id', 'questions.question', 'questions.question_category', 'qa.top_answers', 'totqa.total_votes')
                ->join('questions_answer', 'questions.question_category', '=', 'questions_answer.questions_category')
                ->leftJoin(DB::raw('
                (SELECT questions_category, GROUP_CONCAT(answers, " ( Faves: ",vote_count,")" SEPARATOR "}") AS top_answers, SUM(vote_count) AS total_votes
                FROM (
                    SELECT questions_category, answers, vote_count,     
                    ROW_NUMBER() OVER (PARTITION BY questions_category ORDER BY vote_count DESC) AS row_num
                    FROM questions_answer
                ) AS qa
                WHERE row_num <= 3
                GROUP BY questions_category) AS qa
            '), 'questions.question_category', '=', 'qa.questions_category')
                ->leftJoin(DB::raw('
                (SELECT questions_category, GROUP_CONCAT(answers, " ( Faves: ",vote_count,")" SEPARATOR "}") AS top_answers, SUM(vote_count) AS total_votes
                FROM (
                    SELECT questions_category, answers, vote_count,     
                    ROW_NUMBER() OVER (PARTITION BY questions_category ORDER BY vote_count DESC) AS row_num
                    FROM questions_answer
                ) AS totqa
                GROUP BY questions_category) AS totqa
            '), 'questions.question_category', '=', 'totqa.questions_category')
                ->join('topics', 'questions.topic_id', '=', 'topics.id')
                ->where('topics.topic_name', $topicName)
                ->groupBy('questions.id', 'questions.question', 'questions.question_category', 'qa.top_answers', 'qa.total_votes')
                ->get();

            // topics 
            $hot_topics = array();
            $topics = Topics::select('topics.id as topic_id', 'topics.topic_name', 'questions.id as question_id', 'questions.question', 'questions.question_category')->JOIN('questions', 'topics.id', '=', 'questions.topic_id')->get();
            foreach ($topics as $topic) {
                $question = Questionsanswers::select('*')->where('questions_category', '=', $topic->question_category)->where('answers', '<>', '')->orderby('vote_count', 'DESC')->limit(3)->get();
                $answers = Questionsanswers::where('questions_category', $topic->question_category)->sum('vote_count');
                if (count($question) > 0) {
                    //foreach ($answers as $answer) {
                    $question['total_sum'] = $answers;
                    //}
                    $question['question_id'] = $topic->question_id;
                    $question['question'] = $topic->question;
                    $question['topic_name'] = $topic->topic_name;
                    $hot_topics[] = $question;
                }
            }
            $get_topic_details=Topics::select('*')->where('topic_name',$topicName)->get();
            foreach($get_topic_details as $get_topic_detail){
                $topic_id=$get_topic_detail['id'];
            }
            $questions_slider = Questions::select('*')->where('topic_id',$topic_id)->get();
            return json_encode(['success' => 1, 'data' => $questions, 'this_user_answers' => $subQuery, 'topics' => $hot_topics, 'topic_name' => $topicName, 'questions_slider' => $questions_slider]);
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
        if ($request->task == 'searchQuestions') {
            $tosearch = $request->search;
            $topicName = $request->id;
            if (strlen($tosearch) > 0) {
                // $question_id = $request->question_id;
                // if(strlen($tosearch) >= 3){

                $questions = Questions::select('questions.id as question_id', 'questions.question', 'questions.question_category', 'qa.top_answers', 'totqa.total_votes')
                    ->join('questions_answer', 'questions.question_category', '=', 'questions_answer.questions_category')
                    ->leftJoin(DB::raw('
                    (SELECT questions_category, GROUP_CONCAT(answers, " ( Faves: ",vote_count,")" SEPARATOR "} ") AS top_answers, SUM(vote_count) AS total_votes
                    FROM (
                        SELECT questions_category, answers, vote_count,     
                        ROW_NUMBER() OVER (PARTITION BY questions_category ORDER BY vote_count DESC) AS row_num
                        FROM questions_answer
                    ) AS qa
                    WHERE row_num <= 3
                    GROUP BY questions_category) AS qa
                '), 'questions.question_category', '=', 'qa.questions_category')
                    ->leftJoin(DB::raw('
                    (SELECT questions_category, GROUP_CONCAT(answers, " ( Faves: ",vote_count,")" SEPARATOR "} ") AS top_answers, SUM(vote_count) AS total_votes
                    FROM (
                        SELECT questions_category, answers, vote_count,     
                        ROW_NUMBER() OVER (PARTITION BY questions_category ORDER BY vote_count DESC) AS row_num
                        FROM questions_answer
                    ) AS totqa
                    GROUP BY questions_category) AS totqa
                '), 'questions.question_category', '=', 'totqa.questions_category')
                    ->join('topics', 'questions.topic_id', '=', 'topics.id')
                    ->where('topics.id', $topicName)
                    ->where('questions.question', 'like', '%' . $tosearch . '%')
                    ->groupBy('questions.id', 'questions.question', 'questions.question_category', 'qa.top_answers', 'qa.total_votes')
                    ->get();
                return json_encode([
                    'success' => 1,
                    'data' => $questions
                ]);
            } else {
                // $topicName = $request->id;
                $questions = Questions::select('questions.id as question_id', 'questions.question', 'questions.question_category', 'qa.top_answers', 'totqa.total_votes')
                    ->join('questions_answer', 'questions.question_category', '=', 'questions_answer.questions_category')
                    ->leftJoin(DB::raw('
                    (SELECT questions_category, GROUP_CONCAT(answers, " ( Faves: ",vote_count,")" SEPARATOR "} ") AS top_answers, SUM(vote_count) AS total_votes
                    FROM (
                        SELECT questions_category, answers, vote_count,     
                        ROW_NUMBER() OVER (PARTITION BY questions_category ORDER BY vote_count DESC) AS row_num
                        FROM questions_answer
                    ) AS qa
                    WHERE row_num <= 3
                    GROUP BY questions_category) AS qa
                '), 'questions.question_category', '=', 'qa.questions_category')
                    ->leftJoin(DB::raw('
                    (SELECT questions_category, GROUP_CONCAT(answers, " ( Faves: ",vote_count,")" SEPARATOR ", ") AS top_answers, SUM(vote_count) AS total_votes
                    FROM (
                        SELECT questions_category, answers, vote_count,     
                        ROW_NUMBER() OVER (PARTITION BY questions_category ORDER BY vote_count DESC) AS row_num
                        FROM questions_answer
                    ) AS totqa
                    GROUP BY questions_category) AS totqa
                '), 'questions.question_category', '=', 'totqa.questions_category')
                    ->join('topics', 'questions.topic_id', '=', 'topics.id')
                    ->where('topics.id', $topicName)

                    ->groupBy('questions.id', 'questions.question', 'questions.question_category', 'qa.top_answers', 'qa.total_votes')
                    ->get();
                return json_encode([
                    'success' => 1,
                    'data' => $questions
                ]);
            }
        }
        return json_encode([
            'success' => 0,
            'data' => 'Invalid request'
        ]);
    }

    public function questions_details(Request $request, $id)
    {
        if (Auth::check()) {
            // User is logged in
            $clientIP = Auth::id();
        } else {
            // User is not logged in
            $clientIP = $this->getClientIP($request);
        }
        // return $id;
        $question_id = $id;
        $get_user_answers = UsersAnswer::select('user_answers.id', 'questions_answer.answers', 'user_answers.answer_id')->join('questions_answer', 'user_answers.answer_id', 'questions_answer.id')->where('user_answers.user_ip_address', '=', $clientIP)->where('user_answers.question_id', '=', $id)->get();
        $question_answers = Questions::select('questions.id', 'questions.question')
            ->join('questions_answer', 'questions.question_category', '=', 'questions_answer.questions_category')
            ->where('questions.id', '=', $question_id)
            ->orderBy('questions_answer.answers', 'desc')
            ->select('questions_answer.id as answer_id', 'questions_answer.answers', 'questions_answer.vote_count')
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

        $get_comments = DB::table('comments')->select('*')
            ->selectRaw('(upvotes - downvotes) as difference')
            ->where('question_id', $question_id)
            ->orderBy('difference', 'DESC')
            ->get();
        // $data=[
        //     'question_details'=>$question_details,
        //     'question_answers'=>$question_answers
        // ];
        return view('questions', compact('header_info', 'question_answers', 'get_user_answers', 'get_comments'));
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
            return redirect()->back()->with('error', 'You comments length is exceeding the limit of 1000 characters!');
        } else {
            $insert_comments = DB::table('comments')->insert([
                'comments' => $comments,
                'question_id' => $question_id,
                'comment_by' => $clientIP
            ]);

            if ($insert_comments) {
                return redirect()->back()->with('success', 'Comment added successfully!');
            } else {
                return redirect()->back()->with('error', 'Something went wrong!');
            }
        }
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
        $query = DB::table('topics')->select('*')->orderBy('topic_name', 'desc')->get();
        return json_encode([
            'success' => 1,
            'data' => $query
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
        $header_info = $request->topic_name;
        $get_topic = DB::table('topics')->select('*')->where('topic_name', $request->topic_name)->first();
        //$topic_id=$get_topic;
        // foreach($get_topic as $topic){
        //     $topic_id=$topic['id'];
        // }
        return view("topics", compact('header_info', 'get_topic'));
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
}
