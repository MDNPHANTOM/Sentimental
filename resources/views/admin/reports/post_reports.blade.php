@extends('layouts.app')
@section('title', 'Post Report')
@section('content')
<div class="main-holder">
    <div class="main-report_post1" >
        <div class="main-info1">
            <div class="main-frame427320725">
                <div class="main-post-info">
                    <span class="main-text">{{ $user->name }}</span>
                    <span class="main-text02">---</span>
                    <div class='rem-element'>
                        <a href="{{ route('admin.reported_posts', $user->id) }}"><button>Show User Reports</button></a>
                        <a href="{{ route('admin.flagged_posts', $user->id) }}"><button>Show User Flags</button></a>
                    </div>
                </div>   
                <span class="main-text04">Flaged: {{ $user->concerns }} </span> 
                <span class="main-text04">Reported: {{ $user->reported }} </span>
                <form class="main-post-info" action="{{ route('admin.set_user_support', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <span class="main-text04">Support Tier: {{ $user->support_tier }} </span>
                    <input name="support_tier"  type="number"  min="0" max="3" required>
                    <button type="submit">Set Support:</button>
                </form>
                
                <form class="user-blocked-{{$user->blocked ? 'danger' : 'success'}}" action="{{ route('admin.block_user', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button class="user-blocked-{{$user->blocked ? 'danger' : 'success'}}" type="submit">Blocked:  {{$user->blocked ? 'Enabled' : 'Disabled'}}</button>
                </form>
            </div>
        </div>
    </div>
    <div class="main-report_post1" >
          <div class="main-info4">
            <div class="main-frame427320725">
                <span>Reported Post</span>
                <div class="main-post-info">
                    <span class="main-text">{{ $post->user->name }}</span>
                    <span class="main-text02">---</span>
                    <span class="main-text04">{{$post->created_at->format('Y-m-d H:i:s')}}</span>
                    @if (Auth::user()->isAdmin == 1)
                        <div class="rem-element">
                            <form action="{{ route('posts.post_destroy', $post->id) }}" method="POST">
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
                    @if($post->post_reports > 0)
                        <div class="rem-element">
                            <a href="{{ route('reports.post_report', [$post->user_id, $post->id]) }}">
                                <button type="button">Show Post Reports</button>
                            </a>
                        </div>
                    @endif
                </div>               
                <span class="main-text06">{{ $post->text }}</span>
            </div>
            <div class="main-react">
                <a href="{{ route('posts.show', $post->id) }}" role="link">
                <img
                    alt="comment2014"
                    src="https://aheioqhobo.cloudimg.io/v7/_playground-bucket-v2.teleporthq.io_/437f2a70-6680-454b-9a7f-6e8fcfee7ba1/e8d7d336-b592-43ee-941a-5fdcd5a782e0?org_if_sml=1589&amp;force_format=original"
                    class="main-comment"
                /></a>
                <a href="{{ route('posts.report', $post->id) }}"><img
                    alt="flag12017"
                    src="/flag12017-9i3-200w.png"
                    class="main-image544983200"/></a>
            </div>
        </div>

        <div class="main-info2">
            <span>Reports of the Reported Post</span>
            @foreach ($postreports as $postreport)
                <div class="main-info">
                    <div class="main-frame427320725">
                        <div class="main-post-info">
                            <span class="main-text">{{ $postreport->user->name }}</span>
                            <span class="main-text02">---</span>
                            <span class="main-text04">{{$postreport->created_at->format('Y-m-d H:i:s')}}</span>
                            @if (Auth::user()->isAdmin == 1)
                            <div class="rem-element">
                                <form action="{{ route('reports.delete_post_report', ['report' => $postreport->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">Delete Report for Post</button>
                                </form>
                            </div>
                            @endif
                        </div>
                        <span class="main-text06">{{ $postreport->post_report_text }}</span>
                    </div>
                </div>
            @endforeach
            <div class="nextpage">
                {{ $postreports->links('pagination::bootstrap-4') }}
            </div>
        </div>

    </div>
</div>
@endsection