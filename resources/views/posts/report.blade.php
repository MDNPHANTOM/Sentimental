@extends('layouts.app')
@section('title', 'Report Post')
@section('content')
<div class="main-post" >
    <div class="main-show">
        <div class="main-frame427320725">
            <div class="main-post-info">
                <span class="main-text">{{ $post->user->name }}</span>
                <span class="main-text02">---</span>
                <span class="main-text04">{{$post->created_at->format('Y-m-d H:i:s')}}</span>
                <span class="main-text04">Concern: {{$post->concern}}</span>
                @if ($post->user_id === Auth::user()->id)
                    <div class="rem-element">
                        <form  id="removeFriendButton" action="{{ route('posts.destroy', $post->id) }}" method="POST">
                        <a href="{{ route('posts.edit', $post->id) }}"><button type="button">Edit Post</button></a>
                        @csrf
                        @method('DELETE')
                        <button type="submit">Delete Post</button>
                        </form>
                    </div>
                @endif
            </div>
            <span class="main-text06">{{ $post->text }}</span>
        </div>
        <div class="main-react">
            <form id="likeForm" action="{{ $post->likedBy(auth()->user()) ? route('posts.unlike', $post) : route('posts.like', $post) }}" method="POST">
                @csrf
                @if($post->likedBy(auth()->user()))
                    @method('DELETE')
                @endif
                <button type="submit" style="background: none; border: none;">
                    <img
                        alt="likebutton161993113866172014"
                        src="https://aheioqhobo.cloudimg.io/v7/_playground-bucket-v2.teleporthq.io_/437f2a70-6680-454b-9a7f-6e8fcfee7ba1/7174dfe3-8544-4c6c-875e-d0aec2ab361e?org_if_sml=15703&amp;force_format=original"
                        class="main-likebutton16199311386617"
                    />
                    <span>{{ $post->likes()->count() }}</span>
                </button>
            </form>
            <a href="{{ route('posts.show', $post->id) }}" role="link">
            <img
                alt="comment2014"
                src="https://aheioqhobo.cloudimg.io/v7/_playground-bucket-v2.teleporthq.io_/437f2a70-6680-454b-9a7f-6e8fcfee7ba1/e8d7d336-b592-43ee-941a-5fdcd5a782e0?org_if_sml=1589&amp;force_format=original"
                class="main-comment"/></a>
            <a><img
                alt="flag12017"
                src="/flag12017-9i3-200w.png"
                class="main-image544983200"/></a>
        </div>
    </div>
    <form  class="main-create-post1" action="{{ route('posts.report_post', $post->id) }}" method="POST">
        @csrf
        <div class="main-page-function">
            <span>Report Post</span>   
            <textarea id="post_report_text" name="post_report_text" row="5" class="main-create-post-text @error('post_report_text') is-invalid @enderror" type="text" placeholder="Create Comment"></textarea>
            @error('post_report_text')
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