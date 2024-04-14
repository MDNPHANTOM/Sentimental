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
        $posts = Post::factory(40)->make();


        foreach ($posts as $post) {
            $post->user_id = User::inRandomOrder()->first()->id;
            $post->save();

            if ($post->concern == 1) {
                // Get the user associated with this post and update their concerns count
                $user = User::find($post->user_id);
                $user->concerns = $user->concerns += 1;
                $user->save();
            }
        }
    }
}
