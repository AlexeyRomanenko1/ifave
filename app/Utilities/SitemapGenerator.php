<?php

namespace App\Utilities;

use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Url;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class SitemapGenerator
{
    public function generate()
    {
        $this_time = time();
        // $this->generateBlogsSitemap($this_time);
        // $this->generateBloggersSitemap($this_time);
        // $this->generateTopicsSitemap($this_time);
        $this->generateQuestionsSitemap($this_time);

        // Generate sitemap index file
       // $this->generateSitemapIndex($this_time);
    }


    protected function generateBlogsSitemap($this_time)
    {

        $sitemap = Sitemap::create();

        // Generate URLs for blogs and add them to the sitemap
        // ...
        // Fetch and add new blog URLs to the sitemap
        $newBlogUrls = $this->getNewBlogUrls(); // Implement this method
        if (count($newBlogUrls) > 0) {
            foreach ($newBlogUrls as $url) {
                $sitemap->add(Url::create("/blog/{$url->slug}")
                    ->setPriority(0.8) // Adjust priority as needed
                    ->setChangeFrequency('monthly')); // Adjust change frequency as needed
            }
            // Write the sitemap to a file
            $sitemap->writeToFile(public_path('sitemap-blogs' . $this_time . '.xml'));
        }
    }
    protected function generateBloggersSitemap($this_time)
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
        $sitemap->writeToFile(public_path('sitemap-bloggers' . $this_time . '.xml'));
    }

    protected function generateTopicsSitemap($this_time)
    {
        $sitemap = Sitemap::create();

        // Generate URLs for topics and add them to the sitemap
        // ...
        //topics 
        $topicssUrls = $this->topicsUrls(); // Implement this method
        if (count($topicssUrls) > 0) {
            foreach ($topicssUrls as $url) {
                $url->topic_name = str_replace(' ', '-', $url->topic_name);
                $sitemap->add(Url::create("/location/{$url->topic_name}")
                    ->setPriority(0.8) // Adjust priority as needed
                    ->setChangeFrequency('monthly')); // Adjust change frequency as needed
            }
            // Write the sitemap to a file
            $sitemap->writeToFile(public_path('sitemap-topics' . $this_time . '.xml'));
        }
    }

    protected function generateQuestionsSitemap($this_time)
    {
        // $chunkSize = 10000; // Set an appropriate chunk size

        // // Count the total number of questions
        // $totalQuestions = DB::table('questions')->count();

        // // Calculate the number of iterations needed
        // $iterations = ceil($totalQuestions / $chunkSize);

        // // Initialize a variable to track the current offset
        // $offset = 0;

        $sitemap = Sitemap::create();

        //for ($i = 1; $i <= $iterations; $i++) {
            $questions = $this->questionUrls();

            foreach ($questions as $url) {
                $topicSlug = str_replace(' ', '-', $url->topic_name);
                $questionSlug = str_replace(' ', '-', $url->question);
                $topicSlug=urlencode($topicSlug);
                $sitemap->add(Url::create("/category/{$topicSlug}/{$questionSlug}")
                    ->setPriority(0.8)
                    ->setChangeFrequency('monthly'));
            }

            // Write the sitemap to a file for each iteration
            $sitemap->writeToFile(public_path("sitemap-questions-122.xml"));

            // Move the offset for the next iteration
         //   $offset += $chunkSize;
       // }
    }


    protected function generateSitemapIndex($this_time)
    {
        $sitemapIndexPath = public_path('sitemap-index.xml');
        $existingSitemapIndex = simplexml_load_file($sitemapIndexPath);

        // Add references to other individual sitemaps to the existing sitemap index
        $existingSitemapIndex->addChild('sitemap')->addChild('loc', '/sitemap-blogs' . $this_time . '.xml');
        $existingSitemapIndex->addChild('sitemap')->addChild('loc', '/sitemap-bloggers' . $this_time . '.xml');
        $existingSitemapIndex->addChild('sitemap')->addChild('loc', '/sitemap-topics' . $this_time . '.xml');
        $existingSitemapIndex->addChild('sitemap')->addChild('loc', '/sitemap-questions-' . $this_time . '.xml');
        // Write the updated sitemap index back to the file
        $existingSitemapIndex->asXML($sitemapIndexPath);
        // $sitemapIndex = SitemapIndex::create();

        // // Create and add instances of individual sitemaps
        // $sitemapBlogs = Sitemap::create();
        // $sitemapBloggers = Sitemap::create();
        // $sitemapTopics = Sitemap::create();

        // // Total number of question sitemaps

        // // Add URLs to other individual sitemaps
        // $sitemapBlogs->add('/blogs');
        // $sitemapBloggers->add('/bloggers');
        // $sitemapTopics->add('/topics');

        // // Write other individual sitemaps to files
        // $sitemapBlogs->writeToFile(public_path('sitemap-blogs'.$this_time.'.xml'));
        // $sitemapBloggers->writeToFile(public_path('sitemap-bloggers'.$this_time.'.xml'));
        // $sitemapTopics->writeToFile(public_path('sitemap-topics'.$this_time.'.xml'));

        // // Add references to other individual sitemaps to the sitemap index
        // $sitemapIndex->add('/sitemap-blogs'.$this_time.'.xml');
        // $sitemapIndex->add('/sitemap-bloggers'.$this_time.'.xml');
        // $sitemapIndex->add('/sitemap-topics'.$this_time.'.xml');

        // // Write the sitemap index to a file
        // $sitemapIndex->writeToFile(public_path('sitemap-index.xml'));
    }

    protected function getNewBlogUrls()
    {
        $currentDate = Carbon::now()->toDateString();
        // Fetch and return new blog URLs from your database
        // Implement the logic to query your database for new blog posts
        $blogs = DB::table('posts')->select('slug')->where('status', 1)->whereDate('created_at', $currentDate)->get();
        return $blogs;
    }

    protected function questionUrls()
    {
        $questions = DB::table('questions')
        ->select('topics.topic_name', 'questions.question')
        ->join('topics', 'questions.topic_id', '=', 'topics.id')
        ->where('questions.question', '=','Top local products') // Adjust the condition for starting ID
        ->get();
    
    return $questions;
    }

    protected function topicsUrls()
    {
        $currentDate = Carbon::now()->toDateString();
        $topics = DB::table('topics')->select('topic_name')->whereDate('date', $currentDate)->get();
        return $topics;
    }
    protected function bloggerUrls()
    {
        $processedBloggers = Cache::get('processed_bloggers', []);

        // Fetch the bloggers from the database
        $bloggers = DB::table('posts')
            ->select(DB::raw('DISTINCT user_id'), 'users.name')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->where('posts.status', '=', 1)
            ->get();

        // Filter out bloggers that have already been processed
        $newBloggers = collect($bloggers)->reject(function ($blogger) use ($processedBloggers) {
            return in_array($blogger->user_id, $processedBloggers);
        });

        // Update the list of processed bloggers
        $newProcessedBloggers = $newBloggers->pluck('user_id')->toArray();
        Cache::put('processed_bloggers', $newProcessedBloggers);

        return $newBloggers;
    }
}
