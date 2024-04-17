<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function index(){
        $blocked_users = User::where('blocked', 1)->pluck('id');
        $posts = Post::orderBy('created_at', 'desc')->where(function ($query) use ($blocked_users) {
            $query->whereNotIn('user_id', $blocked_users)
                ->orWhere('user_id', Auth()->user()->id);
                })->orderBy('created_at', 'desc')->paginate(20);
        return view('posts.index', compact('posts'));
        
    }
    public function show(Post $post) {
        $creator = User::find($post->user_id);
        if($creator->blocked == 1 && Auth()->user()->id != $creator->id){
            abort(403, 'Unauthorized access Post is Blocked');
        }else{
            $blocked_users = User::where('blocked', 1)->pluck('id');
            $comments = Comment::where('post_id', $post->id)->where(function ($query) use ($blocked_users) {
                $query->whereNotIn('user_id', $blocked_users)
                    ->orWhere('user_id', Auth()->user()->id);
                    })->orderBy('created_at', 'desc')->paginate(20);
            return view('posts.show', ['post' => $post,'comments' => $comments]);
        }
    } 


    public function edit(Post $post) {
        if(Auth()->user()->id == $post->user_id ){
            return view('posts.edit', [
            'post' => $post
            ]);
        }else{
            abort(403, 'Unauthorized access');
        }
    }


    public function create()
    {
        return view('posts.create');
    }


    
    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required|max:1000']);
    
        $post = new Post();
        $post->user_id = Auth()->user()->id;
        $post->concern = rand(0,1);
        $post->text = $request->text;
        $post->created_at = now();
        $post->updated_at = now();
        $user = Auth()->user();
        if ($post->concern == 1) {
            $user->concerns = $user->concerns += 1;     
        }
        $user->save();
        $post->save();
        return redirect()->route('posts.index')->with('success', 'Post Created');
    }
    
    public function update(Request $request, Post $post) {
        if(Auth()->user()->id == $post->user_id){
            $request->validate([
                'text' => 'required|max:1000']);

            $preconcern = $post->concern;
            $post->user_id = Auth()->user()->id;
            $post->text = $request->text;
            $post->concern = rand(0,1);
            $post->updated_at = now();

            $user = User::find($post->user_id);
            if($preconcern < $post->concern){
                $user->concerns = $user->concerns += 1;}
            elseif($preconcern > $post->concern && $user->concerns > 0){
                $user->concerns = $user->concerns -= 1;} 
            else{
                $user->concerns + 0;
            }
            $user->save();
            $post->save();

            return redirect()->route('posts.index')->with('success', 'Update Successfull');
        } else{
            abort(403, 'Unauthorized access');
        }
    }



        
    
    public function destroy(Post $post) {
        if(Auth()->user()->id == $post->user_id ){
            if(Auth()->user()->isAdmin == 1){
                $user = User::find($post->user_id);
                if($post->concern == 1 && $user->concerns > 0){
                    $user->concerns = $user->concerns -= 1;
                }
                $user->reported -= $post->post_reports;
                $post->delete();
                $user->save();
                return redirect()->back()->with('s uccess', 'Post Deleted Successfully');
            }
            else{
                $user = Auth()->user();
                if($post->concern == 1 && $user->concerns > 0){
                    $user->concerns = $user->concerns -= 1;
                }
                $user->reported -= $post->post_reports;
                $post->delete();
                $user->save();
                return redirect()->route('posts.index')->with('success', 'Post Deleted Successfully');
            }
        } else{
            abort(403, 'Unauthorized access');
        }
    }

    
    
    




}
