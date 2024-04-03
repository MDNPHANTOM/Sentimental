@extends('layouts.app')
@section('title', 'flaggedPosts')
@section('content')
<link href="{{url('css/app.css')}}" rel="stylesheet" />
<div class="main-post" >
    <button id="toggleButton">Reveal Posts and Comments</button>
    <div id="flaggedPosts" class="">
        <span>Posts</span>
        @foreach ($posts as $post)
            @if($post->concern == 1)
                <div class="main-info">
                    <div class="main-frame427320725">
                    <div class="admin-frame427320730">
                    <img
                        alt="concern22017"
                        src="/concern22017-ihg-200h.png"
                        class="admin-concern2"
                    />
                    <img
                        alt="blockuser22017"
                        src="/blockuser22017-mk3c-200h.png"
                        class="admin-blockuser2"
                    />
                    </div>
                    <div class="main-post-info">
                        <span class="main-text">{{ $post->user->name }}</span>
                        <span class="main-text02">---</span>
                        <span class="main-text04">4 Dec 2020</span>
                        @if (Auth::user()->isAdmin == 1)
                        <span class="main-text04">Concern: {{$post->concern}}</span>
                        @endif
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
                    alt="save12015"
                    src="https://aheioqhobo.cloudimg.io/v7/_playground-bucket-v2.teleporthq.io_/437f2a70-6680-454b-9a7f-6e8fcfee7ba1/468f9d8e-41ac-479b-bd56-c5ebe3902f6a?org_if_sml=11373&amp;force_format=original"
                    class="main-save1"/></a>
                    <a><img
                        alt="flag12017"
                        src="/flag12017-9i3-200w.png"
                        class="main-image544983200"/></a>
                </div>
                </div>
            @endif
        @endforeach
        <div class="nextpage">
            {{ $posts->links('pagination::bootstrap-4') }}
        </div>
    </div>
    <div id="flaggedComments" class="hidden">
        <span>Posts</span>
        @foreach ($comments as $comment)
            @if($comment->concern == 1)
                <div class="main-info">
                    <div class="main-frame427320725">
                        <div class="main-post-info">
                            <span class="main-text">{{ $comment->user->name }}</span>
                            <span class="main-text02">---</span>
                            <span class="main-text04">4 Dec 2020</span>
                            @if (Auth::user()->isAdmin == 1)
                                <span class="main-text04">Concern: {{$comment->concern}}</span>
                            @endif
                            @if ((Auth::user()->isAdmin == 1))
                            <div class="rem-element">
                                <form action="{{ route('comments.destroy', $comment->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                <button type="submit">Delete Comment</button>
                            </form>
                            <div class="rem-element">
                                <a href="{{ route('posts.show', $comment->post_id) }}"><button type="button">Show Linked Post</button></a>
                            </div>
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
                    <div class="main-react">

                    <a href="{{ route('comments.show', $comment->id) }}" role="link">
                    <img
                        alt="comment2014"
                        src="https://aheioqhobo.cloudimg.io/v7/_playground-bucket-v2.teleporthq.io_/437f2a70-6680-454b-9a7f-6e8fcfee7ba1/e8d7d336-b592-43ee-941a-5fdcd5a782e0?org_if_sml=1589&amp;force_format=original"
                        class="main-comment"
                    /></a>
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
                @endif
            @endforeach
            <div class="nextpage">
                {{ $comments->links('pagination::bootstrap-4') }}
            </div>
    </div>
</div>

<script>
    const toggleButton = document.getElementById('toggleButton');
    const flaggedPosts = document.getElementById('flaggedPosts');
    const flaggedComments = document.getElementById('flaggedComments');

    console.log(toggleButton, flaggedPosts, flaggedComments); // Check if elements are selected

    document.getElementById('toggleButton').addEventListener('click', function() {
        console.log('Button clicked'); // Check if the event listener is triggered
        if (flaggedPosts.classList.contains('hidden')) {
            // Show flagged posts and hide flagged comments
            flaggedPosts.classList.remove('hidden');
            flaggedComments.classList.add('hidden');
        } else {
            // Show flagged comments and hide flagged posts
            flaggedPosts.classList.add('hidden');
            flaggedComments.classList.remove('hidden');
        }
    });

</script>
@endsection