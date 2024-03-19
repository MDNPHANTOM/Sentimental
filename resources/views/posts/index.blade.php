@extends('layouts.app')
@section('title', 'All of our products')
@section('content')
@if ($message = Session::get('success'))
<div class="alert alert-success">
<p>{{ $message }}</p>
</div>
@endif
@foreach ($posts as $post)
<article>
<div class="main-post">
        <div class="main-info">
          <div class="main-frame427320725">
            <div class="main-post-info">
              <img
                alt="Ellipse12014"
                src="/ellipse12014-8f2b.svg"
                class="main-ellipse1"
              />
              <span class="main-text"><span>Username</span></span>
              <span class="main-text02"><span>---</span></span>
              <span class="main-text04"><span>4 Dec 2020</span></span>
            </div>
            <span class="main-text06">
              <span>
                Embrace the chaos of the unknown and dance with uncertainty. Let
                serendipity guide your steps and weave the threads of chance
                into the tapestry of your life. In the dance of existence,
                spontaneity is your partner, and every unexpected twist is a
                chance to discover something new about yourself and the world
                around you. Embrace the adventure, for in the randomness lies
                the beauty of the journey.
              </span>
            </span>
          </div>
          <div class="main-react">
            <img
              alt="likebutton161993113866172014"
              src="https://aheioqhobo.cloudimg.io/v7/_playground-bucket-v2.teleporthq.io_/437f2a70-6680-454b-9a7f-6e8fcfee7ba1/7174dfe3-8544-4c6c-875e-d0aec2ab361e?org_if_sml=15703&amp;force_format=original"
              class="main-likebutton16199311386617"
            />
            <img
              alt="comment2014"
              src="https://aheioqhobo.cloudimg.io/v7/_playground-bucket-v2.teleporthq.io_/437f2a70-6680-454b-9a7f-6e8fcfee7ba1/e8d7d336-b592-43ee-941a-5fdcd5a782e0?org_if_sml=1589&amp;force_format=original"
              class="main-comment"
            />
            <img
              alt="save12015"
              src="https://aheioqhobo.cloudimg.io/v7/_playground-bucket-v2.teleporthq.io_/437f2a70-6680-454b-9a7f-6e8fcfee7ba1/468f9d8e-41ac-479b-bd56-c5ebe3902f6a?org_if_sml=11373&amp;force_format=original"
              class="main-save1"
            />
            <img
              alt="IMAGE5449832002014"
              src="https://aheioqhobo.cloudimg.io/v7/_playground-bucket-v2.teleporthq.io_/437f2a70-6680-454b-9a7f-6e8fcfee7ba1/5d2245cc-19f3-443a-aab3-ede589c81328?org_if_sml=11070&amp;force_format=original"
              class="main-image544983200"
            />
          </div>
        </div>
      </div>
</article>
@endforeach
{{ $posts->links() }}
@endsection


