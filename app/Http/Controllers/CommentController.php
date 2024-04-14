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
     *ds
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Post $post){
        $request->validate([
            'comment_text' => 'required|max:1000']);

        $comment = new Comment;
        $comment->user_id = Auth()->user()->id;
        $comment->post_id = $request->input('post_id');
        $comment->comment_text = $request->comment_text;
        $comment->concern = rand(0,1);
        $comment->save();
        $user = Auth()->user();
        if($comment->concern == 1)
            $user->concerns += 1;
            $user->save();
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
        if(auth()->user()->id == $comment->user_id){
            return view('comments.edit', [
            'comment' => $comment
            ]);
        } else{
            abort(403, 'Unauthorized access');
        }

        }

    public function update(Request $request, Comment $comment) {
        if(auth()->user()->id == $comment->user_id){
            $request->validate([
                'comment_text' => 'required|max:1000']);

            $preconcern = $comment->concern;
            $comment->user_id = Auth()->user()->id;
            $comment->comment_text = $request->comment_text;
            $comment->concern = rand(0,1);
            $comment->save();

            $user = Auth()->user();
            if($preconcern < $comment->concern)
                $user->concerns = $user->concerns += 1;
            elseif($preconcern > $comment->concern && $user->concerns > 0){
                $user->concerns = $user->concerns -= 1;
            } else{
                $user->concerns + 0;
            }
            $user->save();
            return redirect()->route('posts.show', $comment->post_id)->with('success', 'Update Successfull');
        } else{
            abort(403, 'Unauthorized access');
        }
    }




    public function destroy(Comment $comment) {
        if(auth()->user()->id == $comment->user_id){
            if(Auth()->user()->isAdmin == 1){
                $user = User::find($comment->user_id);
                if($comment->concern == 1 && $user->concerns > 0){
                    $user->concerns = $user->concerns -= 1;
                }
                $user->reported -= $comment->comment_reports;
                $comment->delete();
                $user->save();
                return redirect()->back()->with('success', 'Comment Deleted Successfully');
            }
            else{
                $user = Auth()->user();
                if($comment->concern == 1 && $user->concerns > 0){
                    $user->concerns = $user->concerns -= 1;
                }
                $user->reported -= $comment->comment_reports;
                $comment->delete();
                $user->save();
                return redirect()->route('posts.show', $comment->post_id)->with('success', 'Comment Deleted Successfully');
            }
        } else{
            abort(403, 'Unauthorized access');
        }   
    }
}
