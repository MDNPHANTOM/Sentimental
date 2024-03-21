<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    { }
    

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
        if(Auth::user()){
            $request->validate([
            'text' => 'required']);

            $comment = new Comment;
            $comment->user()->associate(Auth::user());
            $comment->post_id = $request->input('post_id');
            $comment->text = $request->text;
            $comment->save();
            return redirect()->back()->with('success', 'New Comment Added.');
        }
        else{return abort(401);}
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
            'text' => 'required']);

        $comment->user()->associate(Auth::user());
        $comment->text = $request->text;
        $comment->save();
        return redirect()->route('games.index')->with('success', 'Update Successfull');
        
    }




    public function destroy(Comment $comment) {
        $comment->delete();
        return redirect()->back()->with('success', 'New Comment Added.');
        }
}
