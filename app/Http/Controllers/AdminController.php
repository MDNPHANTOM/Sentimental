<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function userindex()
     {
        $Users = User::orderBy('created_at', 'desc')->paginate(20);
     }
    
     public function post_comment_index()
     {
        $posts = Post::where('concern', 1)->orderBy('created_at', 'desc')->paginate(20);
        $comments = Comment::where('concern', 1)->orderBy('created_at', 'desc')->paginate(20);

        return view('warnings.flaggedContents', ['posts' => $posts,'comments' => $comments]);

     }


    public function set_user_support(Request $request, User $user) {
        $request->validate([
            'support_tier' => 'required']);

        if(Auth()->user()->isAdmin(1)){
            $user->support_tier = $request->support_tier;
            $user->save();
            return redirect()->route('users.show', $user->id)->with('success', 'User Blocked');
        }
    }        

    public function block_user(Request $request, User $user) {
        if(Auth()->user()->isAdmin(1)){
            $user->blocked = 1;
            $user->save();
            return redirect()->route('users.show', $user->id)->with('success', 'User Blocked');
        }
    }
    public function unblock_user(Request $request, User $user) {
        if(Auth()->user()->isAdmin(1)){
            $user->blocked = 0;
            $user->save();
            return redirect()->route('users.show', $user->id)->with('success', 'User UnBlocked');
        }
    }
}
