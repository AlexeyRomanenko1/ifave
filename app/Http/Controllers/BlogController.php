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

class BlogController extends Controller
{
    //
    public function index(Request $request)
    {
        if (Auth::check()) {
            // User is logged in
            $clientIP = Auth::id();
            if (!Auth::user()->hasVerifiedEmail()) {
                // User is not verified, redirect to a new route
                return redirect()->route('verification.notice');
            }
        } else {
            // User is not logged in
            return redirect()->route('/');
        }
        return view('blogs.create-blog');
    }
    public function create_blog(Request $request)
    {
        if (!Auth::user()->hasVerifiedEmail()) {
            // User is not verified, redirect to a new route
            return redirect()->route('verification.notice');
        }
        $validatedData = $request->validate([
            'blog_title' => 'required',
            'tags' => 'required',
            'blog_content' => 'required',
            'featured_image'=>'required'
        ]);
        $tags = $request->tags;
        $blog_title = $request->blog_title;
        $blog_content = $request->blog_content;
        $user_id = Auth::id();
        if ($request->hasFile('featured_image') && $request->file('featured_image')->isValid()) {
            $file = $request->file('featured_image');
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'PNG'];
            $extension = $file->getClientOriginalExtension();
            if (!in_array($extension, $allowedExtensions)) {
                return redirect()->back()->with('error', "Only JPG,JPEG,PNG entensions are allowed");
            }
            $uniqueName = date('m_d_Y_his') . $blog_title . '.' . $extension;
            // $file->storeAs(public_path('images/posts/'), $uniqueName);
            $path =  $file->move(public_path('images/posts/'), $uniqueName);
        } else {
            $uniqueName = '';
        }
        if (isset($request->topic_id) && isset($request->question_id)) {
            $insert_blog =  DB::table('posts')->insert([
                'user_id' => $user_id,
                'title' => $blog_title,
                'tags' => $tags,
                'topic_id' => $request->topic_id,
                'question_id' => $request->question_id,
                'blog_content' => $blog_content,
                'featured_image' => $uniqueName
            ]);
        } else {
            $insert_blog =  DB::table('posts')->insert([
                'user_id' => $user_id,
                'title' => $blog_title,
                'tags' => $tags,
                'blog_content' => $blog_content,
                'featured_image' => $uniqueName
            ]);
        }

        if ($insert_blog) {
            return redirect()->back()->with('success', "Blog created successfully");
        } else {
            return redirect()->back()->with('error', "Something went wrong");
        }
    }
    public function create_blog_topic_question(Request $request, $topic, $question)
    {
        $topic = str_replace("-", " ", $topic);
        $question = str_replace("-", " ", $question);
        $topic_id = DB::table('topics')
            ->where('topic_name', $topic)
            ->pluck('id');
        $question_id = DB::table('questions')
            ->where('question', $question)
            ->pluck('id');
        return view('blogs.create-blog', compact('topic', 'question', 'topic_id', 'question_id'));
    }
    public function show_blogs(Request $request){
        //query to get posts data 
        $perPage = 20; // Number of items per page
        $page = request()->get('page', 1); // Get the current page from the request
        $posts = DB::table('posts')
            ->select('posts.title', 'posts.blog_content', 'posts.featured_image', 'users.name', 'posts.created_at')
            ->join('users', 'posts.user_id', 'users.id')
            ->orderByDesc('posts.vote_count')
            ->paginate($perPage, ['*'], 'page', $page);
            return view('posts.blog', compact('posts'));
    }
}
