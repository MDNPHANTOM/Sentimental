@extends('layouts.app')
@section('title', 'showing comment')
@section('content')
<div class="main-post" >
    <div class="main-info">
        <a href="{{ route('comments.show', $comment->id) }}" role="link">
        <div class="main-frame427320725">
            <div class="main-post-info">
                <span class="main-text">{{ $comment->user->name }}</span>
                <span class="main-text02">---</span>
                <span class="main-text04">4 Dec 2020</span>
                @if ($comment->user_id === Auth::user()->id)
                    <div class="rem-element">
                        <button id="removeFriendButton" >Edit Comment</button>
                        <button id="removeFriendButton" >Delete Comment</button>
                    </div>
                @endif
            </div>
            <form id="editForm_{{ $comment->id }}" style="display: none;">
                <textarea name="comment" id="editTextarea_{{ $comment->id }}">{{ $comment->comment_text }}</textarea>
                <button type="submit">Save</button>
                <input type="hidden" name="comment_id" value="{{ $comment->id }}">
            </form>
            <span class="main-text06" id="commentText_{{ $comment->id }}">{{ $comment->commment_text }}</span>
        </div>
        </a>
        <div class="main-react">
        <a>
        <img
            alt="save12015"
            src="https://aheioqhobo.cloudimg.io/v7/_playground-bucket-v2.teleporthq.io_/437f2a70-6680-454b-9a7f-6e8fcfee7ba1/468f9d8e-41ac-479b-bd56-c5ebe3902f6a?org_if_sml=11373&amp;force_format=original"
            class="main-save1"
        /></a>

        <a>
        <img
            alt="IMAGE5449832002014"
            src="https://aheioqhobo.cloudimg.io/v7/_playground-bucket-v2.teleporthq.io_/437f2a70-6680-454b-9a7f-6e8fcfee7ba1/5d2245cc-19f3-443a-aab3-ede589c81328?org_if_sml=11070&amp;force_format=original"
            class="main-image544983200"
        /></a>
        </div>
    </div>
</div>
@endsection