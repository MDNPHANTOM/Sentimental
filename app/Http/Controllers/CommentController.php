<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        $Comments = Comment::orderBy('created_at', 'desc')->paginate(20);
     }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Post $post) {
        return view('comments.create', [
            'post' => $post
            ]);
        }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Post $post){
            $request->validate([
            'commment_text' => 'required']);

            $comment = new Comment;
            $comment->user_id = Auth()->user()->id;
            $comment->post_id = $request->input('post_id');
            $comment->commment_text = $request->commment_text;
            $comment->save();
            return redirect()->back()->with('success', 'New Comment Added.');

        }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment) {
        return view('comments.show', [
        'comment' => $comment
        ]);
        }

        //edits comments
    public function edit(Comment $comment) {
        return view('comments.edit', [
        'comment' => $comment
        ]);
        }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment) {
        $request->validate([
            'commment_text' => 'required']);

        $comment->user_id = Auth()->user()->id;
        $comment->commment_text = $request->commment_text;
        $comment->save();
        return redirect()->route('posts.show', $comment->post_id)->with('success', 'Update Successfull');
        
    }




    public function destroy(Comment $comment) {
        $comment->delete();
        return redirect()->back()->with('success', 'New Comment Added.');
        }
}
