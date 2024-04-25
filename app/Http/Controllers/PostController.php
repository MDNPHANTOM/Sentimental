<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
    public function store(Request $request){
        $request->validate([
            'text' => 'required|max:1000']);
    
        $post = new Post();
        $post->user_id = Auth()->user()->id;
        $post->text = $request->text;

        $responseData = $this->sendJSONToDjango($request->text);
        $post->concern = $responseData['is_depressed'][0][0];
        $post->fear = $responseData['sentiment']['fear'][0];
        $post->anger = $responseData['sentiment']['anger'][0];
        $post->anticipation = $responseData['sentiment']['anticipation'][0];
        $post->trust = $responseData['sentiment']['trust'][0];
        $post->surprise = $responseData['sentiment']['surprise'][0];
        $post->positive = $responseData['sentiment']['positive'][0];
        $post->negative = $responseData['sentiment']['negative'][0];
        $post->sadness = $responseData['sentiment']['sadness'][0];
        $post->disgust = $responseData['sentiment']['disgust'][0];
        $post->joy = $responseData['sentiment']['joy'][0];
        $post->neutral = $responseData['sentiment']['neutral'][0];
        $post->compound = $responseData['sentiment']['compound'][0];
        $post->concern_score = $post->concern + ($post->compound * -1);

        $post->created_at = now();
        $post->updated_at = now();
        $user = Auth()->user();
        if ($post->concern == 1) {
            $user->concerns = $user->concerns += 1;     
        }
        if ($post->concern_score > 0) {
            $user->score_neg +=  $post->concern_score;
        } else {
            $user->score_pos +=  (($post->concern_score-1)*-1);
        }
        $user->save();
        $post->save();
        return redirect()->route('posts.index')->with('success', 'Post Created');
    }
    
    public function update(Request $request, Post $post) {
        if(Auth()->user()->id == $post->user_id){
            $request->validate([
                'text' => 'required|max:1000']);

            $prescore = $post->concern_score;
            $preconcern = $post->concern;
            $post->user_id = Auth()->user()->id;
            $post->text = $request->text;

            $responseData = $this->sendJSONToDjango($request->text);
            $post->concern = $responseData['is_depressed'][0][0];
            $post->fear = $responseData['sentiment']['fear'][0];
            $post->anger = $responseData['sentiment']['anger'][0];
            $post->anticipation = $responseData['sentiment']['anticipation'][0];
            $post->trust = $responseData['sentiment']['trust'][0];
            $post->surprise = $responseData['sentiment']['surprise'][0];
            $post->positive = $responseData['sentiment']['positive'][0];
            $post->negative = $responseData['sentiment']['negative'][0];
            $post->sadness = $responseData['sentiment']['sadness'][0];
            $post->disgust = $responseData['sentiment']['disgust'][0];
            $post->joy = $responseData['sentiment']['joy'][0];
            $post->neutral = $responseData['sentiment']['neutral'][0];
            $post->compound = $responseData['sentiment']['compound'][0];
            $post->concern_score = $post->concern + ($post->compound * -1);

            $post->updated_at = now();
            $user = User::find($post->user_id);
            if($preconcern < $post->concern){
                $user->concerns = $user->concerns += 1;}
            elseif($preconcern > $post->concern && $user->concerns > 0){
                $user->concerns = $user->concerns -= 1;} 
            else{
                $user->concerns + 0;
            }
            if ($post->concern_score >= 0) {
                $user->score_neg -=  $prescore;
                $user->score_neg +=  $post->concern_score;
            } else {
                $user->score_pos -=  (($prescore-1)*-1);
                $user->score_pos +=  (($post->concern_score-1)*-1);
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
                if ($post->concern_score >= 0 &&  ($user->score_neg - $post->concern_score > 0) ) {
                    $user->score_neg -=  $post->concern_score;
                } 
                if($post->concern_score < 0  &&  ($user->score_pos - $post->concern_score > 0)) {
                    $user->score_pos -=  (($post->concern_score-1)*-1);
                }
                $user->reported -= $post->post_reports;
                $post->delete();
                $user->save();
                return redirect()->back()->with('success', 'Post Deleted Successfully');
            }
            else{
                $user = Auth()->user();
                if($post->concern == 1 && $user->concerns > 0){
                    $user->concerns = $user->concerns -= 1;
                }
                if ($post->concern_score >= 0 &&  ($user->score_neg - $post->concern_score > 0) ) {
                    $user->score_neg -=  $post->concern_score;
                } 
                if($post->concern_score < 0  &&  ($user->score_pos - $post->concern_score > 0)) {
                    $user->score_pos -=  (($post->concern_score-1)*-1);
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
