<form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}" id="loginStyle" class="justify-content-center">
  <div class="col-12">
    <h3>Accedi</h3>
  </div>

  @csrf


  <div class="form-group col-12">
      <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Email') }}</label>

      <div class="col-md-6">
        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>
        @if ($errors->has('email'))
        <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('email') }}</strong>
                </span>
        @endif
        </div>
  </div>

  <div class="form-group col-12">
    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

    <div class="col-md-6">
      <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" value="{{ old('Password') }}" required autofocus>

      @if ($errors->has('password'))
      <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('password') }}</strong>
              </span>
      @endif
    </div>
  </div>

  <div class="col-md-5 offset-md-2">
    <label for="remember" class="col-6">{{__('Ricordami')}}</label>
    <input class="form-checkbox-input2" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} />
  </div>

  <div class="col-md-6 offset-md-4">
    <button type="submit" class="btn btn-dark">
      {{ __('Accedi') }}
    </button>
  </div>

  <hr>
  <div class="col-md-7 offset-md-3">
    <a class="btn btn-link" href="{{ route('password.request') }}">
        {{ __('Password dimenticata?') }}
    </a>
  </div>


</form>
