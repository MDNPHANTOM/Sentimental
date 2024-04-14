<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use App\Models\Post;
use App\Models\PostReport;
use App\Models\CommentReport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
     {
        $users = User::orderBy('concerns', 'desc')->paginate(20);

        return view('admin.index', ['users' => $users]);
     }
    
     public function posts_flagged()
     {
        $posts = Post::where('concern', 1)->orderBy('created_at', 'desc')->paginate(20);

        return view('warnings.flaggedPosts', ['posts' => $posts]);

     }

     public function comments_flagged()
     {
        $comments = Comment::where('concern', 1)->orderBy('created_at', 'desc')->paginate(20);

        return view('warnings.flaggedComments', ['comments' => $comments]);
     }

     public function show_user_flagged_posts(Request $request, User $user)
     {
        $posts = Post::where('concern', 1)->where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.flagged_posts', ['user' => $user,'posts' => $posts]);

     }
     public function show_user_flagged_comments(Request $request, User $user){

        $comments = Comment::where('concern', 1)->where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.flagged_comments', ['user' => $user,'comments' => $comments]);

     }

    public function set_user_support(Request $request, User $user) {
        $request->validate([
            'support_tier' => 'required|numeric|between:0,3']);

            $user->support_tier = $request->support_tier;
            $user->save();
            return redirect()->back()->with('success', 'Support Set');
        
    }        



    public function block_user(Request $request, User $user) {
        if($user->blocked){
            $user->blocked = 0;
        }
        else{ 
            $user->blocked = 1;
        }
        $user->save();
        return redirect()->back()->with('success', 'User Blocked');
        
    }

}
