@extends('layouts.app')
@section('title', 'flaggedPosts')
@section('content')
<div class="main-post" >
    <div id="flaggedComments">
        <div class="main-info2">
            <span>Flagged Comments</span>
            <a href="{{ route('posts_flagged') }}"><button>Show All Flagged Posts</button></a>
            @foreach ($comments as $comment)
                @if($comment->concern == 1)
                    <div class="main-info">
                        <div class="main-frame427320725">
                            <div class="main-post-info">
                                <a href="{{ route('admin.flagged_comments', $comment->user_id) }}" class="main-text">{{ $comment->user->name }}</a>
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
                                <span class="main-text04">Concern Score: {{$comment->concern_score}}</span>
                                <span class="main-text04">Concern: {{$comment->concern}}</span>
                                <span class="main-text04">Reports: {{$comment->comment_reports}}</span>
                            </div>
                            <div class="main-frame427320700">
                                <div class="main-post-info1">
                                    <span class="main-text04">OVERALL EVAL:</span>
                                    <span class="main-text04">Compound: {{$comment->compound}}</span>
                                    <span class="main-text04">Neutral: {{$comment->neutral}}</span>
                                    <span class="main-text04">Positive: {{$comment->positive}}</span>
                                    <span class="main-text04">Negative: {{$comment->negative}}</span>
                                    <span></span>
                                </div>
                                <div class="main-post-info1">
                                    <span class="main-text04">NEGATIVE EMOTION:</span>
                                    <span class="main-text04">Fear: {{$comment->fear}}</span>
                                    <span class="main-text04">Anger: {{$comment->anger}}</span>
                                    <span class="main-text04">Sadness: {{$comment->sadness}}</span>
                                    <span class="main-text04">Disgust: {{$comment->disgust}}</span>
                                    <span></span>
                                </div>
                                <div class="main-post-info1">
                                    <span class="main-text04">POSITIVE EMOTION:</span>
                                    <span class="main-text04">Joy: {{$comment->joy}}</span>
                                    <span class="main-text04">Trust: {{$comment->trust}}</span>
                                    <span class="main-text04">Surprise: {{$comment->surprise}}</span>
                                    <span class="main-text04">Anticipation: {{$comment->anticipation}}</span>
                                    <span></span>
                                </div>
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
                            <a href="{{ route('comments.report', $comment->id) }}"><img
                                alt="flag12017"
                                src="{{ asset('images/flag.png') }}"
                                class="main-image544983200"/></a>
                        </div>
                    </div>
                @endif
            @endforeach
            <div class="nextpage">
                {{ $comments->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>

@endsection