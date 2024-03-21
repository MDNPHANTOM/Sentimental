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
        $Comments = Comment::orderBy('created_at', 'desc')->paginate(20);
        return view('posts.show', [
        'post' => $post,'comments' => $Comments
        ]);
        }


    public function edit(Post $post) {
        
        return view('post.edit', [
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
        $post->text = $request->text;
        $post->created_at = now();
        $post->updated_at = now();
        $post->save();
    
        return redirect()->back()->with('success', 'New Post Added.');
    }
    
    public function update(Request $request, Post $post) {
        $request->validate([
            'text' => 'required',
            ]);
        $post->text = $request->text;
        $post->updated_at = now();
        $post->save();
        return redirect()->route('posts.index')->with('success', 'Update Successfull');
        }

    
    public function destroy(Post $post) {
        $post->delete();
        return redirect()->route('post.index')->with('success', 'Post deleted successfully');
        }

    
    
    




}
