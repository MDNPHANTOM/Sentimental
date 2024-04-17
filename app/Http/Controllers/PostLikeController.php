<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;

class PostLikeController extends Controller
{
    public function show_liked_posts(){

        $liker = auth()->user();
        $liker->likes();
        $posts =  $liker->likes()->orderBy('created_at', 'desc');
        return view('users.liked', compact('posts'));
    }

    public function like(Post $post){
        $liker = auth()->user();
        $user = User::find($post->user_id);
        if($user->blocked == 1){
            abort(403, 'Unauthorized access Post is Blocked');
        }else{
            $liker->likes()->attach($post);
            return redirect()->back()->with('success', 'Post liked');
        }
    }

    public function unlike(Post $post){
        $liker = auth()->user();
        $user = User::find($post->user_id);
        if($user->blocked == 1){
            abort(403, 'Unauthorized access Post is Blocked');
        }else{
            $liker->likes()->detach($post);
            return redirect()->back()->with('success', 'Post unliked');
        }
    }




}
