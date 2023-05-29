<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Questions;
use App\Models\Topics;
use App\Models\Questionsanswers;
use App\Models\UsersAnswer;
use Illuminate\Support\Facades\DB;

class indexController extends Controller
{
    //
    public function indexonloadRequest(Request $request)
    {

        if ($request->task == 'get_questions') {
            $userIpAddress = $this->getClientIP($request);
            $subQuery = UsersAnswer::select('question_id')
                ->where('user_ip_address', $userIpAddress)
                ->get();
            $questions = Questions::select('questions.id as question_id', 'questions.question', 'questions.question_category', 'qa.top_answers', 'totqa.total_votes')
                ->join('questions_answer', 'questions.question_category', '=', 'questions_answer.questions_category')
                ->leftJoin(DB::raw('
                (SELECT questions_category, GROUP_CONCAT(answers, " ( Votes: ",vote_count,")" SEPARATOR ", ") AS top_answers, SUM(vote_count) AS total_votes
                FROM (
                    SELECT questions_category, answers, vote_count,     
                    ROW_NUMBER() OVER (PARTITION BY questions_category ORDER BY vote_count DESC) AS row_num
                    FROM questions_answer
                ) AS qa
                WHERE row_num <= 3
                GROUP BY questions_category) AS qa
            '), 'questions.question_category', '=', 'qa.questions_category')
                ->leftJoin(DB::raw('
            (SELECT questions_category, GROUP_CONCAT(answers, " ( Votes: ",vote_count,")" SEPARATOR ", ") AS top_answers, SUM(vote_count) AS total_votes
            FROM (
                SELECT questions_category, answers, vote_count,     
                ROW_NUMBER() OVER (PARTITION BY questions_category ORDER BY vote_count DESC) AS row_num
                FROM questions_answer
            ) AS totqa
            GROUP BY questions_category) AS totqa
        '), 'questions.question_category', '=', 'totqa.questions_category')
                ->groupBy('questions.id', 'questions.question', 'questions.question_category', 'qa.top_answers', 'qa.total_votes')
                ->get();

            // topics 
            $hot_topics = array();
            $topics = Topics::select('topics.id as topic_id', 'topics.topic_name', 'questions.id as question_id', 'questions.question', 'questions.question_category')->JOIN('questions', 'topics.id', '=', 'questions.topic_id')->get();
            foreach ($topics as $topic) {
                $question = QuestionsAnswers::select('*')->where('questions_category', '=', $topic->question_category)->where('answers', '<>', '')->orderby('vote_count', 'DESC')->limit(3)->get();
                $answers = QuestionsAnswers::where('questions_category', $topic->question_category)->sum('vote_count');
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

            return json_encode(['success' => 1, 'data' => $questions, 'this_user_answers' => $subQuery, 'topics' => $hot_topics]);
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
        $clientIP = $this->getClientIP($request);
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
        if ($count_answer == 0) {
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
            $questions = Questions::select('id', 'question')
                ->where('question', 'like', '%' . $tosearch . '%')
                ->orderBy('question', 'desc')
                ->get();
            $topics = DB::table('topics')
                ->join('questions', 'topics.id', '=', 'questions.topic_id')
                ->where('questions.question', 'like', '%' . $tosearch . '%')
                ->select('topics.id', 'topics.topic_name')
                ->distinct()
                ->get();
            return json_encode([
                'success' => 1,
                'data' => $questions,
                'topics' => $topics
            ]);
        }
        return json_encode([
            'success' => 0,
            'data' => 'Invalid request'
        ]);
    }
    public function getClientIP(Request $request)
    {
        $ip = $request->getClientIp();
        return $ip;
    }
}
