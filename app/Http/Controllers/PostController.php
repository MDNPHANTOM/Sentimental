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
        $posts = Post::orderBy('created_at', 'desc')->paginate(20);
        return view('posts.index', compact('posts'));
        
    }
    public function show(Post $post) {
        $comments = Comment::where('post_id', $post->id)->orderBy('created_at', 'desc')->paginate(20);
        return view('posts.show', [
        'post' => $post,'comments' => $comments
        ]);
        }


    public function edit(Post $post) {
        
        return view('posts.edit', [
        'post' => $post
        ]);
        }


    public function create()
    {
        return view('posts.create');
    }


    
    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required']);
    
        $post = new Post();
        $post->user_id = Auth()->user()->id;
        $post->concern = rand(0,1);
        $post->text = $request->text;
        $post->created_at = now();
        $post->updated_at = now();
        $post->save();
    
        return redirect()->route('posts.index')->with('success', 'Update Successfull');
    }
    
    public function update(Request $request, Post $post) {
        $request->validate([
            'text' => 'required',
            ]);
        $post->user_id = Auth()->user()->id;
        $post->text = $request->text;
        $post->concern = rand(0,1);
        $post->updated_at = now();
        $post->save();
        return redirect()->route('posts.index')->with('success', 'Update Successfull');
        }

    
    public function destroy(Post $post) {
        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Post deleted successfully');
        }

    
    
    




}
