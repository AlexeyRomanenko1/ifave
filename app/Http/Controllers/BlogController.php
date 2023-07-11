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
        $insert_blog =  DB::table('posts')->insert([
            'user_id' => $user_id,
            'title' => $blog_title,
            'tags' => $tags,
            'blog_content' => $blog_content,
            'featured_image' => $uniqueName
        ]);
        if ($insert_blog) {
            return redirect()->back()->with('success', "Blog created successfully");
        } else {
            return redirect()->back()->with('error', "Something went wrong");
        }
    }
}
