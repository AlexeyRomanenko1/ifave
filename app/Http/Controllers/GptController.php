<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Questions;
use App\Models\Topics;
use App\Models\Questionsanswers;
use App\Models\UsersAnswer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Http\Client\RequestException;

class GptController extends Controller
{
    // function to create and check personality potrait
    public function create_personality_potrait(Request $request)
    {
        if (!Auth::user()->hasVerifiedEmail()) {
            // User is not verified, redirect to a new route
            return json_encode([
                'success' => 0,
                'data' => 'User not loggedin'
            ]);
        }
        $userId = Auth::id();
        $check_count = DB::table('user_answers')->select('user_ip_address')->where('user_ip_address', $userId)->count();
        if ($check_count > 4) {
            $currentTime = Carbon::now();
            $check_time = DB::table('personality_potrait')->where('user_id', $userId)->where('created_at', $currentTime->toDateString())->count();
            //return $currentTime->toDateString();
            if ($check_time != 0) {
                $user_faves = DB::table('questions')
                    ->select('questions.question', 'questions_answer.answers')
                    ->join('questions_answer', 'questions.question_category', '=', 'questions_answer.questions_category')
                    ->join('user_answers', 'user_answers.answer_id', '=', 'questions_answer.id')
                    ->where('user_answers.user_ip_address', $userId)
                    ->distinct() // Use distinct
                    ->inRandomOrder()
                    ->take(5) // Use take instead of limit
                    ->get();
                $user_selections = '';
                foreach ($user_faves as $user_answers) {
                    $user_selections .= $user_answers->answers . '';
                }
                //$prompt = 'Give me personality potrait of mine based on these keywords ' . $user_selections . ' give me a response of only 100 words';
                // $prompt = 'Define my personality of maximum 100 words based on keywords  Atif Aslam, Babar Azam, Laravel , Cricket, Dota 2';
                $prompt = 'Define my personality of maximum 100 words based on keywords ' . $user_selections . '.Instead of using I am/ I have etc use you. Also please dont mention keywords word in response' ;
                //return $prompt;
                // $response = Http::withHeaders([
                //     'Authorization' => 'Bearer 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',
                // ])->post('https://api.openai.com/v1/engines/davinci/completions', [
                //     'prompt' => $prompt,
                //     'max_tokens' => 100,  // Adjust this as needed
                // ]);

                // $result = $response->json();

                // // The generated text can be extracted using $result['choices'][0]['text']
                // $generatedText = $result['choices'][0]['text'];
                // return $result;
                $apiKey = 'api - key'; // Replace with your OpenAI API key
                $model = 'gpt-3.5-turbo-instruct';
                $prompt = $prompt;
                $maxTokens = 150;
                $temperature = 0;
                try {
                    $response = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $apiKey,
                    ])->post('https://api.openai.com/v1/engines/' . $model . '/completions', [
                        'prompt' => $prompt,
                        'max_tokens' => $maxTokens,
                        'temperature' => $temperature,
                    ]);

                    if ($response->successful()) {
                        $data = $response->json();
                        // $data = json_decode($data, true);
                        // foreach($data as $gpt_response){
                        //     return $gpt_response[choices];
                        // }
                        // $responseArray = json_decode($data, true);

                        if (isset($data['choices'])) {
                            foreach ($data['choices'] as $choice) {
                                $text = $choice['text'];
                            }
                        }
                        //return json_encode($data);
                        $insert_personality = DB::table('personality_potrait')->insert([
                            'user_id' => $userId,
                            'personality' => $text,
                        ]);
                        if ($insert_personality) {
                            return json_encode([
                                'success' => 1,
                                'data' => $text
                            ]);
                        } else {
                            return json_encode([
                                'success' => 0,
                                'data' => 'Something went wrong'
                            ]);
                        }
                    } else {
                        return "Error: " . $response->status() . " - " . $response->body();
                    }
                } catch (RequestException $e) {
                    return "Error: " . $e->getMessage();
                }
            } else {
                return json_encode([
                    'success' => 0,
                    'data' => 'You can request for personality potrait once a day'
                ]);
            }
        } else {
            return json_encode([
                'success' => 0,
                'data' => 'Vote for minimun of 5 questions to create personality potrait!'
            ]);
        }
    }
}
