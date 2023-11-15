<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Charts\InfographicsChart as charts;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class InfographicsController extends Controller
{
    public function generate_image(Request $request, $topic_name, $question_name)
    {
        $question_name = str_replace('-', ' ', $question_name);
        $topic_name = str_replace('-', ' ', $topic_name);

        $DatasetLabel = $question_name;

        //get topic id
        $topic_id = DB::table('topics')->where('topic_name', $topic_name)->pluck('id');
        //get question id
        $question_id = DB::table('questions')->where('topic_id', $topic_id[0])->where('question', $question_name)->pluck('id');

        $data = DB::table('questions_answer')->select('questions_answer.answers', 'questions_answer.vote_count')
            ->join('questions', 'questions_answer.questions_category', 'questions.question_category')
            ->where('questions.id', $question_id[0])
            ->orderByDesc('questions_answer.vote_count')
            ->limit(10)
            ->get();

        $answers = [''];
        $frequecy = [0];
        foreach ($data as $index => $record) {
            array_push($answers, $record->answers);
            array_push($frequecy, $record->vote_count);
        }

        $chart = new Charts();
        $chart->labels($answers);
        $chart->dataset($DatasetLabel, 'horizontalBar', $frequecy)->backgroundColor('blue'); // Use 'horizontalBar' here
        $chart->options([
            'legend' => [
                'labels' => [
                    'fontSize' => 20,
                ],
            ]
        ]);


        return view('histogram', compact('chart', 'question_name', 'topic_name'));
    }

    public function saveChartImage(Request $request)
    {
        // Get the base64-encoded image data from the request
        $imageData = $request->input('imageData');

        // Decode the base64 data (remove the data URI prefix if present)
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageData));
        $topic_name = str_replace(' ', '-', $request->input('topic_name'));
        $question_name = str_replace(' ', '-', $request->input('question_name'));

        File::delete(public_path('images/infographics') . '/' . 'ifave-' . $topic_name . '-' . $question_name . '-infographics.png');
        // Save the image data to a file
        $filePath = public_path('images/infographics/ifave-' . $topic_name . '-' . $question_name . '-infographics.png');
        $success = file_put_contents($filePath, $imageData);

        if ($success !== false) {
            $imageName = 'ifave-' . $topic_name . '-' . $question_name . '-infographics.png';
            $publicPath = public_path('images/infographics');

            // Full path to the image in the public folder
            $imagePath = $publicPath . '/' . $imageName;

            // Check if the image exists
            if (File::exists($imagePath)) {
                // Download the image
                $file = File::get($imagePath);

                // Define the response headers
                $headers = [
                    'Content-Type' => File::mimeType($imagePath),
                    'Content-Disposition' => 'attachment; filename="' . $imageName . '"',
                ];

                // Delete the image from the public folder
                // File::delete($imagePath);

                // Redirect to a new route with parameters after the image is downloaded
                $redirectRoute = route('questions_details', ['location' => $topic_name, 'category' => $question_name]);

                // Return the image as a downloadable response
                $response = response($file, 200, $headers);

                // Add a JavaScript snippet to the response to redirect after the download
                $response->setContent('<script>window.location.href = "' . $redirectRoute . '";</script>');

                return $response;
            }
        }

        // If the image saving or processing fails
        return response()->json(['success' => false, 'error' => 'Failed to save image']);
    }
}
