<link href="{{url('css/log-in.css')}}" rel="stylesheet" />
@vite('resources/css/log-in.css')
  <div class="log-in-container">
    <div class="log-in-log-in">
      <div class="log-in-form">
        <span class="log-in-text"><span>Sentimental Login</span></span>
        <div class="log-in-frame427320715">
          <form class="log-in-frame427320714" method= "POST" action="{{route('login')}}">
            @csrf
            <div class="log-in-frame427320713">
              <div class="log-in-frame427320712">
                <div class="log-in-frame427320710">
                    <input class="log-in-frame427320708" id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Enter Email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <input class="log-in-frame427320707" id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Enter Password" required autocomplete="current-password">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <button class="log-in-frame427320711" type="submit">
                  <span class="log-in-text06"><span>Log In</span></span>
                </button>
              </div>
              <div class="log-in-frame3">
                @if (Route::has('password.request'))
                    <a class="btn btn-link" href="{{ route('password.request') }}">
                        <span class="log-in-text08"><span>Forgot password?</span></span>
                    </a>
                @endif
              </div>
            </div>
            <div class="log-in-frame427320706">
              <div class="log-in-frame5">
                <img class="log-in-line48"/>
                <span class="log-in-text10"><span>or</span></span>
                <img class="log-in-line49"/>
              </div>
              <div class="log-in-frame427320718">
                <a class="sign-up-text12" href="{{ route('register') }}">Sign Up</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
