@extends('layouts.app')
@section('title', 'showing post')
@section('content')
<link href="{{url('css/show.css')}}" rel="stylesheet" />
<div class="post-box">
    <div class="main-post" >
        <div class="main-show">
            <div class="main-frame427320725">
                <div class="main-post-info">
                    <span class="main-text">{{ $post->user->name }}</span>
                    <span class="main-text02">---</span>
                    <span class="main-text04">4 Dec 2020</span>
                    @if ($post->user_id === Auth::user()->id)
                        <div class="rem-element">
                            <button id="removeFriendButton" >Edit Comment</button>
                            <button id="removeFriendButton" >Delete Comment</button>
                        </div>
                    @endif
                </div>
                <span class="main-text06">{{ $post->text }}</span>
            </div>
            <div class="main-react">
                <a>
                <img
                alt="likebutton161993113866172014"
                src="https://aheioqhobo.cloudimg.io/v7/_playground-bucket-v2.teleporthq.io_/437f2a70-6680-454b-9a7f-6e8fcfee7ba1/7174dfe3-8544-4c6c-875e-d0aec2ab361e?org_if_sml=15703&amp;force_format=original"
                class="main-likebutton16199311386617"
                /></a>
                <a href="#" data-toggle="modal" data-target="ModalCreate">
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
        <div class="main-create-comment">
            <form action="{{ route('comments.store', $post->id) }}" method="POST">
                @csrf
                <input type="hidden" name ="post_id" id="post_id" value="{{$post->id}}"/>
                <a href="/profile" class="main-text32">{{Auth::user()->name}}</a>
                <textarea id="commment_text" name="commment_text" row="5" class="main-create-post-text @error('commment_text') is-invalid @enderror" type="text" placeholder="Create Comment"></textarea>
                @error('text')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <div class="main-create-post-frame1">
                    <button class="main-create-post-button" type="submit">
                        <span class="main-create-post-button-text ">create comment</span>
                    </button>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                        <span>{{ $message }}</span>
                        </div>
                    @endif
                </div>
            </form>
        </div>
        @foreach ($comments as $comment)
            @if ($post->id === $comment->post_id)
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
                        alt="likebutton161993113866172014"
                        src="https://aheioqhobo.cloudimg.io/v7/_playground-bucket-v2.teleporthq.io_/437f2a70-6680-454b-9a7f-6e8fcfee7ba1/7174dfe3-8544-4c6c-875e-d0aec2ab361e?org_if_sml=15703&amp;force_format=original"
                        class="main-likebutton16199311386617"
                    /></a>
                    <a href="{{ route('posts.show', $post->id) }}" role="link">
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
            <div>
                
            <script>
                function editComment(commentId) {
                    var commentText = document.getElementById('commentText_' + commentId);
                    var editTextbox = document.getElementById('editTextbox_' + commentId);
                    editTextbox.value = commentText.innerHTML;
                    commentText.style.display = 'none';
                    editTextbox.style.display = 'inline-block';
                }
            </script>
    </div>
</div>
@endsection