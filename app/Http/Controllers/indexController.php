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
            // $topics = Topics::all();
            // $questions = Topics::join('questions', 'topics.id', '=', 'questions.topic_id')
            // ->join('questions_answer', 'questions.question_category', '=', 'questions_answer.questions_category')
            // ->select('questions.question', 'questions_answer.answers', 'questions_answer.vote_count')
            // ->get();
            // $questions = Questions::select('questions.question', 'questions.question_category')
            //     ->join('questions_answer', 'questions.question_category', '=', 'questions_answer.questions_category')
            //     ->leftJoin(DB::raw('
            //     (SELECT answers, questions_category, vote_count
            //     FROM questions_answer
            //     ORDER BY vote_count DESC
            //     LIMIT 3) AS qa
            // '), 'questions_answer.answers', '=', 'qa.answers')
            //     ->leftJoin(DB::raw('
            //     (SELECT questions_category, SUM(vote_count) AS total_votes
            //     FROM questions_answer
            //     GROUP BY questions_category) AS qav
            // '), 'questions.question_category', '=', 'qav.questions_category')
            //     ->groupBy('questions.question', 'questions.question_category', 'qav.total_votes')
            //     ->selectRaw('GROUP_CONCAT(CONCAT("Place", " (Votes: ", qa.vote_count, ")") SEPARATOR ", ") AS top_answers')
            //     ->selectRaw('qav.total_votes AS question_votes')
            //     ->get();
            $questions = Questions::select('questions.id as question_id', 'questions.question', 'questions.question_category')
                ->join('questions_answer', 'questions.question_category', '=', 'questions_answer.questions_category')
                ->leftJoin(DB::raw('
        (SELECT answers, questions_category, vote_count
        FROM questions_answer
        ORDER BY vote_count DESC
        LIMIT 3) AS qa
    '), 'questions_answer.answers', '=', 'qa.answers')
                ->leftJoin(DB::raw('
        (SELECT questions_category, SUM(vote_count) AS total_votes
        FROM questions_answer
        GROUP BY questions_category) AS qav
    '), 'questions.question_category', '=', 'qav.questions_category')
                ->groupBy('questions.id', 'questions.question', 'questions.question_category', 'qav.total_votes')
                ->selectRaw('GROUP_CONCAT(CONCAT("Place", " (Votes: ", qa.vote_count, ")") SEPARATOR ", ") AS top_answers')
                ->selectRaw('qav.total_votes AS question_votes')
                ->get();
            return json_encode(['success' => 1, 'data' => $questions]);
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
            // }else{
            //     $question_answers = Questions::select('questions.id', 'questions.question')
            //     ->join('questions_answer', 'questions.question_category', '=', 'questions_answer.questions_category')
            //     ->where('questions.id', '=', $question_id)
            //     // ->where('questions_answer.answers', 'like', '%' . $tosearch . '%')
            //     ->orderBy('questions_answer.answers', 'desc')
            //     ->select('questions_answer.answers', 'questions_answer.vote_count')
            //     ->get();
            //     return json_encode([
            //         'success' => 1,
            //         'data' => $question_answers
            //     ]);
            // }
        }
        return json_encode([
            'success' => 0,
            'data' => 'Invalid request'
        ]);
    }
    public function entervote(Request $request)
    {
        $answer_id = $request->answer_id;
        $query = Questionsanswers::select('questions_answer.id', 'questions_answer.vote_count')
            ->join('questions', 'questions_answer.questions_category', '=', 'questions.question_category')
            ->where('questions_answer.id', '=', $answer_id)
            ->select('questions.id as question_id', 'questions_answer.id', 'questions_answer.vote_count')
            ->get();
        foreach ($query as $result) {
            $question_id = $result->question_id;
            //$answer_id = $result->id;
            $vote_count = $result->vote_count;
            // return json_encode([
            //     'success' => 1,
            //     'data' => $result->id
            // ]);
        }
        // $user_ip_address=Request::getClientIp(true);

        // $insert_user_vote=UsersAnswer::create([
        //     'question_id' => 'John Doe',
        //     'answer_id' => 'john@example.com',
        //     'user_id_address' => bcrypt('password')
        // ]);
        $clientIP = $this->getClientIP($request);
        return json_encode([
            'success' => 1,
            'data' => $clientIP
        ]);
    }
    public function getClientIP(Request $request)
    {
        $ip = $request->getClientIp();
        return $ip;
    }
}
