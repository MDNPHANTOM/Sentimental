@extends('layouts.app')
@section('title', 'showing comment')
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
                src="/flag12017-9i3-200w.png"
                class="main-image544983200"/></a>
        </div>
    </div>
</div>
@endsection