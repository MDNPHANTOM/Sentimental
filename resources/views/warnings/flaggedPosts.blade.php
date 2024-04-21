@extends('layouts.app')
@section('title', 'flaggedPosts')
@section('content')
<div class="main-post" >
    <div id="flaggedPosts">
        <div class="main-info2">
            <span>Flagged Posts</span>
            <a href="{{ route('comments_flagged') }}"><button>Show All Flagged Comments</button></a>
            @foreach ($posts as $post)        
                <div class="main-info">
                    <div class="main-frame427320725">
                        <div class="main-post-info">
                            <a href="{{ route('admin.flagged_posts', $post->user_id) }}" class="main-text">{{ $post->user->name }}</a>
                            <span class="main-text02">---</span>
                            <span class="main-text04">4 Dec 2020</span>
                            @if (Auth::user()->isAdmin == 1)
                                <div class="rem-element">
                                    <form action="{{ route('posts.destroy', $post->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit">Delete Post</button>
                                    </form>

                                </div>
                            @endif
                        </div>
                        <div class="main-post-info">
                            <span class="main-text04">Concern: {{$post->concern}}</span>
                            <span class="main-text04">Reports: {{$post->post_reports}}</span>
                        </div>
                        <div class="main-frame427320700">
                            <div class="main-post-info1">
                                <span class="main-text04">OVERALL EVAL:</span>
                                <span class="main-text04">Compound: {{$post->compound}}</span>
                                <span class="main-text04">Neutral: {{$post->neutral}}</span>
                                <span class="main-text04">Positive: {{$post->positive}}</span>
                                <span class="main-text04">Negative: {{$post->negative}}</span>
                                <span></span>
                            </div>
                            <div class="main-post-info1">
                                <span class="main-text04">NEGATIVE EMOTION:</span>
                                <span class="main-text04">Fear: {{$post->fear}}</span>
                                <span class="main-text04">Anger: {{$post->anger}}</span>
                                <span class="main-text04">Sadness: {{$post->sadness}}</span>
                                <span class="main-text04">Disgust: {{$post->disgust}}</span>
                                <span></span>
                            </div>
                            <div class="main-post-info1">
                                <span class="main-text04">POSITIVE EMOTION:</span>
                                <span class="main-text04">Joy: {{$post->joy}}</span>
                                <span class="main-text04">Trust: {{$post->trust}}</span>
                                <span class="main-text04">Surprise: {{$post->surprise}}</span>
                                <span class="main-text04">Anticipation: {{$post->anticipation}}</span>
                                <span></span>
                            </div>
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
                        <a href="{{ route('posts.report', $post->id) }}"><img
                            alt="flag12017"
                            src="{{ asset('images/flag.png') }}"
                            class="main-image544983200"/></a>
                    </div>
                </div>
            @endforeach
            <div class="nextpage">
                {{ $posts->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection