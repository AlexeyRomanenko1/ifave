<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Topics;
use App\Models\Questions;
use App\Models\Questionsanswers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use ZipArchive;

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
        $userId = Auth::id();
        $this_user = DB::table('users')->select('*')->where('id', $userId)->get();
        foreach ($this_user as $user_details) {
            //print_r($user_details);
            $user_type = $user_details->user_type;
        }
        if ($user_type == 1) {
            return view('home');
        } else {
            return view('index');
        }
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
            $current_date = date('Y-m-d H:i:s');
            foreach ($rows as $row) {
                if ($i < $row_count) {
                    // $row = mb_convert_encoding($row, 'UTF-8', 'UTF-8');
                    // $row = mb_convert_encoding($row, 'UTF-8', 'auto');
                    $row = iconv('ISO-8859-1', 'UTF-8//IGNORE', $row);
                    $data = str_getcsv($row);
                    $answers = strval($data[0]);
                    $vote_count = $data[1];
                    $questions_category = $data[2];

                    // check if answer exsists
                    $check_answer = DB::table('questions_answer')->select('answers')
                        ->where('answers', '=', $answers)
                        ->where('questions_category', '=', $questions_category)
                        ->get();
                    if (count($check_answer) == 0) {
                        // echo $answers .'<br>';
                        // return;
                        $insert_answer =  DB::table('questions_answer')->insert([
                            'answers' => $answers,
                            'vote_count' => $vote_count,
                            'questions_category' => $questions_category
                        ]);
                        // echo '<script>sonsole.log(' . $answers  . ')</script>';
                        // $insert_answer =  DB::table('questions_answer')->insert([
                        //     'answers' => 'Salò, or the 120 Days of Sodom',
                        //     'vote_count' => 0,
                        //     'questions_category' => 'Horror'
                        // ]);
                        // return;
                        // DB::connection()->getPdo()->exec("INSERT INTO questions_answer (`answers`, `vote_count`, `questions_category`) VALUES ('$answers', '$vote_count', '$questions_category')");
                        //    DB::statement('INSERT INTO questions_answer (answers, vote_count, questions_category) VALUES (:answers, :vote_count, :questions_category)', [
                        //     'answers' => $answers,
                        //     'vote_count' => $vote_count,
                        //     'questions_category' => $questions_category
                        // ]);
                        //DB::connection()->getPdo()->exec("INSERT INTO questions_answer ('answers','vote_count','questions_category') VALUES ('$answers','$vote_count','$questions_category' )");
                    }
                }
                $i++;
            }
            return redirect()->back()->with('message', "Success Answers imported Successfully!");
        } else {
            return redirect()->back()->with('error', "Error!");
        }
    }

    public function dashboard_questions(Request $request)
    {
        $query = DB::table('questions')->select('questions.id', 'questions.question', 'questions.question_category', 'topics.topic_name')->join('topics', 'questions.topic_id', 'topics.id')->get();
        return json_encode([
            'success' => 1,
            'data' => $query
        ]);
    }

    public function dashboard_question_details(Request $request)
    {
        $question_id = $request->question;
        //$query = DB::table('questions')->select('questions.id', 'topics.id as topic_id', 'questions.question_category', 'topics.topic_name', 'questions.question')->join('topics', 'topics.id', '=','questions.id')->where('questions.id', $question_id)->get();

        $query = DB::table('questions')->select('questions.id', 'topics.id as topic_id', 'questions.question_category', 'topics.topic_name', 'questions.question')->join('topics', 'questions.topic_id', 'topics.id')->where('questions.id', $question_id)->get();
        //$query = DB::table('questions')->select('*')->where('id', $question_id)->get();
        $topics = Topics::select('*')->get();
        return json_encode([
            'success' => 1,
            'data' => $query,
            'topics' => $topics
        ]);
    }

    public function update_dashboard_question(Request $request)
    {
        $question = $request->question;
        $topic_id = $request->topic_id;
        $question_id = $request->dashboard_question_id;

        $update_question = DB::table('questions')
            ->where('id', $question_id)
            ->update([
                'question' => $question,
                'topic_id' => $topic_id
            ]);
        if ($update_question) {
            return redirect()->back()->with('success', "Question updated successfully!");
        } else {
            return redirect()->back()->with('error', "Something went wrong!");
        }
    }
    public function delete_dashboard_question(Request $request)
    {
        $question_id = $request->delete_question_id;

        $delete_question =   DB::table('questions')->where('id', $question_id)->delete();
        if ($delete_question) {
            return redirect()->back()->with('success', "Question deleted successfully!");
        } else {
            return redirect()->back()->with('error', "Something went wrong!");
        }
    }

    public function question_answers($category)
    {
        $query = DB::table('questions_answer')->select('*')->where('questions_category', $category)->get();
        return view('dashboard_answers', compact('query'));
    }

    public function dashboard_answer_details(Request $request)
    {
        $query = DB::table('questions_answer')->select('*')->where('id', $request->answer)->get();
        return json_encode([
            'success' => 1,
            'data' => $query
        ]);
    }
    public function update_dashboard_answer(Request $request)
    {
        $answer = $request->answer;
        $answer_id = $request->dashboard_answer_id;

        $update_answer = DB::table('questions_answer')
            ->where('id', $answer_id)
            ->update([
                'answers' => $answer
            ]);
        if ($update_answer) {
            return redirect()->back()->with('success', "Answer updated successfully!");
        } else {
            return redirect()->back()->with('error', "Something went wrong!");
        }
    }
    public function delete_dashboard_answer(Request $request)
    {
        $answer_id = $request->delete_answer_id;

        $delete_question =   DB::table('questions_answer')->where('id', $answer_id)->delete();
        if ($delete_question) {
            return redirect()->back()->with('success', "Answer deleted successfully!");
        } else {
            return redirect()->back()->with('error', "Something went wrong!");
        }
    }

    public function export_users(Request $request)
    {
        $query = DB::table('users')->select('*')->get();
        $delimiter = ",";
        $filename = "users-data_" . date('Y-m-d') . ".csv";

        // Create a file pointer 
        $f = fopen('php://memory', 'w');

        // Set column headers 
        $fields = array('#', 'USER NAME', 'USER EMAIL');
        fputcsv($f, $fields, $delimiter);
        $j = 1;
        foreach ($query as $users) {
            $lineData = array($j, $users->name, $users->email);
            fputcsv($f, $lineData, $delimiter);
            $j++;
        }
        // Move back to beginning of file 
        fseek($f, 0);
        // Set headers to download file rather than displayed 
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '";');

        //output all remaining data on a file pointer 
        fpassthru($f);
        //return redirect()->back()->with('success', "Importing Users data!");
    }

    public function import_images(Request $request)
    {
        $request->validate([
            'zip_file' => 'required|mimes:zip',
        ]);
        $folderPath = public_path('images/question_images/ifave_images');

        $files = File::glob($folderPath . '/*'); // Get all files in the folder
    
        foreach ($files as $file) {
            if (is_file($file)) {
                File::delete($file); // Delete the file
            }
        }
        // Get the uploaded file
        $zipFile = $request->file('zip_file');
        // Extract the file to a temporary directory
        $extractedPath = $zipFile->store('temp');

        $destinationPath = public_path('images/question_images');
        // Unzip the folder
        $zip = new ZipArchive;
        $zip->open(storage_path('app/' . $extractedPath));
        $zip->extractTo($destinationPath);
        $zip->close();

        // Delete the temporary zip file
        Storage::delete($extractedPath);
        return redirect()->back()->with('success', "Images imported successfully!");
    }
}
