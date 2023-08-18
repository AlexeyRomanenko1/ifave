<?php

namespace App\Utilities;

use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Illuminate\Support\Facades\DB;

class SitemapGenerator
{
    public function generate()
    {
        $sitemap = Sitemap::create();

        // Add existing URLs to the sitemap
        $sitemap->add(Url::create('/')
            ->setPriority(1.0)
            ->setChangeFrequency('monthly'));
        $sitemap->add(Url::create('/contact-us')
            ->setPriority(0.9)
            ->setChangeFrequency('monthly'));

        $sitemap->add(Url::create('/blog')
            ->setPriority(0.9)
            ->setChangeFrequency('monthly'));
        $sitemap->add(Url::create('/create-blog')
            ->setPriority(0.9)
            ->setChangeFrequency('monthly'));
        $sitemap->add(Url::create('/update-profile')
            ->setPriority(0.9)
            ->setChangeFrequency('monthly'));
        $sitemap->add(Url::create('/update-profile')
            ->setPriority(0.9)
            ->setChangeFrequency('monthly'));
        // Fetch and add new blog URLs to the sitemap
        $newBlogUrls = $this->getNewBlogUrls(); // Implement this method
        foreach ($newBlogUrls as $url) {
            $sitemap->add(Url::create("/blog/{$url->slug}")
                ->setPriority(0.8) // Adjust priority as needed
                ->setChangeFrequency('monthly')); // Adjust change frequency as needed
        }
        // blogger
        $newBloggersUrls = $this->bloggerUrls(); // Implement this method
        foreach ($newBloggersUrls as $url) {
            $url->name = str_replace(' ', '-', $url->name);
            $sitemap->add(Url::create("/blogger/{$url->name}")
                ->setPriority(0.8) // Adjust priority as needed
                ->setChangeFrequency('monthly')); // Adjust change frequency as needed
        }
        //topics 
        $topicssUrls = $this->topicsUrls(); // Implement this method
        foreach ($topicssUrls as $url) {
            $url->topic_name = str_replace(' ', '-', $url->topic_name);
            $sitemap->add(Url::create("/location/{$url->topic_name}")
                ->setPriority(0.8) // Adjust priority as needed
                ->setChangeFrequency('monthly')); // Adjust change frequency as needed
        }
        //questions 
        $questionsUrls = $this->questionUrls(); // Implement this method
        foreach ($questionsUrls as $url) {
            $url->topic_name = str_replace(' ', '-', $url->topic_name);
            $url->question = str_replace(' ', '-', $url->question);
            $sitemap->add(Url::create("/category/{$url->topic_name}/{$url->question}")
                ->setPriority(0.8) // Adjust priority as needed
                ->setChangeFrequency('monthly')); // Adjust change frequency as needed
        }


        // Write the sitemap to a file
        $sitemap->writeToFile(public_path('sitemap.xml'));
    }

    protected function getNewBlogUrls()
    {
        // Fetch and return new blog URLs from your database
        // Implement the logic to query your database for new blog posts
        $blogs = DB::table('posts')->select('slug')->where('status', 1)->get();
        return $blogs;
    }

    protected function questionUrls()
    {
        $questions = DB::table('questions')
            ->select('topics.topic_name', 'questions.question')
            ->join('topics', 'questions.topic_id', '=', 'topics.id')
            ->get();
        return $questions;
    }
    protected function topicsUrls()
    {
        $topics = DB::table('topics')->select('topic_name')->get();
        return $topics;
    }
    protected function bloggerUrls()
    {
        $bloggers = DB::table('posts')
            ->select(DB::raw('DISTINCT user_id'), 'users.name')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->where('posts.status', '=', 1)
            ->get();
        return $bloggers;
    }
}
