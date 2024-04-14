@extends('layouts.app')
@section('title', 'showing users')
@section('content')
<div class="main-post" >
    @foreach ($users as $user)
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
    @endforeach
</div>
@endsection


