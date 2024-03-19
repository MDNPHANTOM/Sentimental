<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;
class PostSeeder extends Seeder
{
    public function run()
    {
        // Generate 20 posts using the factory
        $posts = Post::factory(20)->make();

        // Assign random user and game to each post and save to the database
        foreach ($posts as $post) {
            $post->user_id = User::inRandomOrder()->first()->id;
            $post->save();
        }
    }
}
