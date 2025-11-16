@extends('front.layouts.app')

@section('title', 'Accedi')

@section('styles')
<style>
.login-container {
    max-width: 500px;
    margin: 50px auto;
    padding: 30px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
}
.login-header {
    text-align: center;
    margin-bottom: 30px;
}
.login-header h2 {
    color: #2c3e50;
    font-weight: bold;
}
.btn-primary {
    width: 100%;
    padding: 12px;
    font-size: 16px;
}
</style>
@endsection

@section('content')
<div class="container">
    <div class="login-container">
        <div class="login-header">
            <h2>Accedi a Hogwarts</h2>
            <p class="text-muted">Inserisci le tue credenziali per accedere</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email"
                       class="form-control @error('email') is-invalid @enderror"
                       name="email"
                       value="{{ old('email') }}"
                       required
                       autofocus>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password"
                       class="form-control @error('password') is-invalid @enderror"
                       name="password"
                       required>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">
                        Ricordami
                    </label>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    Accedi
                </button>
            </div>

            <div class="text-center mt-3">
                <a href="{{ route('password.request') }}">Password dimenticata?</a>
            </div>

            <hr>

            <div class="text-center">
                <p>Non hai un account? <a href="{{ route('register') }}">Registrati qui</a></p>
            </div>
        </form>
    </div>
</div>
@endsection
