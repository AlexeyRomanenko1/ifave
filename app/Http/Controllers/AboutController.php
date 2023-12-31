<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AboutController extends Controller
{
    //
    public function about_us(Request $request)
    {
        $popular_questions = DB::table('questions')
            ->join('topics', 'questions.topic_id', '=', 'topics.id')
            ->where('topics.topic_name', '=', 'The World')
            ->select('questions.question', 'topics.topic_name')
            ->inRandomOrder()
            ->limit(5)
            ->get();
        $keywords = 'ifave, ifave About';
        $meta_description = 'Dive into a world of rankings, user-driven insights, blogs and articles on trending topics. Understand what the world likes and dislikes with our Top 10 lists on a huge variety of topics. Join our community to discover, compare, and share the best of everything.';
        $page_title = 'Discover Our Story at ifave.com - Passionate Pursuit of Excellence';
        return view('about', compact('keywords', 'meta_description', 'page_title', 'popular_questions'));
    }
}
