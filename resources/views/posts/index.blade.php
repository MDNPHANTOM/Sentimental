@extends('layouts.app')
@section('title', 'All of our products')
@section('content')
@if ($message = Session::get('success'))
  <div class="alert alert-success">
  <p>{{ $message }}</p>
  </div>
@endif
<form action="{{ route('posts.store') }}" method="POST">
  @csrf
    <div class="main-create-post" >
      <a href="/profile" class="main-text32">{{Auth::user()->name}}</a>
      <textarea name="text" class="main-create-post-text @error('text') is-invalid @enderror" id="text" type="text" placeholder="Create Post"></textarea>
      @error('text')
        <div class="alert alert-danger">{{ $message }}</div>
      @enderror
      <div class="main-create-post-frame1">
          <button class="main-create-post-button" type="submit">
            <span class="main-create-post-button-text ">create</span>
          </button>
      </div>
    </div>
</form>
  <div class="main-post" >
    @foreach ($posts as $post)
        <div class="main-info">
          <a href="{{ route('posts.show', $post->id) }}" role="link">
            <div class="main-frame427320725">
              <div class="main-post-info">
                <span class="main-text">{{ $post->user->name }}</span>
                <span class="main-text02">---</span>
                <span class="main-text04">4 Dec 2020</span>
              </div>
              <span class="main-text06">{{ $post->text }}</span>
            </div>
          </a>
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
      @endforeach
      @include('modal.create')
  </div>


@endsection


