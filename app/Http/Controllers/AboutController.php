<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController extends Controller
{
    //
    public function about_us(Request $request)
    {
        $keywords='ifave, ifave About';
        $meta_description = 'Dive into a world of rankings, user-driven insights, blogs and articles on trending topics. Understand what the world likes and dislikes with our Top 10 lists on a huge variety of topics. Join our community to discover, compare, and share the best of everything.';
        $page_title='iFave - About Us';
        return view('about',compact('keywords','meta_description','page_title'));
    }
}
