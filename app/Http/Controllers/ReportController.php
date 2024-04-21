<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\PostReport;
use App\Models\CommentReport;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReportController extends Controller
{
    public function create_post_report(Post $post)
    {
        return view('posts.report', [
            'post' => $post
            ]);
    }

    public function create_comment_report(Comment $comment)
    {
        return view('comments.report', [
            'comment' => $comment
            ]);
    }

        
    public function posts_reported(){
        $posts = Post::where('post_reports', '>', 0)->orderBy('created_at', 'desc')->paginate(20);
        return view('warnings.reportedPosts', ['posts' => $posts]);
    }
    
    public function comments_reported(){
        $comments = Comment::where('comment_reports', '>', 0)->orderBy('created_at', 'desc')->paginate(20);
        return view('warnings.reportedComments', ['comments' => $comments]);
    }

    
    public function show_user_reported_posts(Request $request, User $user){
        $posts = Post::where('post_reports', '>', 0)->where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.reported_posts', ['user' => $user,'posts' => $posts]);
    }

    public function show_user_reported_comments(Request $request, User $user){
        $comments = Comment::where('comment_reports', '>', 0)->where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.reported_comments', ['user' => $user,'comments' => $comments]);
    }


    public function show_post_reports(Request $request, User $user, Post $post){
        $postreports = PostReport::where('post_id', $post->id)->orderBy('created_at', 'desc')->paginate(20);
        $view = view('admin.reports.post_reports', ['user' => $user,'post' => $post,'postreports' => $postreports]);

        $response = new Response($view);
        $response->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    
        return $response;
    }

    public function show_comment_reports(Request $request, User $user, Comment $comment){
        $commentreports = CommentReport::where('comment_id', $comment->id)->orderBy('created_at', 'desc')->paginate(20);
        $comments = Comment::where('post_id', $comment->post_id)->orderBy('created_at', 'desc')->paginate(20);
        $post = Post::findOrFail($comment->post_id);
        $view = view('admin.reports.comment_reports', ['user' => $user,'post' => $post,'target_comment' => $comment,'comments' => $comments,'commentreports' => $commentreports]);

        $response = new Response($view);
        $response->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    
        return $response;
    }




    public function report_post(Request $request, Post $post){
        $request->validate([
            'post_report_text' => 'required|max:2000']);
        $p_report = new PostReport();
        $p_report->user_id = Auth()->user()->id;
        $p_report->post_id = $post->id;
        $p_report->post_report_text = $request->post_report_text;
        $p_report->save();

        //adds and to the total number of reports for user
        $user = User::find($post->user_id);
        $user->reported = $user->reported += 1;
        $user->save();
        $post->post_reports += 1;
        $post->save();
        return redirect()->route('posts.index')->with('success', 'Report Sent Successfully');
    }

    public function report_comment(Request $request, Comment $comment){
        $request->validate([
            'comment_report_text' => 'required|max:2000']);

        $c_report = new CommentReport();
        $c_report->user_id = Auth()->user()->id;
        $c_report->comment_id = $comment->id;
        $c_report->comment_report_text = $request->comment_report_text;
        $c_report->save();

         //adds and to the total number of reports for user
        $user = User::find($comment->user_id);
        $user->reported = $user->reported += 1;
        $user->save();
        $comment->comment_reports += 1;
        $comment->save();
        return redirect()->route('posts.index')->with('success', 'Report Sent Successfully');
    }


        
    public function post_destroy(Post $post) {
        $user = User::find($post->user_id);
        if($post->concern == 1 && $user->concerns > 0){
            $user->concerns = $user->concerns -= 1;
        }
        $user->reported -= $post->post_reports;
        $post->delete();
        $user->save();
        return redirect()->route('admin.reported_posts', $user->id)->with('success', 'Post Deleted Successfully');
        
        }

        
    public function comment_destroy(Comment $comment) {
        $user = User::find($comment->user_id);
        if($comment->concern == 1 && $user->concerns > 0){
            $user->concerns = $user->concerns -= 1;
        }
        $user->reported -= $comment->comment_reports;
        $comment->delete();
        $user->save();
        return redirect()->route('admin.reported_comments', $user->id)->with('success', 'Comment Deleted Successfully');
        }

    public function delete_post_report(PostReport $report){
        $post = Post::findOrFail($report->post_id);
        $user = User::findOrFail($post->user_id);
        if($user->reported > 0 &&  $post->post_reports > 0){
            $user->reported -= 1;
            $post->post_reports -= 1;
        }
        $user->save();
        $post->save();
        $report->delete();
        return redirect()->back()->with('success', 'Deleted Report on post');
    }


    public function delete_comment_report(CommentReport $report){
        $comment = Comment::find($report->comment_id);
        $user = User::find($comment->user_id);
        if($user->reported > 0 &&  $comment->comment_reports > 0){
            $user->reported -= 1;
            $comment->comment_reports -= 1;
        }
        $user->save();
        $comment->save();
        $report->delete();
        return redirect()->back()->with('success', 'Deleted Report on Comment');
    }



}
