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
                 <span class="main-text02">Follows: </span>
              </div>  

              @if (auth()->user()->follows($user))
                  <form action="{{ route('users.unfollow', $user) }}" method="POST">
                      @csrf
                      @method('DELETE')
                      <button type="submit">Unfollow</button>
                  </form>
              @else
                  <form action="{{ route('users.follow', $user) }}" method="POST">
                      @csrf
                      <button type="submit">Follow</button>
                  </form>
              @endif
            </div>
        </div>
    @endforeach
</div>
@endsection


