<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class CommentController extends Controller
{
    public function index()
    {
     }
    
    public function create(Post $post) {
        return view('comments.create', [
            'post' => $post
            ]);
        }

    public function sendJSONToDjango($message)
    {
        $jsonData = [
            'message' => $message,
        ];

        $jsonString = json_encode($jsonData);

        $response = Http::post('http://127.0.0.1:8000/api/detect-depression', [
            'message' => $jsonString, // Send JSON data in the request body
        ]);

        if ($response->successful()) {
            $responseData = $response->json();
            return $responseData;
        } else {
            $errorCode = $response->status();
            $errorMessage = $response->body();
            return response()->json(['error' => $errorMessage], $errorCode);
        }
    
    }

    public function store(Request $request,Post $post){
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'comment_text' => 'required|max:1000']);

        $comment = new Comment;
        $comment->user_id = Auth()->user()->id;
        $comment->post_id = $request->input('post_id');
        $comment->comment_text = $request->comment_text;

        $responseData = $this->sendJSONToDjango($request->comment_text);
        $comment->concern = $responseData['is_depressed'][0][0];
        $comment->fear = $responseData['sentiment']['fear'][0];
        $comment->anger = $responseData['sentiment']['anger'][0];
        $comment->anticipation = $responseData['sentiment']['anticipation'][0];
        $comment->trust = $responseData['sentiment']['trust'][0];
        $comment->surprise = $responseData['sentiment']['surprise'][0];
        $comment->positive = $responseData['sentiment']['positive'][0];
        $comment->negative = $responseData['sentiment']['negative'][0];
        $comment->sadness = $responseData['sentiment']['sadness'][0];
        $comment->disgust = $responseData['sentiment']['disgust'][0];
        $comment->joy = $responseData['sentiment']['joy'][0];
        $comment->neutral = $responseData['sentiment']['neutral'][0];
        $comment->compound = $responseData['sentiment']['compound'][0];
        $comment->concern_score = $comment->concern + ($comment->compound * -1);

        $comment->save();
        $user = Auth()->user();
        if($comment->concern == 1)
            $user->concerns += 1;
        if ($comment->concern_score >= 0) {
            $user->score_neg +=  $comment->concern_score;
        } else {
            $user->score_pos +=  (($comment->concern_score-1)*-1);
        }
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
            $prescore = $comment->concern_score;
            $comment->user_id = Auth()->user()->id;
            $comment->comment_text = $request->comment_text;
    
            $responseData = $this->sendJSONToDjango($request->comment_text);
            $comment->concern = $responseData['is_depressed'][0][0];
            $comment->fear = $responseData['sentiment']['fear'][0];
            $comment->anger = $responseData['sentiment']['anger'][0];
            $comment->anticipation = $responseData['sentiment']['anticipation'][0];
            $comment->trust = $responseData['sentiment']['trust'][0];
            $comment->surprise = $responseData['sentiment']['surprise'][0];
            $comment->positive = $responseData['sentiment']['positive'][0];
            $comment->negative = $responseData['sentiment']['negative'][0];
            $comment->sadness = $responseData['sentiment']['sadness'][0];
            $comment->disgust = $responseData['sentiment']['disgust'][0];
            $comment->joy = $responseData['sentiment']['joy'][0];
            $comment->neutral = $responseData['sentiment']['neutral'][0];
            $comment->compound = $responseData['sentiment']['compound'][0];
            $comment->concern_score = $comment->concern + ($comment->compound * -1);

            $comment->save();

            $user = Auth()->user();
            if($preconcern < $comment->concern)
                $user->concerns = $user->concerns += 1;
            elseif($preconcern > $comment->concern && $user->concerns > 0){
                $user->concerns = $user->concerns -= 1;
            } else{
                $user->concerns + 0;
            } 
            if ($comment->concern_score >= 0) {
                $user->score_neg -=  $prescore;
                $user->score_neg +=  $comment->concern_score;
            } else {
                $user->score_neg -=  (($prescore-1)*-1);
                $user->score_pos +=  (($comment->concern_score-1)*-1);
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
                if ($comment->concern_score >= 0 &&  ($user->score_neg - $comment->concern_score > 0) ) {
                    $user->score_neg -=  $comment->concern_score;
                } 
                if($comment->concern_score < 0  &&  ($user->score_pos - $comment->concern_score > 0)) {
                    $user->score_pos -=  (($comment->concern_score-1)*-1);
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
                if ($comment->concern_score >= 0 &&  ($user->score_neg - $comment->concern_score > 0) ) {
                    $user->score_neg -=  $comment->concern_score;
                } 
                if($comment->concern_score < 0  &&  ($user->score_pos - $comment->concern_score > 0)) {
                    $user->score_pos -=  (($comment->concern_score-1)*-1);
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
