<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
class ContactController extends Controller
{
    //
    public function index(Request $request)
    {
        $keywords='ifave, ifave contact';
        $meta_description = 'Dive into a world of rankings, user-driven insights, blogs and articles on trending topics. Understand what the world likes and dislikes with our Top 10 lists on a huge variety of topics. Join our community to discover, compare, and share the best of everything.';
        $page_title='iFave - Contact';
        $popular_questions = DB::table('questions')
        ->join('questions_answer', 'questions.question_category', '=', 'questions_answer.questions_category')
        ->join('topics', 'questions.topic_id', '=', 'topics.id')
        ->select('questions.question', 'topics.topic_name')
        ->inRandomOrder()  // This line randomizes the order of the records
        ->limit(5)
        ->get();
        return view('contact',compact('keywords','meta_description','page_title','popular_questions'));
    }
    public function contact_us(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required',
            'subject' => 'required'
        ]);
       //Mail::to('contact@ifave.com')->send(new ContactMail($validatedData['name'], $validatedData['email'], $validatedData['message'], $validatedData['subject']));
        Mail::to('contact@ifave.com')->send(new ContactMail($validatedData['name'], $validatedData['email'], $validatedData['message'], $validatedData['subject']));
        // Redirect back with a success message
        return redirect()->back()->with('success', 'Your message has been sent successfully!');
    }
}
