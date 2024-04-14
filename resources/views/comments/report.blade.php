@extends('layouts.app')
@section('title', 'Report Post')
@section('content')
<div class="main-post" >
    <form class="main-create-post1" action="{{ route('comments.report_comment', $comment->id) }}" method="POST">
        @csrf
        <div class="main-page-function">
            <span>Report Post</span>   
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