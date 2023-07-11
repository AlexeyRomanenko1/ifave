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
    public function index(Request $request){
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
    public function create_blog(Request $request){
        $tags=$request->tags;

        return redirect()->back()->with('success', "$tags");
    }
}
