<?php

namespace App\Utilities;

use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Url;
use Illuminate\Support\Facades\DB;

class SitemapGenerator
{
    public function generate()
    {
        // $sitemap = Sitemap::create();

        // // Add existing URLs to the sitemap
        // $sitemap->add(Url::create('/')
        //     ->setPriority(1.0)
        //     ->setChangeFrequency('monthly'));
        // $sitemap->add(Url::create('/contact-us')
        //     ->setPriority(0.9)
        //     ->setChangeFrequency('monthly'));

        // $sitemap->add(Url::create('/blog')
        //     ->setPriority(0.9)
        //     ->setChangeFrequency('monthly'));
        // $sitemap->add(Url::create('/create-blog')
        //     ->setPriority(0.9)
        //     ->setChangeFrequency('monthly'));
        // $sitemap->add(Url::create('/update-profile')
        //     ->setPriority(0.9)
        //     ->setChangeFrequency('monthly'));
        // $sitemap->add(Url::create('/update-profile')
        //     ->setPriority(0.9)
        //     ->setChangeFrequency('monthly'));
        // // Fetch and add new blog URLs to the sitemap
        // $newBlogUrls = $this->getNewBlogUrls(); // Implement this method
        // foreach ($newBlogUrls as $url) {
        //     $sitemap->add(Url::create("/blog/{$url->slug}")
        //         ->setPriority(0.8) // Adjust priority as needed
        //         ->setChangeFrequency('monthly')); // Adjust change frequency as needed
        // }
        // // blogger
        // $newBloggersUrls = $this->bloggerUrls(); // Implement this method
        // foreach ($newBloggersUrls as $url) {
        //     $url->name = str_replace(' ', '-', $url->name);
        //     $sitemap->add(Url::create("/blogger/{$url->name}")
        //         ->setPriority(0.8) // Adjust priority as needed
        //         ->setChangeFrequency('monthly')); // Adjust change frequency as needed
        // }
        // //topics 
        // $topicssUrls = $this->topicsUrls(); // Implement this method
        // foreach ($topicssUrls as $url) {
        //     $url->topic_name = str_replace(' ', '-', $url->topic_name);
        //     $sitemap->add(Url::create("/location/{$url->topic_name}")
        //         ->setPriority(0.8) // Adjust priority as needed
        //         ->setChangeFrequency('monthly')); // Adjust change frequency as needed
        // }
        // //questions 
        // $questionsUrls = $this->questionUrls(); // Implement this method
        // foreach ($questionsUrls as $url) {
        //     $url->topic_name = str_replace(' ', '-', $url->topic_name);
        //     $url->question = str_replace(' ', '-', $url->question);
        //     $sitemap->add(Url::create("/category/{$url->topic_name}/{$url->question}")
        //         ->setPriority(0.8) // Adjust priority as needed
        //         ->setChangeFrequency('monthly')); // Adjust change frequency as needed
        // }


        // // Write the sitemap to a file
        // $sitemap->writeToFile(public_path('sitemap.xml'));

        // Generate smaller sitemap files
        // $this->generateBlogsSitemap();
        // $this->generateBloggersSitemap();
        // $this->generateTopicsSitemap();
        $this->generateQuestionsSitemap();

        // Generate sitemap index file
        // $this->generateSitemapIndex();
    }


    protected function generateBlogsSitemap()
    {

        $sitemap = Sitemap::create();

        // Generate URLs for blogs and add them to the sitemap
        // ...
        // Fetch and add new blog URLs to the sitemap
        $newBlogUrls = $this->getNewBlogUrls(); // Implement this method
        foreach ($newBlogUrls as $url) {
            $sitemap->add(Url::create("/blog/{$url->slug}")
                ->setPriority(0.8) // Adjust priority as needed
                ->setChangeFrequency('monthly')); // Adjust change frequency as needed
        }
        // Write the sitemap to a file
        $sitemap->writeToFile(public_path('sitemap-blogs.xml'));
    }
    protected function generateBloggersSitemap()
    {
        $sitemap = Sitemap::create();

        // Generate URLs for bloggers and add them to the sitemap
        // ...
        // // blogger
        $newBloggersUrls = $this->bloggerUrls(); // Implement this method
        foreach ($newBloggersUrls as $url) {
            $url->name = str_replace(' ', '-', $url->name);
            $sitemap->add(Url::create("/blogger/{$url->name}")
                ->setPriority(0.8) // Adjust priority as needed
                ->setChangeFrequency('monthly')); // Adjust change frequency as needed
        }
        // Write the sitemap to a file
        $sitemap->writeToFile(public_path('sitemap-bloggers.xml'));
    }

