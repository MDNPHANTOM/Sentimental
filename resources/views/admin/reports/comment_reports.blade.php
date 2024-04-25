@extends('layouts.app')
@section('title', 'Comment Report')
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
                <div class="main-post-info">
                    <span class="main-text04">Flaged: {{ $user->concerns }} </span> 
                    <span class="main-text04">Reported: {{ $user->reported }} </span>
                </div>
                <div class="main-post-info">
                    <span class="main-text04">Total Negative: {{ $user->score_neg  }} </span>
                    <span class="main-text04">Total Positive: {{ $user->score_pos }} </span> 
                </div>
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
        <div class="">
        <div class="main-info">
            <div class="main-frame427320725">
                <div class="main-post-info">
                    <a href="{{ route('admin.reported_posts', $post->user_id) }}" class="main-text">{{ $post->user->name }}</a>
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
                    <span class="main-text04">Concern Score: {{$post->concern_score}}</span>
                    <span class="main-text04">Concern: {{$post->concern}}</span>
                    <span class="main-text04">Reports: {{$post->post_reports}}</span>
                    @if($post->post_reports > 0)
                        <div class="rem-element">
                            <a href="{{ route('reports.post_report', [$post->user_id, $post->id]) }}?{{ time() }}">
                                <button type="button">Show Post Reports</button>
                            </a>
                        </div>
                    @endif
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
                    class="main-comment"
                /></a>
                <a href="{{ route('posts.report', $post->id) }}"><img
                    alt="flag12017"
                    src="{{ asset('images/flag.png') }}"
                    class="main-image544983200"/></a>
            </div>
        </div>

            <div id="reportedComments" class="main-info3">
                <span>Comments around the Reported Comment</span>
                @foreach ($comments as $comment)
                    @if( $comment == $target_comment)
                        @if($commentreports->isEmpty())
                            <div class="main-info">
                        @else
                            <div class="main-info4">
                        @endif  
                    @else
                        <div class="main-info">
                    @endif          
                        <div class="main-frame427320725">
                            <div class="main-post-info">
                                <a href="{{ route('admin.reported_comments', $comment->user_id) }}" class="main-text">{{ $comment->user->name }}</a>
                                <span class="main-text02">---</span>
                                <span class="main-text04">{{$comment->created_at->format('Y-m-d H:i:s')}}</span>
                                @if (Auth::user()->isAdmin == 1)
                                    <div class="rem-element">
                                        <form action="{{ route('comments.comment_destroy', $comment->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit">Delete Comment</button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                            <div class="main-post-info">
                                <span class="main-text04">Concern Score: {{$comment->concern_score}}</span>
                                <span class="main-text04">Concern: {{$comment->concern}}</span>
                                <span class="main-text04">Reports: {{$comment->comment_reports}}</span>
                                @if($comment->comment_reports > 0)
                                    <div class="rem-element">
                                        <a href="{{ route('reports.comment_report', [$comment->user_id, $comment->id]) }}?{{ time() }}">
                                            <button type="button">Show Comment Reports</button>
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <div class="main-frame427320700">
                                <div class="main-post-info1">
                                    <span class="main-text04">OVERALL EVAL:</span>
                                    <span class="main-text04">Compound: {{$comment->compound}}</span>
                                    <span class="main-text04">Neutral: {{$comment->neutral}}</span>
                                    <span class="main-text04">Positive: {{$comment->positive}}</span>
                                    <span class="main-text04">Negative: {{$comment->negative}}</span>
                                    <span></span>
                                </div>
                                <div class="main-post-info1">
                                    <span class="main-text04">NEGATIVE EMOTION:</span>
                                    <span class="main-text04">Fear: {{$comment->fear}}</span>
                                    <span class="main-text04">Anger: {{$comment->anger}}</span>
                                    <span class="main-text04">Sadness: {{$comment->sadness}}</span>
                                    <span class="main-text04">Disgust: {{$comment->disgust}}</span>
                                    <span></span>
                                </div>
                                <div class="main-post-info1">
                                    <span class="main-text04">POSITIVE EMOTION:</span>
                                    <span class="main-text04">Joy: {{$comment->joy}}</span>
                                    <span class="main-text04">Trust: {{$comment->trust}}</span>
                                    <span class="main-text04">Surprise: {{$comment->surprise}}</span>
                                    <span class="main-text04">Anticipation: {{$comment->anticipation}}</span>
                                    <span></span>
                                </div>
                            </div>
                            <span class="main-text06">{{ $comment->comment_text }}</span>
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
                                src="{{ asset('images/flag.png') }}"
                                class="main-image544983200"/></a>
                        </div>
                    </div>
                
                @endforeach
                <div class="nextpage">
                    {{ $comments->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
    <div class="main-report_post1" >
    @if($commentreports->isEmpty())
        <div class="main-info">
    @else
        <div class="main-info4">
    @endif     
            <div class="main-frame427320725">
                <span>Reported Comment</span>
                <div class="main-post-info">
                    <a href="{{ route('admin.reported_comments', $comment->user_id) }}" class="main-text">{{ $target_comment->user->name }}</a>
                    <span class="main-text02">---</span>
                    <span class="main-text04">{{$target_comment->created_at->format('Y-m-d H:i:s')}}</span>
                    @if (Auth::user()->isAdmin == 1)
                        <div class="rem-element">
                            <form action="{{ route('comments.comment_destroy', $target_comment->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Delete Comment</button>
                            </form>
                        </div>
                    @endif
                </div>
                <div class="main-post-info">
                    <span class="main-text04">Concern Score: {{$target_comment->concern_score}}</span>
                    <span class="main-text04">Concern: {{$target_comment->concern}}</span>
                    <span class="main-text04">Reports: {{$target_comment->comment_reports}}</span>
                    @if($target_comment->comment_reports > 0)
                        <div class="rem-element">
                            <a href="{{ route('reports.comment_report', [$comment->user_id, $target_comment->id]) }}?{{ time() }}">
                                <button type="button">Show Comment Reports</button>
                            </a>
                        </div>
                    @endif
                </div> 
                <div class="main-frame427320700">
                    <div class="main-post-info1">
                        <span class="main-text04">OVERALL EVAL:</span>
                        <span class="main-text04">Compound: {{$target_comment->compound}}</span>
                        <span class="main-text04">Neutral: {{$target_comment->neutral}}</span>
                        <span class="main-text04">Positive: {{$target_comment->positive}}</span>
                        <span class="main-text04">Negative: {{$target_comment->negative}}</span>
                        <span></span>
                    </div>
                    <div class="main-post-info1">
                        <span class="main-text04">NEGATIVE EMOTION:</span>
                        <span class="main-text04">Fear: {{$target_comment->fear}}</span>
                        <span class="main-text04">Anger: {{$target_comment->anger}}</span>
                        <span class="main-text04">Sadness: {{$target_comment->sadness}}</span>
                        <span class="main-text04">Disgust: {{$target_comment->disgust}}</span>
                        <span></span>
                    </div>
                    <div class="main-post-info1">
                        <span class="main-text04">POSITIVE EMOTION:</span>
                        <span class="main-text04">Joy: {{$target_comment->joy}}</span>
                        <span class="main-text04">Trust: {{$target_comment->trust}}</span>
                        <span class="main-text04">Surprise: {{$target_comment->surprise}}</span>
                        <span class="main-text04">Anticipation: {{$target_comment->anticipation}}</span>
                        <span></span>
                    </div>
                </div>              
                <span class="main-text06">{{ $target_comment->comment_text }}</span>
            </div>
            <div class="main-react">
                <a href="{{ route('comments.show', $target_comment->id) }}" role="link">
                <img
                    alt="comment2014"
                    src="https://aheioqhobo.cloudimg.io/v7/_playground-bucket-v2.teleporthq.io_/437f2a70-6680-454b-9a7f-6e8fcfee7ba1/e8d7d336-b592-43ee-941a-5fdcd5a782e0?org_if_sml=1589&amp;force_format=original"
                    class="main-comment"
                /></a>
                <a href="{{ route('comments.report', $target_comment->id) }}"><img
                    alt="flag12017"
                    src="{{ asset('images/flag.png') }}"
                    class="main-image544983200"/></a>
            </div>
        </div>
        <div class="main-info2">
            <span>Reports of the Reported Comment</span>
            @foreach ($commentreports as $commentreport)
                <div class="main-info">
                    <div class="main-frame427320725">
                        <div class="main-post-info">
                            <a href="{{ route('admin.reported_comments', $commentreport->user_id) }}" class="main-text">{{ $commentreport->user->name }}</a>
                            <span class="main-text02">---</span>
                            <span class="main-text04">{{$commentreport->created_at->format('Y-m-d H:i:s')}}</span>
                            @if (Auth::user()->isAdmin == 1)
                            <div class="rem-element">
                                <form action="{{ route('reports.delete_comment_report', ['report' => $commentreport->id]) }}?{{ time() }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">Delete Report for Comment</button>
                                </form>
                            </div>
                            @endif
                        </div>
                        <span class="main-text06">{{ $commentreport->comment_report_text }}</span>
                    </div>
                </div>
            @endforeach
            <div class="nextpage">
                {{ $comments->links('pagination::bootstrap-4') }}
            </div>
        </div>

    </div>
</div>
@endsection