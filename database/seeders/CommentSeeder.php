<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CommentSeeder extends Seeder
{
    public function run()
    {
        // Generate 20 posts using the factory
        $comments = Comment::factory(100)->make();

  
        foreach ($comments as $comment) {
            $comment->user_id = User::inRandomOrder()->first()->id;
            $comment->post_id = Post::inRandomOrder()->first()->id;
            $responseData = $this->sendJSONToDjango($comment->comment_text);
            $comment->concern = $responseData['is_depressed'][0][0];
            $comment->fear = $responseData['sentiment']['fear'][0];
            $comment->anger = $responseData['sentiment']['anger'][0];
            $comment->anticipation = $responseData['sentiment']['anticipation'][0];
            $comment->trust = $responseData['sentiment']['trust'][0];
            $comment->surprise = $responseData['sentiment']['surprise'][0];
            $comment->positive = $responseData['sentiment']['positive'][0];
            $comment->negative = $responseData['sentiment']['negative'][0];
            $comment->sadness = $responseData['sentiment']['sadness'][0];
            $comment->disgust = $responseData['sentiment']['disgust'][0];
            $comment->joy = $responseData['sentiment']['joy'][0];
            $comment->neutral = $responseData['sentiment']['neutral'][0];
            $comment->compound = $responseData['sentiment']['compound'][0];

            $comment->save();

            if ($comment->concern == 1) {
                $user = User::find($comment->user_id);
                $user->concerns = $user->concerns += 1;
                $user->save();
            }



            
        }

    }


    public function sendJSONToDjango($message)
    {
        $jsonData = [
            'message' => $message,
        ];

        $jsonString = json_encode($jsonData);

        $response = Http::post('http://127.0.0.1:8000/api/detect-depression', [
            'message' => $jsonString, // Send JSON data in the request body
        ]);

        if ($response->successful()) {
            $responseData = $response->json();
            return $responseData;
        } else {
            $errorCode = $response->status();
            $errorMessage = $response->body();
            return response()->json(['error' => $errorMessage], $errorCode);
        }
    
    }
}