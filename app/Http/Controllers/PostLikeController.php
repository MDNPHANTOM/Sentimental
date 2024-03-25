<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;

class PostLikeController extends Controller
{
    public function like(Post $post){
        $liker = auth()->user();
        $liker->likes()->attach($post);

        return redirect()->back()->with('success', 'Post liked');
    }
    public function unlike(Post $post){
        $liker = auth()->user();
        $liker->likes()->detach($post);

        return redirect()->back()->with('success', 'Post unliked');
    }
}
