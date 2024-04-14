@extends('layouts.app')
@section('title', 'All of our Posts')
@section('content')
<button id="toggleButton">Toggle Content</button>

<div class="main-post" >
    <div id="reportedComments">
        <div class="main-info2">
            <span>Reported Comments</span>
            <a href="{{ route('posts_reported') }}"><button>Show All Reported Posts</button></a>
            @foreach ($comments as $comment)
                <div class="main-info">
                    <div class="main-frame427320725">
                        <div class="main-post-info">
                            <span class="main-text">{{ $comment->user->name }}</span>
                            <span class="main-text02">---</span>
                            <span class="main-text04">{{$comment->created_at->format('Y-m-d H:i:s')}}</span>
                            @if (Auth::user()->isAdmin == 1)
                                <div class="rem-element">
                                    <form action="{{ route('comments.destroy', $comment->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit">Delete Comment</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                        <div class="main-post-info">
                            <span class="main-text04">Concern: {{$comment->concern}}</span>
                            <span class="main-text04">Reports: {{$comment->comment_reports}}</span>
                            @if($comment->comment_reports > 0)
                                <div class="rem-element">
                                    <a href="{{ route('reports.comment_report', [$comment->user_id, $comment->id]) }}">
                                        <button type="button">Show Comment Reports</button>
                                    </a>
                                </div>
                            @endif
                        </div>
                        <span class="main-text06" id="commentText_{{ $comment->id }}">{{ $comment->comment_text }}</span>
                    </div>
                    <div class="main-react">
                        <a href="{{ route('comments.show', $comment->id) }}" role="link">
                        <img
                            alt="comment2014"
                            src="https://aheioqhobo.cloudimg.io/v7/_playground-bucket-v2.teleporthq.io_/437f2a70-6680-454b-9a7f-6e8fcfee7ba1/e8d7d336-b592-43ee-941a-5fdcd5a782e0?org_if_sml=1589&amp;force_format=original"
                            class="main-comment"
                        /></a>
                        <a><img
                            alt="save12015"
                            src="https://aheioqhobo.cloudimg.io/v7/_playground-bucket-v2.teleporthq.io_/437f2a70-6680-454b-9a7f-6e8fcfee7ba1/468f9d8e-41ac-479b-bd56-c5ebe3902f6a?org_if_sml=11373&amp;force_format=original"
                            class="main-save1"
                        /></a>
                        <a href="{{ route('comments.report', $comment->id) }}"><img
                            alt="flag12017"
                            src="/flag12017-9i3-200w.png"
                            class="main-image544983200"/></a>
                    </div>
                </div>    
            @endforeach
            <div class="nextpage">
                {{ $comments->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>