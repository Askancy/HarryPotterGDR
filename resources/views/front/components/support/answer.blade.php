<form method="POST" action="#" aria-label="{{ __('Answer') }}" class="justify-content-center">
  @csrf

  <div class="form-group">
    <h3>Rispondi velocemente</h3>
  </div>

    <div class="form-group">
      <textarea rows="10" class="form-control{{ $errors->has('text') ? ' is-invalid' : '' }}" name="text" placeholder="Inizia a scrivere..." required></textarea>
    </div>

    @if ($errors->has('text'))
    <span class="invalid-feedback" role="alert">
              <strong>{{ $errors->first('text') }}</strong>
            </span>
    @endif

    <div class="form-group">
      <button class="btn btn-dark" type="submit">
        {{__('Rispondi')}}
      </button>
    </div>

  </div>
</form>
