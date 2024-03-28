@extends('layouts.app')

@section('content')
<div class="main-post" >
    <form class="main-create-post" action="{{ route('comments.update', $comment->id) }}" method="POST">
        @csrf
        @method('PUT')
        <a href="/profile" class="main-text32">{{Auth::user()->name}}</a>
        <textarea name="commment_text" class="main-create-post-text @error('commment_text') is-invalid @enderror" id="commment_text" type="text" required>{{ old('commment_text', $comment->commment_text) }}</textarea>
        @error('comment_text')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <div class="main-create-post-frame1">
            <button class="main-create-post-button" type="submit">
                <span class="main-create-post-button-text ">update</span>
            </button>
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                <span>{{ $message }}</span>
                </div>
            @endif
        </div>
    </form>
</div>
@endsection