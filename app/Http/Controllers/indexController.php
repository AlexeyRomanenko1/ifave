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
            // $questions = Questions::select('questions.id as question_id', 'questions.question', 'questions.question_category')
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
            //     ->groupBy('questions.id', 'questions.question', 'questions.question_category', 'qav.total_votes')
            //     ->selectRaw('GROUP_CONCAT(CONCAT(qa.answers, " (Votes: ", qa.vote_count, ")") SEPARATOR ", ") AS top_answers')
            //     ->selectRaw('qav.total_votes AS question_votes')
            //     ->get();

            $questions = Questions::select('questions.id as question_id', 'questions.question', 'questions.question_category', 'qa.top_answers', 'qa.total_votes')
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
            ->groupBy('questions.id', 'questions.question', 'questions.question_category', 'qa.top_answers', 'qa.total_votes')
            ->get();
            // $subQuery = DB::table('user_answers')
            //     ->select('question_id', 'answer_id')
            //     ->where('user_ip_address', $userIpAddress)
            //     ->groupBy('question_id')
            //     ->toSql();

            //     $questions = DB::select("
            //     SELECT
            //         questions.id AS question_id,
            //         questions.question,
            //         questions.question_category,

            //         GROUP_CONCAT(CONCAT(IFNULL(ua.answer_name, 'Place'),' (Votes: ', qa.vote_count, ') ') SEPARATOR ', ') AS top_answers,
            //         qav.total_votes AS question_votes
            //     FROM
            //         questions
            //         INNER JOIN questions_answer ON questions.question_category = questions_answer.questions_category
            //         LEFT JOIN (
            //             SELECT answers, questions_category, vote_count
            //             FROM questions_answer
            //             ORDER BY vote_count DESC
            //             LIMIT 3
            //         ) AS qa ON questions_answer.answers = qa.answers
            //         LEFT JOIN (
            //             SELECT questions_category, SUM(vote_count) AS total_votes
            //             FROM questions_answer
            //             GROUP BY questions_category
            //         ) AS qav ON questions.question_category = qav.questions_category
            //         LEFT JOIN (
            //             SELECT
            //                 question_id,
            //                 IF(user_answers.answer_id IS NOT NULL, questions_answer.answers, '****') AS answer_name
            //             FROM
            //                 user_answers
            //                 LEFT JOIN questions_answer ON user_answers.answer_id = questions_answer.id
            //             WHERE
            //                 user_answers.user_ip_address = :userIpAddress
            //         ) AS ua ON questions.id = ua.question_id
            //     GROUP BY
            //         questions.id,
            //         questions.question,
            //         questions.question_category,
            //         qav.total_votes
            // ", ['userIpAddress' => $userIpAddress]);

            return json_encode(['success' => 1, 'data' => $questions, 'this_user_answers' => $subQuery]);
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
    public function getClientIP(Request $request)
    {
        $ip = $request->getClientIp();
        return $ip;
    }
}
