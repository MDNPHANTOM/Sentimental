@extends('layouts.app')
@section('title', 'Report Post')
@section('content')
<div class="main-post" >
    <div class="main-info">
        <a href="{{ route('comments.show', $comment->id) }}" role="link"></a>
        <div class="main-frame427320725">
            <div class="main-post-info">
                <span class="main-text">{{ $comment->user->name }}</span>
                <span class="main-text02">---</span>
                <span class="main-text04">{{$comment->created_at->format('Y-m-d H:i:s')}}</span>
                @if ($comment->user_id === Auth::user()->id)
                    <div class="rem-element">
                        <form action="{{ route('comments.destroy', $comment->id) }}" method="POST">
                            <a href="{{ route('comments.edit', $comment->id) }}"><button type="button">Edit Comment</button></a>
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete Comment</button>
                        </form>
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
            <a href="{{ route('comments.report', $comment->id) }}"><img
                alt="flag12017"
                src="{{ asset('images/flag.png') }}"
                class="main-image544983200"/></a>
        </div>
    </div>
    <form class="main-create-post1" action="{{ route('comments.report_comment', $comment->id) }}" method="POST">
        @csrf
        <div class="main-page-function">
            <span>Report Comment</span>   
            <textarea id="comment_report_text" name="comment_report_text" row="5" class="main-create-post-text @error('comment_report_text') is-invalid @enderror" type="text" placeholder="Create Comment"></textarea>
            @error('text')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <div class="main-create-post-frame1">
                <button class="main-create-post-button" type="submit">
                    <span class="main-create-post-button-text ">report</span>
                </button>
                @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                    <span>{{ $message }}</span>
                    </div>
                @endif
            </div>
        </div>
    </form>
</div>
@endsection