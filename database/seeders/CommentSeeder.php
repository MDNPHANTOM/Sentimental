<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\User;
use App\Models\Post;
class CommentSeeder extends Seeder
{
    public function run()
    {
        // Generate 20 posts using the factory
        $comments = Comment::factory(100)->make();

  
        foreach ($comments as $comment) {
            $comment->user_id = User::inRandomOrder()->first()->id;
            $comment->post_id = Post::inRandomOrder()->first()->id;
            $comment->save();

            if ($comment->concern == 1) {
                $user = User::find($comment->user_id);
                $user->concerns = $user->concerns += 1;
                $user->save();
            }



            
        }

    }
}