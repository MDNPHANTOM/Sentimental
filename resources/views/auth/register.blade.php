<link href="{{url('css/sign-up.css')}}" rel="stylesheet" />
@vite('resources/css/sign-up.css')
<div class="sign-up-container">
  <div class="sign-up-sign-up">
    <div class="sign-up-form">
      <span class="sign-up-text"><span>Sign Up</span></span>
      <form class="sign-up-frame427320715" method="POST" action="{{ route('register') }}">
        @csrf
        <div class="sign-up-frame427320714">
          <div class="sign-up-frame427320713">
            <div class="sign-up-frame427320712">
              <div class="sign-up-frame427320710">
                <input class="sign-up-frame427320708" id="name" type="text" placeholder="Enter Name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <input class="sign-up-frame427320708" id="email" type="email" placeholder="Enter Email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <input class="sign-up-frame427320709" id="password" type="password" placeholder="Enter Password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <input class="sign-up-frame427320707" id="password-confirm" type="password" placeholder="Repeat Password" class="form-control" name="password_confirmation" required autocomplete="new-password">
              
              </div>
              <button  class="sign-up-frame427320711" type="submit" class="btn btn-primary">                  
                <span class="sign-up-text08"><span>Sign Up</span></span>
              </button>
            </div>
          </div>

        </div>
        <div class="sign-up-frame427320718">
          <span class="sign-up-text10"><span>Have an account?</span></span>
          <a href="{{ route('login') }}" class="log-in-text12">Log In</a>
        </div>
      </div>
    </form>
  </div>
</div>