    protected function generateTopicsSitemap()
    {
        $sitemap = Sitemap::create();

        // Generate URLs for topics and add them to the sitemap
        // ...
        //topics 
        $topicssUrls = $this->topicsUrls(); // Implement this method
        foreach ($topicssUrls as $url) {
            $url->topic_name = str_replace(' ', '-', $url->topic_name);
            $sitemap->add(Url::create("/location/{$url->topic_name}")
                ->setPriority(0.8) // Adjust priority as needed
                ->setChangeFrequency('monthly')); // Adjust change frequency as needed
        }
        // Write the sitemap to a file
        $sitemap->writeToFile(public_path('sitemap-topics.xml'));
    }

    protected function generateQuestionsSitemap()
    {
        $chunkSize = 50000; // Set an appropriate chunk size

        // Count the total number of questions
        $totalQuestions = DB::table('questions')->count();

        // Calculate the number of iterations needed
        $iterations = ceil($totalQuestions / $chunkSize);

        // Initialize a variable to track the current offset
        $offset = 450009;
        
        $sitemap = Sitemap::create();

        for ($i = 10; $i <= $iterations; $i++) {
            $questions = $this->questionUrls($chunkSize, $offset);

            foreach ($questions as $url) {
                $topicSlug = str_replace(' ', '-', $url->topic_name);
                $questionSlug = str_replace(' ', '-', $url->question);

                $sitemap->add(Url::create("/category/{$topicSlug}/{$questionSlug}")
                    ->setPriority(0.8)
                    ->setChangeFrequency('monthly'));
            }

            // Write the sitemap to a file for each iteration
            $sitemap->writeToFile(public_path("sitemap-questions-{$i}.xml"));

            // Move the offset for the next iteration
            $offset += $chunkSize;
        }
    }


    protected function generateSitemapIndex()
    {
        $sitemapIndex = SitemapIndex::create();

        // Create and add instances of individual sitemaps
        $sitemapBlogs = Sitemap::create();
        $sitemapBloggers = Sitemap::create();
        $sitemapTopics = Sitemap::create();
        $sitemapQuestions = Sitemap::create();

        // Add URLs to individual sitemaps
        $sitemapBlogs->add('/blogs');
        $sitemapBloggers->add('/bloggers');
        $sitemapTopics->add('/topics');
        $sitemapQuestions->add('/questions');

        // Write individual sitemaps to files
        $sitemapBlogs->writeToFile(public_path('sitemap-blogs.xml'));
        $sitemapBloggers->writeToFile(public_path('sitemap-bloggers.xml'));
        $sitemapTopics->writeToFile(public_path('sitemap-topics.xml'));
        $sitemapQuestions->writeToFile(public_path('sitemap-questions.xml'));

        // Add references to individual sitemaps to the sitemap index
        $sitemapIndex->add('/sitemap-blogs.xml');
        $sitemapIndex->add('/sitemap-bloggers.xml');
        $sitemapIndex->add('/sitemap-topics.xml');
        $sitemapIndex->add('/sitemap-questions.xml');

        // Write the sitemap index to a file
        $sitemapIndex->writeToFile(public_path('sitemap-index.xml'));
    }

    protected function getNewBlogUrls()
    {
        // Fetch and return new blog URLs from your database
        // Implement the logic to query your database for new blog posts
        $blogs = DB::table('posts')->select('slug')->where('status', 1)->get();
        return $blogs;
    }

    protected function questionUrls($chunkSize, $offset)
    {
        $questions = DB::table('questions')
            ->select('topics.topic_name', 'questions.question')
            ->join('topics', 'questions.topic_id', '=', 'topics.id')
            ->where('questions.id', '>=', $offset + 1) // Adjust the condition for starting ID
            ->where('questions.id', '<=', $offset + $chunkSize) // Adjust the condition for ending ID
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
