<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CommentReport;
use App\Models\Comment;
use App\Models\User;

class CommentReportSeeder extends Seeder
{
    public function run()
    {
        // Generate 20 posts using the factory
        $reportedComments = CommentReport::factory(40)->make();

      
        foreach ($reportedComments as $reportedComment) {
            $reportedComment->user_id = User::inRandomOrder()->first()->id;
            $reportedComment->comment_id = Comment::inRandomOrder()->first()->id;
            $reportedComment->save();
            
            $comment = Comment::find($reportedComment->comment_id);
            $comment->comment_reports =  $comment->comment_reports += 1;
            
            $user = User::find($comment->user_id);
            $user->reported = $user->reported += 1;
            
            $user->save();
            $comment->save();

        }
    }
}