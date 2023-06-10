<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topics;
use App\Models\Questions;
use App\Models\Questionsanswers;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function import_questions(Request $request)
    {
        if (isset($request->import_questions)) {
            //$csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
            // Validate the uploaded file
            $request->validate([
                'import_questions' => 'required|mimes:csv,txt'
            ]);

            // Get the uploaded file
            $file = $request->file('import_questions');

            // Read the CSV file
            $csvData = file_get_contents($file);

            // Split the CSV data into an array of rows
            $rows = explode("\n", $csvData);

            // Remove the first row (header)
            unset($rows[0]);
            // print_r();
            $row_count = count($rows) - 1;
            $i = 0;
            foreach ($rows as $row) {
                if ($i < $row_count) {
                    $data = str_getcsv($row);
                    $question = $data[0];
                    $topic_name = $data[1];
                    $question_category = $data[2];
                    // print_r($data);
                    // return;
                    //redirect()->back()->with('message', "$data");
                    // Select topics from question if exsists or not
                    $check_topics = Topics::select('id', 'topic_name')
                        ->where('topic_name', '=',  $topic_name)
                        ->get();
                    // return redirect()->back()->with('message', "$question");
                    if (count($check_topics) == 0) {
                        $insert_topic =  DB::table('topics')->insert([
                            'topic_name' => $topic_name
                        ]);
                        $get_this_topic = Topics::select('id')
                            ->where('topic_name', '=', $topic_name)
                            ->get();

                        $new_topic_id =  $get_this_topic[0]->id;
                        $check_question = Questions::select('question')
                            ->where('question', '=', $question)
                            ->where('topic_id', '=', $new_topic_id)
                            ->get();
                        if (count($check_question) == 0) {
                            $insert_question =  DB::table('questions')->insert([
                                'question' => $question,
                                'topic_id' => $new_topic_id,
                                'question_category' => $question_category
                            ]);
                        }
                    }
                    if (count($check_topics) > 0) {
                        $get_this_topic = Topics::select('id')
                            ->where('topic_name', '=', $topic_name)
                            ->get();

                        $new_topic_id =  $get_this_topic[0]->id;
                        $check_question = Questions::select('question')
                            ->where('question', '=', $question)
                            ->where('topic_id', '=', $new_topic_id)
                            ->get();
                        if (count($check_question) == 0) {
                            $insert_question =  DB::table('questions')->insert([
                                'question' => $question,
                                'topic_id' => $new_topic_id,
                                'question_category' => $question_category
                            ]);
                        }
                    }
                    // Insert data into the database table
                    // DB::table('your_table')->insert([
                    //     'column1' => $column1,
                    //     'column2' => $column2,
                    // ]);

                    // return redirect()->back()->with('message', "$column1");
                }
                $i++;
            }

            // Process each row and insert into the database
            return redirect()->back()->with('message', "Success Questions imported Successfully!");
        } else {
            return redirect()->back()->with('error', 'Error!');
        }
    }
    public function import_answer(Request $request)
    {
        if (isset($request->import_answer)) {
            //$csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
            // Validate the uploaded file
            $request->validate([
                'import_answer' => 'required|mimes:csv,txt'
            ]);

            // Get the uploaded file
            $file = $request->file('import_answer');

            // Read the CSV file
            $csvData = file_get_contents($file);

            // Split the CSV data into an array of rows
            $rows = explode("\n", $csvData);

            // Remove the first row (header)
            unset($rows[0]);
            // print_r();
            $row_count = count($rows) - 1;
            $i = 0;
            foreach ($rows as $row) {
                if ($i < $row_count) {
                    $data = str_getcsv($row);
                    $answers = $data[0];
                    $vote_count = $data[1];
                    $questions_category = $data[2];

                    // check if answer exsists
                    $check_answer = Questionsanswers::select('answers')
                        ->where('answers', '=', $answers)
                        ->where('questions_category', '=', $questions_category)
                        ->get();
                    if (count($check_answer) == 0) {
                        $insert_answer =  DB::table('questions_answer')->insert([
                            'answers' => $answers,
                            'vote_count' => $vote_count,
                            'questions_category' => $questions_category
                        ]);
                    }
                }
                $i++;
            }
            return redirect()->back()->with('message', "Success Answers imported Successfully!");
        } else {
            return redirect()->back()->with('error', "Error!");
        }
    }
}
