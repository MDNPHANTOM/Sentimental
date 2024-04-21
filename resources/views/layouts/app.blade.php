<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    
    <!-- Scripts -->
    @vite('resources/css/app.css')
</head>
<body class="body">
    <div class="main-task">
        @yield('content')
    </div>
    <div class="nav-bar">
        <div class="main-frame427320727">
            <a href="/posts" class="main-text23">Sentimental</a>
            @if (Auth::user()->isAdmin == 1)  
                <a href='/admin' class="main-text23">Users</a>
                <a href='/warnings/flaggedPosts' class="main-text24">Flagged Posts and Comments</a>
                <a href='/warnings/reportedPosts' class="main-text24">Reported Posts and Comments</a>
            @elseif (Auth::user())
                <a href="{{ route('users.liked') }}" class="main-text24">My Liked Posts</a>
            @endif
        </div>
        <div class="main-frame427320729">
            <a class="main-text32">{{Auth::user()->name}}</a>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </div>
    @if (Auth::user()->support_tier > 0)
        <div class="main-mainpage">
            <div class="main-frame-friend">
                <button id="removeFriendButton" class="rem-element">X</button>
                <span class="main-text08">
                    <span>My Friends</span>
                    <br />
                </span>
                <div class="main-friends">
                    <span class="main-text13">
                        <a class="main-text18">Person A</a>
                        <br />
                        <a class="main-text18">Person B</a>
                        <br />
                        <a class="main-text18">Person C</a>
                    </span>
                </div>
            </div>
            <div class="main-frame-ads">
                <button id="removeAdButton" class="rem-element">X</button>
                <span>Advertisement</span>
                        <br />
                <div class="main-ads">
                    <span class="main-text13">
                        <a class="main-text18">Ad 1</a>
                        <br />
                        <a class="main-text18">Ad 2</a>
                        <br />
                        <a class="main-text18">Ad 3</a>
                    </span>
                </div>
            </div>
        </div>
    @endif
    <script>
        // removes ads
        document.getElementById('removeAdButton').addEventListener('click', function() {
            var adDiv = document.querySelector('.main-frame-ads');
            if (adDiv) {
                adDiv.parentNode.removeChild(adDiv);
            }
        });

        document.getElementById('removeFriendButton').addEventListener('click', function() {
            var adDiv = document.querySelector('.main-frame-friend');
            if (adDiv) {
                adDiv.parentNode.removeChild(adDiv);
            }
        });

    </script>

</body>
</html>
