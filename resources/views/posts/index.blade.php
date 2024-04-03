@extends('layouts.app')
@section('title', 'All of our Posts')
@section('content')
<link href="{{url('css/app.css')}}" rel="stylesheet" />
<link href="path/to/bootstrap-pagination.min.css" rel="stylesheet">
    <div class="main-post" >
      <form  class="main-create-post" action="{{ route('posts.store') }}" method="POST">
        @csrf
          <a href="/profile" class="main-text32">{{Auth::user()->name}}</a>
          <textarea name="text" class="main-create-post-text @error('text') is-invalid @enderror" id="text" type="text" placeholder="Create Post"></textarea>
          @error('text')
            <div class="alert alert-danger">{{ $message }}</div>
          @enderror
          <div class="main-create-post-frame1">
              <button class="main-create-post-button" type="submit">
                <span class="main-create-post-button-text ">create</span>
              </button>
              @if ($message = Session::get('success'))
                <div class="alert alert-success">
                  <span>{{ $message }}</span>
                </div>
              @endif
          </div>
      </form>
      @foreach ($posts as $post)
        <div class="main-info">
            <div class="main-frame427320725">
            <div class="admin-frame427320730">
              <img
                alt="flag12017"
                src="/flag12017-9i3-200w.png"
                class="admin-flag1"
              />
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
                @if ($post->user_id === Auth::user()->id)
                    <div class="rem-element">
                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST">
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
              alt="save12015"
              src="https://aheioqhobo.cloudimg.io/v7/_playground-bucket-v2.teleporthq.io_/437f2a70-6680-454b-9a7f-6e8fcfee7ba1/468f9d8e-41ac-479b-bd56-c5ebe3902f6a?org_if_sml=11373&amp;force_format=original"
              class="main-save1"/></a>
              <a><img
                  alt="flag12017"
                  src="/flag12017-9i3-200w.png"
                  class="main-image544983200"/></a>
          </div>
          </div>
      @endforeach
      <div class="nextpage">
        {{ $posts->links('pagination::bootstrap-4') }}
      </div>
          
    </div>
  <script>
    // Function to check if user has scrolled to the bottom of the page
    function isBottomReached() {
      return window.innerHeight + window.scrollY >= document.body.offsetHeight;
    }

    // Function to load more content
    function loadMoreContent() {
      // Simulated AJAX request to fetch more content
      setTimeout(function() {
        // Append new content
        var newContent = document.createElement('div');
        newContent.classList.add('post');
        newContent.textContent = 'New Post';
        document.getElementById('main-post').appendChild(newContent);
      }, 1000); // Simulated delay
    }

    // Event listener for scrolling
    window.addEventListener('scroll', function() {
      // If user has scrolled to the bottom, load more content
      if (isBottomReached()) {
        loadMoreContent();
      }
    });

    // Load initial content
    loadMoreContent();
  </script>


@endsection


