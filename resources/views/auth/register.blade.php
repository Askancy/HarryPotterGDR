        <form method="POST" action="{{ route('register') }}" aria-label="{{ __('Register') }}">
          @csrf

          <div class="form-group row">
            <label for="username" class="col-md-4 col-form-label text-md-right">{{ __('Username') }}</label>

            <div class="col-md-6">
              <input id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username') }}" required autofocus>

              @if ($errors->has('username'))
              <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('username') }}</strong>
              </span>
              @endif
            </div>
          </div>

          <hr>

          <div class="form-group row">
            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nome') }}</label>

            <div class="col-md-6">
              <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('username') }}" required autofocus>

              @if ($errors->has('name'))
              <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('name') }}</strong>
            </span>
              @endif
            </div>
          </div>

          <div class="form-group row">
            <label for="surname" class="col-md-4 col-form-label text-md-right">{{ __('Cognome') }}</label>

            <div class="col-md-6">
              <input id="surname" type="text" class="form-control{{ $errors->has('surname') ? ' is-invalid' : '' }}" name="surname" value="{{ old('surname') }}" required autofocus>

              @if ($errors->has('surname'))
              <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('surname') }}</strong>
            </span>
              @endif
            </div>
          </div>

          <div class="form-group row">

            <label for="surname" class="col-md-4 col-form-label text-md-right">{{ __('Sesso') }}</label>
            <div class="col-md-6">
              <select name="sex" class="form-control">
                  <option value="1" selected> Maschio </option>
                  <option value="2"> Femmina </option>
                </select>
              @if ($errors->has('sex'))
              <span class="invalid-feedback" role="alert">
              <strong>{{ $errors->first('sex') }}</strong>
          </span>
              @endif
            </div>
          </div>

          <div class="form-group row {{ $errors->has('birthday') ? 'has-error' : '' }}">
            {!! Form::label('birthday', 'Data di nascita', ['class' => 'col-md-4 col-form-label text-md-right']) !!}
            <div class="col-md-8">
              <div class="form-inline">
                <input id="datepicker" class="form-control" name="birthday" />
                <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/gijgo@1.9.9/js/gijgo.min.js"></script>
                <script>
                  $('#datepicker').datepicker({
                    uiLibrary: 'bootstrap4',
                    format: 'yyyy-mm-dd'
                  });
                </script>
              </div>
              {{ $errors->first('birthday', '<span class="help-block">:message</span>') }}
            </div>
          </div>

          <hr>

          <div class="form-group row">
            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Email') }}</label>
            <div class="col-md-6">
              <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>
              @if ($errors->has('email'))
              <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('email') }}</strong>
              </span>
              @endif
            </div>
          </div>

          <div class="form-group row">
            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

            <div class="col-md-6">
              <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

              @if ($errors->has('password'))
              <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('password') }}</strong>
              </span>
              @endif
            </div>
          </div>

          <div class="form-group row">
            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Conferma Password') }}</label>

            <div class="col-md-6">
              <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
            </div>
          </div>

          <p class="text-center">La casata del giocatore sarà scelta casualmente dal sistema di gioco</p>

          <div class="form-group row mb-0">
            <div class="col-md-6 offset-md-4">
              <button type="submit" class="btn btn-dark">{{ __('Registrati') }}</button>
            </div>
          </div>
        </form>
