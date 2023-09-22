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
use GrahamCampbell\ResultType\Success;
use Illuminate\Support\Facades\File;
use PDO;
use ZipArchive;
use Mews\Purifier\Facades\Purifier;
use Illuminate\Support\Str;

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
        $topics = DB::table('topics')->select('*')->get();
        $keywords = 'ifave, ifave blogging, blogging';
        $meta_description = 'Dive into a world of rankings, user-driven insights, blogs and articles on trending topics. Understand what the world likes and dislikes with our Top 10 lists on a huge variety of topics. Join our community to discover, compare, and share the best of everything.';
        $page_title = 'iFave - Blogs';
        return view('blogs.create-blog', compact('topics', 'keywords', 'meta_description', 'page_title'));
    }
    public function create_blog(Request $request)
    {
        if (!Auth::user()->hasVerifiedEmail()) {
            // User is not verified, redirect to a new route
            // return redirect()->route('verification.notice');
            return json_encode([
                'success' => 3,
                'data' => 'Unverified User'
            ]);
        }
        $validatedData = $request->validate([
            'blog_title' => 'required',
            'tags' => 'required',
            'blog_content' => 'required',
            'featured_image' => 'required'
        ]);
        $tags = $request->tags;
        $blog_title = $request->blog_title;
        $blog_content = $request->blog_content;
        $user_id = Auth::id();
        $slug = str_replace(" ", "-", $request->blog_title) . "-" . $user_id . "-" . date('m-d-Y-his');
        $slug = str_replace('?', '-', $slug);
        if ($request->hasFile('featured_image') && $request->file('featured_image')->isValid()) {
            $file = $request->file('featured_image');
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'PNG'];
            $extension = $file->getClientOriginalExtension();
            if (!in_array($extension, $allowedExtensions)) {
                return json_encode(['success' => 0, 'data' => "Only JPG,JPEG,PNG entensions are allowed"]);
            }
            $uniqueName = date('m_d_Y_his') . $file->getClientOriginalName();
            // $file->storeAs(public_path('images/posts/'), $uniqueName);
            $path =  $file->move(public_path('images/posts/'), $uniqueName);
        } else {
            $uniqueName = '';
        }
        // if (isset($request->topic_id) && isset($request->question_id)) {
        $insert_blog =  DB::table('posts')->insert([
            'user_id' => $user_id,
            'title' => $blog_title,
            'tags' => $tags,
            'topic_id' => $request->topic_id,
            'question_id' => $request->question_id,
            'blog_content' => $blog_content,
            'featured_image' => $uniqueName,
            'slug' => $slug
        ]);

        if ($insert_blog) {
            //return redirect()->back()->with('success', "Blog created successfully");
            return json_encode([
                'success' => 1,
                'data' => 'Blog created successfully'
            ]);
        } else {
            // return redirect()->back()->with('error', "Something went wrong");
            return json_encode([
                'success' => 0,
                'data' => 'Something went wrong'
            ]);
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
    public function show_blogs(Request $request)
    {
        //query to get posts data 
        $perPage = 20; // Number of items per page
        $page = request()->get('page', 1); // Get the current page from the request
        $posts = DB::table('posts')
            ->select('posts.title', 'posts.blog_content', 'posts.featured_image', 'users.name', 'posts.created_at', 'posts.slug', 'posts.user_id', 'posts.id')
            ->join('users', 'posts.user_id', 'users.id')
            ->where('posts.status', 1)
            ->orderByDesc('posts.vote_count')
            ->paginate($perPage, ['*'], 'page', $page);

        // $bloggers = [];
        // $bloggers_rating = DB::table('users as u')
        //     ->join('posts as p', 'u.id', '=', 'p.user_id')
        //     ->select('u.name as username', 'u.id as user_id', 'u.image', 'u.bio', 'u.location', DB::raw('SUM(p.vote_count) as rating'))
        //     ->groupBy('u.id', 'u.name', 'u.image', 'u.bio', 'u.location')
        //     ->orderByDesc('rating')
        //     ->get();
        // foreach ($bloggers_rating as $blogger) {
        //     $this_user_rating = $blogger->rating;
        //     $comments = DB::table('comments')
        //         ->select('users.name', DB::raw('SUM(comments.upvotes) as upvotes'))
        //         ->join('users', 'comments.comment_by', '=', 'users.id')
        //         ->join('questions', 'comments.question_id', '=', 'questions.id')
        //         ->where('comments.comment_by', $blogger->user_id)
        //         ->orderByDesc('upvotes')
        //         ->groupBy('users.name')
        //         ->get();
        //     foreach ($comments as $comment_rating) {
        //         $total_rating = $comment_rating->upvotes + $this_user_rating;
        //     }
        // }
        $bloggers = [];
        $bloggers_rating = DB::table('users as u')
            ->join('posts as p', 'u.id', '=', 'p.user_id')
            ->select('u.name as username', 'u.id as user_id', 'u.image', 'u.bio', 'u.location', DB::raw('SUM(p.vote_count - p.down_votes) as post_rating'))
            ->groupBy('u.id', 'u.name', 'u.image', 'u.bio', 'u.location')
            ->orderByDesc('post_rating')
            ->get();

        foreach ($bloggers_rating as $blogger) {
            $total_rating = $blogger->post_rating;

            $comments = DB::table('comments')
                ->select(DB::raw('SUM(upvotes - downvotes) as comment_rating'))
                ->where('comments.comment_by', $blogger->user_id)
                ->first();

            if ($comments) {
                $total_rating += $comments->comment_rating;
            }

            $bloggers[] = [
                'username' => $blogger->username,
                'user_id' => $blogger->user_id,
                'image' => $blogger->image,
                'bio' => $blogger->bio,
                'location' => $blogger->location,
                'rating' => $total_rating,
            ];
        }

        // Sort bloggers by rating in descending order
        usort($bloggers, function ($a, $b) {
            return $b['rating'] - $a['rating'];
        });
        // foreach($bloggers as $blogger){
        //     echo $blogger['username'].'<br>';
        // }
        // return;

        $keywords = 'ifave, ifave blogs, bloggers';
        $meta_description = 'Dive into a world of rankings, user-driven insights, blogs and articles on trending topics. Understand what the world likes and dislikes with our Top 10 lists on a huge variety of topics. Join our community to discover, compare, and share the best of everything.';
        $page_title = 'iFave - Blogs';
        $topics = DB::table('topics')->select('*')->get();
        return view('posts.blog', compact('posts', 'bloggers', 'topics', 'keywords', 'meta_description', 'page_title'));
    }
    public function blog_details(Request $request, $slug)
    {
        if (Auth::check()) {
            // User is logged in
            $clientIP = Auth::id();
        } else {
            // User is not logged in
            $clientIP = $this->getClientIP($request);
        }
        $check_if_viewed = DB::table('post_views')->select('*')->where('post_id', $slug)->where('viewed_by', $clientIP)->count();
        if ($check_if_viewed == 0) {
            $insert_view = DB::table('post_views')->insert([
                'viewed_by' => $clientIP,
                'post_id' => $slug
            ]);
            if ($insert_view) {
                $view_count = DB::table('posts')->select('*')->where('slug', $slug)->get();
                foreach ($view_count as $views) {
                    $post_views_count = $views->views_count;
                }
                $view_count = $post_views_count + 1;
                $update_votes = DB::table('posts')
                    ->where('slug', $slug)
                    ->update([
                        'views_count' => $view_count
                    ]);
            }
        }
        $posts = DB::table('posts')->select('posts.title', 'posts.tags', 'posts.blog_content', 'posts.featured_image', 'posts.vote_count', 'users.name', 'posts.created_at', 'posts.id', 'posts.down_votes', 'posts.views_count', 'posts.topic_id')
            ->join('users', 'users.id', 'posts.user_id')
            ->where('posts.slug', $slug)
            ->get();




        foreach ($posts as $post_location) {
            $this_post_location = $post_location->topic_id;
            // return $this_post_location;
        }
        // return $slug;
        $popular_questions = DB::table('questions')
            ->join('questions_answer', 'questions.question_category', '=', 'questions_answer.questions_category')
            ->join('topics', 'questions.topic_id', '=', 'topics.id')
            ->select('questions.question', 'topics.topic_name')
            ->where('questions.topic_id', '=', 1)
            ->orderBy('questions_answer.vote_count', 'DESC')
            ->distinct()
            ->offset(1)
            ->limit(5)
            ->get();

        $keywords = DB::table('posts')->where('slug', $slug)->pluck('tags');
        $keywords = $keywords[0];
        $meta_description = DB::table('posts')->where('slug', $slug)->pluck('blog_content');
        $meta_description = Purifier::clean($meta_description[0]);
        // Extract the plain text content
        $meta_description = strip_tags($meta_description);
        $meta_description =  Str::limit($meta_description, 160, '...');
        $latest_posts = DB::table('posts')->select('*')->where('status', 1)->orderByDesc('created_at')->limit(5)->get();
        $blog_title = DB::table('posts')->where('slug', $slug)->pluck('title');
        $page_title = 'iFave Blog - ' . $blog_title[0];
        return view('posts.post_details', compact('posts', 'latest_posts', 'keywords', 'meta_description', 'page_title', 'popular_questions'));
    }
    public function upvote_post(Request $request)
    {
        if (Auth::check()) {
            // User is logged in
            $clientIP = Auth::id();
        } else {
            // User is not logged in
            $clientIP = $this->getClientIP($request);
        }
        $check_if_voted = DB::table('posts_votes_history')->select('*')->where('post_id', $request->post_id)->where('vote_by', $clientIP)->count();
        if ($check_if_voted == 0) {
            $insert_upvote = DB::table('posts_votes_history')->insert([
                'vote_by' => $clientIP,
                'vote_type' => 'Upvote',
                'post_id' => $request->post_id
            ]);
            if ($insert_upvote) {
                $vote_count = $request->upvote + 1;
                $update_votes = DB::table('posts')
                    ->where('id', $request->post_id)
                    ->update([
                        'vote_count' => $vote_count
                    ]);
                if ($update_votes) {
                    return json_encode([
                        'success' => 1,
                        'data' => 'Post upvoted successfully'
                    ]);
                } else {
                    return json_encode([
                        'success' => 0,
                        'data' => 'Something went wrong'
                    ]);
                }
            } else {
                return json_encode([
                    'success' => 0,
                    'data' => 'Something went wrong'
                ]);
            }
        } else {
            return json_encode([
                'success' => 0,
                'data' => 'You have already voted for this post'
            ]);
        }
    }
    public function downvote_post(Request $request)
    {
        if (Auth::check()) {
            // User is logged in
            $clientIP = Auth::id();
        } else {
            // User is not logged in
            $clientIP = $this->getClientIP($request);
        }
        $check_if_voted = DB::table('posts_votes_history')->select('*')->where('post_id', $request->post_id)->where('vote_by', $clientIP)->count();
        if ($check_if_voted == 0) {
            $insert_upvote = DB::table('posts_votes_history')->insert([
                'vote_by' => $clientIP,
                'vote_type' => 'Downvote',
                'post_id' => $request->post_id
            ]);
            if ($insert_upvote) {
                $vote_count = $request->down_vote + 1;
                $update_votes = DB::table('posts')
                    ->where('id', $request->post_id)
                    ->update([
                        'down_votes' => $vote_count
                    ]);
                if ($update_votes) {
                    return json_encode([
                        'success' => 1,
                        'data' => 'Post down voted successfully'
                    ]);
                } else {
                    return json_encode([
                        'success' => 0,
                        'data' => 'Something went wrong'
                    ]);
                }
            } else {
                return json_encode([
                    'success' => 0,
                    'data' => 'Something went wrong'
                ]);
            }
        } else {
            return json_encode([
                'success' => 0,
                'data' => 'You have already voted for this post'
            ]);
        }
    }
    public function get_categories_onchange(Request $request)
    {
        $topic_id = $request->topic_id;
        $query = DB::table('questions')->select('*')->where('topic_id', $topic_id)->orderBy('question')->get();
        return json_encode([
            'success' => 1,
            'data' => $query
        ]);
    }
    public function upload_content_image(Request $request)
    {
        // $file = $request->file('content_images');
        // $allowedExtensions = ['jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'PNG'];
        // $extension = $file->getClientOriginalExtension();
        // if (!in_array($extension, $allowedExtensions)) {
        //     return json_encode([
        //         'success' => 0,
        //         'data' => 'Only JPG,JPEG,PNG entensions are allowed'
        //     ]);
        // }
        // // $uniqueName = date('m_d_Y_his') . $file->getClientOriginalName() . '.' . $extension;
        // $uniqueName = date('m_d_Y_his') . $file->getClientOriginalName();
        // // $file->storeAs(public_path('images/posts/'), $uniqueName);
        // $path =  $file->move(public_path('images/posts/'), $uniqueName);
        // return json_encode([
        //     'success' => 1,
        //     'path' => '/images/posts/' . $uniqueName
        // ]);
        $file = $request->file('file');
        $uniqueName = date('m_d_Y_his') . $file->getClientOriginalName();
        $path =  $file->move(public_path('images/posts/'), $uniqueName);
        // Validate the uploaded file, for example, you can check the file extension and size.

        // Save the file to a designated location, for example, the public/uploads folder.
        // $path = $file->store('uploads', 'public');

        // Return the URL of the uploaded image back to Froala.
        return response()->json(['link' => asset('/images/posts/' . $uniqueName)]);
    }

    public function filter_blog(Request $request, $topic_slug, $question_slug)
    {
        $topic = str_replace('-', " ", $topic_slug);
        $question = str_replace('-', " ", $question_slug);
        $topic_id = DB::table('topics')
            ->where('topic_name', $topic)
            ->pluck('id');

        $topic_id = $topic_id[0];
        $categories = DB::table('questions')->select('*')->where('topic_id', $topic_id)->get();
        $perPage = 20; // Number of items per page
        $page = request()->get('page', 1); // Get the current page from the request
        if ($question != 'All Categories') {
            $question_id = DB::table('questions')
                ->where('question', $question)
                ->where('topic_id', $topic_id)
                ->pluck('id');
            $question_id = $question_id[0];
            $posts = DB::table('posts')
                ->select('posts.title', 'posts.blog_content', 'posts.featured_image', 'users.name', 'posts.created_at', 'posts.slug', 'posts.user_id', 'posts.id')
                ->join('users', 'posts.user_id', 'users.id')
                ->where('posts.topic_id', $topic_id)
                ->where('posts.question_id', $question_id)
                ->where('posts.status', 1)
                ->orderByDesc('posts.vote_count')
                ->paginate($perPage, ['*'], 'page', $page);
        } else {
            $posts = DB::table('posts')
                ->select('posts.title', 'posts.blog_content', 'posts.featured_image', 'users.name', 'posts.created_at', 'posts.slug', 'posts.user_id', 'posts.id')
                ->join('users', 'posts.user_id', 'users.id')
                ->where('posts.topic_id', $topic_id)
                ->where('posts.status', 1)
                ->orderByDesc('posts.vote_count')
                ->paginate($perPage, ['*'], 'page', $page);
        }
        // $bloggers = DB::table('users as u')
        //     ->join('posts as p', 'u.id', '=', 'p.user_id')
        //     ->select('u.name as username', 'u.image', 'u.bio', 'u.location', DB::raw('SUM(p.vote_count) as rating'))
        //     ->groupBy('u.id', 'u.name', 'u.image', 'u.bio', 'u.location')
        //     ->orderByDesc('rating')
        //     ->get();
        $bloggers = [];
        $bloggers_rating = DB::table('users as u')
            ->join('posts as p', 'u.id', '=', 'p.user_id')
            ->select('u.name as username', 'u.id as user_id', 'u.image', 'u.bio', 'u.location', DB::raw('SUM(p.vote_count - p.down_votes) as post_rating'))
            ->groupBy('u.id', 'u.name', 'u.image', 'u.bio', 'u.location')
            ->orderByDesc('post_rating')
            ->get();

        foreach ($bloggers_rating as $blogger) {
            $total_rating = $blogger->post_rating;

            $comments = DB::table('comments')
                ->select(DB::raw('SUM(upvotes - downvotes) as comment_rating'))
                ->where('comments.comment_by', $blogger->user_id)
                ->first();

            if ($comments) {
                $total_rating += $comments->comment_rating;
            }

            $bloggers[] = [
                'username' => $blogger->username,
                'user_id' => $blogger->user_id,
                'image' => $blogger->image,
                'bio' => $blogger->bio,
                'location' => $blogger->location,
                'rating' => $total_rating,
            ];
        }

        // Sort bloggers by rating in descending order
        usort($bloggers, function ($a, $b) {
            return $b['rating'] - $a['rating'];
        });

        $keywords = 'ifave, ifave blogging, blogging';
        $meta_description = 'Dive into a world of rankings, user-driven insights, blogs and articles on trending topics. Understand what the world likes and dislikes with our Top 10 lists on a huge variety of topics. Join our community to discover, compare, and share the best of everything.';
        $page_title = 'iFave - Blogs - ' . $topic . ' - ' . $question;
        $topics = DB::table('topics')->select('*')->get();
        return view('posts.blog', compact('posts', 'bloggers', 'topics', 'topic_slug', 'question_slug', 'categories', 'page_title', 'keywords', 'meta_description'));
    }

    public function blogger_location_filter(Request $request, $user_name, $topic_slug, $question_slug)
    {
        $name = str_replace('-', " ", $user_name);
        $topic = str_replace('-', " ", $topic_slug);
        $question = str_replace('-', " ", $question_slug);
        $topic_id = DB::table('topics')
            ->where('topic_name', $topic)
            ->pluck('id');
        $caltegories = DB::table('questions')->select('*')->where('topic_id', $topic_id)->get();
        $user_id = DB::table('users')
            ->where('name', $name)
            ->pluck('id');
        $user_id = $user_id[0];
        $topic_id = $topic_id[0];
        $categories = DB::table('questions')->select('*')->where('topic_id', $topic_id)->get();
        $perPage = 20; // Number of items per page
        $page = request()->get('page', 1); // Get the current page from the request
        if ($question != 'All Categories') {
            $question_id = DB::table('questions')
                ->where('question', $question)
                ->where('topic_id', $topic_id)
                ->pluck('id');
            $question_id = $question_id[0];
            $posts = DB::table('posts')
                ->select('posts.title', 'posts.blog_content', 'posts.featured_image', 'users.name', 'posts.created_at', 'posts.slug', 'posts.user_id', 'posts.id')
                ->join('users', 'posts.user_id', 'users.id')
                ->where('posts.topic_id', $topic_id)
                ->where('posts.question_id', $question_id)
                ->where('posts.status', 1)
                ->where('users.id', $user_id)
                ->orderByDesc('posts.vote_count')
                ->paginate($perPage, ['*'], 'page', $page);
        } else {
            $posts = DB::table('posts')
                ->select('posts.title', 'posts.blog_content', 'posts.featured_image', 'users.name', 'posts.created_at', 'posts.slug', 'posts.user_id', 'posts.id')
                ->join('users', 'posts.user_id', 'users.id')
                ->where('posts.topic_id', $topic_id)
                ->where('posts.status', 1)
                ->where('users.id', $user_id)
                ->orderByDesc('posts.vote_count')
                ->paginate($perPage, ['*'], 'page', $page);
        }
        // $bloggers = DB::table('users as u')
        //     ->join('posts as p', 'u.id', '=', 'p.user_id')
        //     ->select('u.name as username', 'u.image', 'u.bio', 'u.location', DB::raw('SUM(p.vote_count) as rating'))
        //     ->groupBy('u.id', 'u.name', 'u.image', 'u.bio', 'u.location')
        //     ->orderByDesc('rating')
        //     ->get();

        $bloggers = [];
        $bloggers = [];
        $bloggers_rating = DB::table('users as u')
            ->join('posts as p', 'u.id', '=', 'p.user_id')
            ->select('u.name as username', 'u.id as user_id', 'u.image', 'u.bio', 'u.location', DB::raw('SUM(p.vote_count - p.down_votes) as post_rating'))
            ->groupBy('u.id', 'u.name', 'u.image', 'u.bio', 'u.location')
            ->orderByDesc('post_rating')
            ->get();

        foreach ($bloggers_rating as $blogger) {
            $total_rating = $blogger->post_rating;

            $comments = DB::table('comments')
                ->select(DB::raw('SUM(upvotes - downvotes) as comment_rating'))
                ->where('comments.comment_by', $blogger->user_id)
                ->first();

            if ($comments) {
                $total_rating += $comments->comment_rating;
            }

            $bloggers[] = [
                'username' => $blogger->username,
                'user_id' => $blogger->user_id,
                'image' => $blogger->image,
                'bio' => $blogger->bio,
                'location' => $blogger->location,
                'rating' => $total_rating,
            ];
        }

        // Sort bloggers by rating in descending order
        usort($bloggers, function ($a, $b) {
            return $b['rating'] - $a['rating'];
        });
        $keywords = 'ifave, ifave blogging, blogging';
        $meta_description = 'Dive into a world of rankings, user-driven insights, blogs and articles on trending topics. Understand what the world likes and dislikes with our Top 10 lists on a huge variety of topics. Join our community to discover, compare, and share the best of everything.';
        $page_title = 'iFave - Blogger ' . $name . ' - ' . $topic . ' - ' . $question;
        $topics = DB::table('topics')->select('*')->get();
        return view('posts.blog', compact('posts', 'bloggers', 'topics', 'topic_slug', 'question_slug', 'name', 'categories', 'keywords', 'meta_description', 'page_title'));
    }
    public function blogger_filter(Request $request, $user_name)
    {
        $name = str_replace('-', " ", $user_name);
        $user_id = DB::table('users')
            ->where('name', $name)
            ->pluck('id');
        $user_id = $user_id[0];
        //query to get posts data 
        $perPage = 20; // Number of items per page
        $page = request()->get('page', 1); // Get the current page from the request
        $posts = DB::table('posts')
            ->select('posts.title', 'posts.blog_content', 'posts.featured_image', 'users.name', 'posts.created_at', 'posts.slug', 'posts.user_id', 'posts.id')
            ->join('users', 'posts.user_id', 'users.id')
            ->where('users.id', $user_id)
            ->where('posts.status', 1)
            ->orderByDesc('posts.vote_count')
            ->paginate($perPage, ['*'], 'page', $page);

        // $bloggers = DB::table('users as u')
        //     ->join('posts as p', 'u.id', '=', 'p.user_id')
        //     ->select('u.name as username', 'u.image', 'u.bio', 'u.location', DB::raw('SUM(p.vote_count) as rating'))
        //     ->groupBy('u.id', 'u.name', 'u.image', 'u.bio', 'u.location')
        //     ->orderByDesc('rating')
        //     ->get();
        $bloggers = [];
        $bloggers_rating = DB::table('users as u')
            ->join('posts as p', 'u.id', '=', 'p.user_id')
            ->select('u.name as username', 'u.id as user_id', 'u.image', 'u.bio', 'u.location', DB::raw('SUM(p.vote_count - p.down_votes) as post_rating'))
            ->groupBy('u.id', 'u.name', 'u.image', 'u.bio', 'u.location')
            ->orderByDesc('post_rating')
            ->get();

        foreach ($bloggers_rating as $blogger) {
            $total_rating = $blogger->post_rating;

            $comments = DB::table('comments')
                ->select(DB::raw('SUM(upvotes - downvotes) as comment_rating'))
                ->where('comments.comment_by', $blogger->user_id)
                ->first();

            if ($comments) {
                $total_rating += $comments->comment_rating;
            }

            $bloggers[] = [
                'username' => $blogger->username,
                'user_id' => $blogger->user_id,
                'image' => $blogger->image,
                'bio' => $blogger->bio,
                'location' => $blogger->location,
                'rating' => $total_rating,
            ];
        }

        // Sort bloggers by rating in descending order
        usort($bloggers, function ($a, $b) {
            return $b['rating'] - $a['rating'];
        });
        $keywords = 'ifave, ifave blogging, blogging';
        $meta_description = 'Dive into a world of rankings, user-driven insights, blogs and articles on trending topics. Understand what the world likes and dislikes with our Top 10 lists on a huge variety of topics. Join our community to discover, compare, and share the best of everything.';
        $page_title = 'iFave - Blogger - ' . $name;
        $topics = DB::table('topics')->select('*')->get();
        return view('posts.blog', compact('posts', 'bloggers', 'topics', 'name', 'keywords', 'meta_description', 'page_title'));
    }
    public function searchBlogs(Request $request)
    {
        $perPage = 20; // Number of items per page
        $page = request()->get('page', 1); // Get the current page from the request
        if (isset($request->topic_slug) && isset($request->question_slug)) {
            $topic = str_replace('-', " ", $request->topic_slug);
            $question = str_replace('-', " ", $request->question_slug);
            $topic_id = DB::table('topics')
                ->where('topic_name', $topic)
                ->pluck('id');

            $topic_id = $topic_id[0];
            if ($question != 'All Categories') {
                $question_id = DB::table('questions')
                    ->where('question', $question)
                    ->where('topic_id', $topic_id[0])
                    ->pluck('id');
                $question_id = $question_id[0];
                $posts = DB::table('posts')
                    ->select('posts.title', 'posts.blog_content', 'posts.featured_image', 'users.name', 'posts.created_at', 'posts.slug', 'posts.user_id', 'posts.id')
                    ->join('users', 'posts.user_id', 'users.id')
                    ->where('posts.topic_id', $topic_id)
                    ->where('posts.question_id', $question_id)
                    ->where('posts.status', 1)
                    ->where('posts.title', 'like', '%' . $request->search . '%')
                    ->orderByDesc('posts.vote_count')
                    ->paginate($perPage, ['*'], 'page', $page);
            } else {
                $posts = DB::table('posts')
                    ->select('posts.title', 'posts.blog_content', 'posts.featured_image', 'users.name', 'posts.created_at', 'posts.slug', 'posts.user_id', 'posts.id')
                    ->join('users', 'posts.user_id', 'users.id')
                    ->where('posts.topic_id', $topic_id)
                    ->where('posts.status', 1)
                    ->where('posts.title', 'like', '%' . $request->search . '%')
                    ->orderByDesc('posts.vote_count')
                    ->paginate($perPage, ['*'], 'page', $page);
            }
        } else {
            $posts = DB::table('posts')
                ->select('posts.title', 'posts.blog_content', 'posts.featured_image', 'users.name', 'posts.created_at', 'posts.slug', 'posts.user_id', 'posts.id')
                ->join('users', 'posts.user_id', 'users.id')
                ->where('posts.status', 1)
                ->where('posts.title', 'like', '%' . $request->search . '%')
                ->orderByDesc('posts.vote_count')
                ->limit(1)
                ->paginate($perPage, ['*'], 'page', $page);
        }
        return view('posts.pagination', compact('posts'));
    }
    public function editBlog(Request $request, $username, $slug, $blog_id)
    {
        if (Auth::check()) {
            // User is logged in
            $clientIP = Auth::id();
            $UserName = Auth::user();
            // echo $UserName->name;
            // return ;
            if (!Auth::user()->hasVerifiedEmail()) {
                // User is not verified, redirect to a new route
                return redirect()->route('verification.notice');
            }
        } else {
            // User is not logged in
            return redirect()->route('/');
        }
        $post_details = DB::table('posts')->select('*')->where('id', $blog_id)->where('user_id', $clientIP)->get();
        $topics = DB::table('topics')->select('*')->get();
        $keywords = 'ifave, ifave blogging, blogging';
        $meta_description = 'Dive into a world of rankings, user-driven insights, blogs and articles on trending topics. Understand what the world likes and dislikes with our Top 10 lists on a huge variety of topics. Join our community to discover, compare, and share the best of everything.';
        $page_title = 'iFave - Edit Blogs';
        return view('blogs.edit-blog', compact('topics', 'keywords', 'meta_description', 'page_title', 'post_details', 'blog_id'));
    }
    public function edit_blog(Request $request)
    {
        if (!Auth::user()->hasVerifiedEmail()) {
            // User is not verified, redirect to a new route
            // return redirect()->route('verification.notice');
            return json_encode([
                'success' => 3,
                'data' => 'Unverified User'
            ]);
        }
        $tags = $request->tags;
        // $blog_title = $request->blog_title;
        $blog_content = $request->blog_content;
        $user_id = Auth::id();
        // $slug = str_replace(" ", "-", $request->blog_title) . "-" . $user_id . "-" . date('m-d-Y-his');

        if ($request->hasFile('featured_image') && $request->file('featured_image')->isValid()) {
            $file = $request->file('featured_image');
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'PNG'];
            $extension = $file->getClientOriginalExtension();
            if (!in_array($extension, $allowedExtensions)) {
                return json_encode(['success' => 0, 'data' => "Only JPG,JPEG,PNG entensions are allowed"]);
            }
            $uniqueName = date('m_d_Y_his') . $file->getClientOriginalName();
            // $file->storeAs(public_path('images/posts/'), $uniqueName);
            $path =  $file->move(public_path('images/posts/'), $uniqueName);
            $insert_blog = DB::table('posts')
                ->where('id', $request->blog_id)
                ->update([
                    'tags' => $tags,
                    'topic_id' => $request->topic_id,
                    'question_id' => $request->question_id,
                    'blog_content' => $blog_content,
                    'featured_image' => $uniqueName,
                    //'slug' => $slug
                ]);
        } else {
            // $uniqueName = '';
            $insert_blog = DB::table('posts')
                ->where('id', $request->blog_id)
                ->update([
                    'tags' => $tags,
                    'topic_id' => $request->topic_id,
                    'question_id' => $request->question_id,
                    'blog_content' => $blog_content,
                    //'slug' => $slug
                ]);
        }
        // if (isset($request->topic_id) && isset($request->question_id)) {
        // $insert_blog =  DB::table('posts')->insert([
        //     'user_id' => $user_id,
        //     'title' => $blog_title,
        //     'tags' => $tags,
        //     'topic_id' => $request->topic_id,
        //     'question_id' => $request->question_id,
        //     'blog_content' => $blog_content,
        //     'featured_image' => $uniqueName,
        //     'slug' => $slug
        // ]);

        if ($insert_blog) {
            //return redirect()->back()->with('success', "Blog created successfully");
            return json_encode([
                'success' => 1,
                'data' => 'Blog updated successfully'
            ]);
        } else {
            // return redirect()->back()->with('error', "Something went wrong");
            return json_encode([
                'success' => 0,
                'data' => 'Something went wrong'
            ]);
        }
    }
    public function getClientIP(Request $request)
    {
        $ip = $request->getClientIp();
        return $ip;
    }
}
