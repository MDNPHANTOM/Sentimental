<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PostReport;
use App\Models\Post;
use App\Models\User;


class PostReportSeeder extends Seeder
{
    public function run()
    {
        // Generate 20 posts reports using the factory
        $reportedPosts = PostReport::factory(40)->make();

   
        foreach ($reportedPosts as $reportedPost) {
            $reportedPost->user_id = User::inRandomOrder()->first()->id;
            $reportedPost->post_id = Post::inRandomOrder()->first()->id;
            $reportedPost->save();

            $post = Post::find($reportedPost->post_id);
            $post->post_reports = $post->post_reports += 1;

            $user = User::find($post->user_id);
            $user->reported = $user->reported += 1;



            $user->save();
            $post->save();
            
        }
    }
}

