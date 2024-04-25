<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PostSeeder extends Seeder
{
    public function run()
    {
        // Generate 20 posts using the factory
        $posts = Post::factory(40)->make();


        foreach ($posts as $post) {
            $post->user_id = User::inRandomOrder()->first()->id;
            $responseData = $this->sendJSONToDjango($post->text);
            $post->concern = $responseData['is_depressed'][0][0];
            $post->fear = $responseData['sentiment']['fear'][0];
            $post->anger = $responseData['sentiment']['anger'][0];
            $post->anticipation = $responseData['sentiment']['anticipation'][0];
            $post->trust = $responseData['sentiment']['trust'][0];
            $post->surprise = $responseData['sentiment']['surprise'][0];
            $post->positive = $responseData['sentiment']['positive'][0];
            $post->negative = $responseData['sentiment']['negative'][0];
            $post->sadness = $responseData['sentiment']['sadness'][0];
            $post->disgust = $responseData['sentiment']['disgust'][0];
            $post->joy = $responseData['sentiment']['joy'][0];
            $post->neutral = $responseData['sentiment']['neutral'][0];
            $post->compound = $responseData['sentiment']['compound'][0];
            $post->concern_score = $post->concern + ($post->compound * -1);
            $post->save();
            $user = User::find($post->user_id);
            if ($post->concern == 1) {
                // Get the user associated with this post and update their concerns count
                $user->concerns = $user->concerns += 1;
            }
            if ($post->concern_score >= 1) {
                $user->score_neg +=  $post->concern_score;
            } else {
                $user->score_pos +=  (($post->concern_score-1)*-1);
            }
            $user->save();

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
