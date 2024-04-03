@extends('layouts.app')
@section('title', 'showing users')
@section('content')
    @foreach ($posts as $post)
        <div class="main-post-info">
            <span class="main-text">{{ $user->name }}</span>
            <span class="main-text02">---</span>
            <span class="main-text04">4 Dec 2020</span>
            @if ( Auth::user('Admin'))
                <div class="rem-element">
                    <form action="{{ route('users.block', $post->id) }}" method="POST">
                    <a href="{{ route('posts.edit', $post->id) }}"><button type="button">Edit Post</button></a>
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete Post</button>
                    </form>
                </div>
            @endif
        </div>

    @endforeach
@endsection