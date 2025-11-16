@extends('front.layouts.app')

@section('title', 'Registrati')

@section('styles')
<style>
.register-container {
    max-width: 700px;
    margin: 50px auto;
    padding: 30px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
}
.register-header {
    text-align: center;
    margin-bottom: 30px;
}
.register-header h2 {
    color: #2c3e50;
    font-weight: bold;
}
.btn-primary {
    width: 100%;
    padding: 12px;
    font-size: 16px;
}
.section-divider {
    margin: 25px 0;
    border-top: 2px solid #e9ecef;
}
.info-box {
    background: #f8f9fa;
    padding: 15px;
    border-left: 4px solid #007bff;
    margin-bottom: 20px;
}
</style>
@endsection

@section('content')
<div class="container">
    <div class="register-container">
        <div class="register-header">
            <h2>Benvenuto a Hogwarts</h2>
            <p class="text-muted">Compila il modulo per iscriverti alla scuola di magia</p>
        </div>

        <div class="info-box">
            <strong>Nota:</strong> Il Cappello Parlante determinerà la tua casa dopo la registrazione!
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Account Information -->
            <h5 class="mb-3">Informazioni Account</h5>

            <div class="form-group">
                <label for="username">Username <span class="text-danger">*</span></label>
                <input id="username" type="text"
                       class="form-control @error('username') is-invalid @enderror"
                       name="username"
                       value="{{ old('username') }}"
                       required
                       autofocus>
                @error('username')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email <span class="text-danger">*</span></label>
                <input id="email" type="email"
                       class="form-control @error('email') is-invalid @enderror"
                       name="email"
                       value="{{ old('email') }}"
                       required>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password <span class="text-danger">*</span></label>
                <input id="password" type="password"
                       class="form-control @error('password') is-invalid @enderror"
                       name="password"
                       required>
                <small class="form-text text-muted">Minimo 6 caratteri</small>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password-confirm">Conferma Password <span class="text-danger">*</span></label>
                <input id="password-confirm" type="password"
                       class="form-control"
                       name="password_confirmation"
                       required>
            </div>

            <div class="section-divider"></div>

            <!-- Personal Information -->
            <h5 class="mb-3">Informazioni Personali</h5>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Nome <span class="text-danger">*</span></label>
                        <input id="name" type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               name="name"
                               value="{{ old('name') }}"
                               required>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="surname">Cognome <span class="text-danger">*</span></label>
                        <input id="surname" type="text"
                               class="form-control @error('surname') is-invalid @enderror"
                               name="surname"
                               value="{{ old('surname') }}"
                               required>
                        @error('surname')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="sex">Sesso <span class="text-danger">*</span></label>
                        <select name="sex" id="sex"
                                class="form-control @error('sex') is-invalid @enderror"
                                required>
                            <option value="">Seleziona...</option>
                            <option value="M" {{ old('sex') == 'M' ? 'selected' : '' }}>Maschio</option>
                            <option value="F" {{ old('sex') == 'F' ? 'selected' : '' }}>Femmina</option>
                        </select>
                        @error('sex')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="birthday">Data di nascita <span class="text-danger">*</span></label>
                        <input id="datepicker" type="text"
                               class="form-control @error('birthday') is-invalid @enderror"
                               name="birthday"
                               value="{{ old('birthday') }}"
                               placeholder="yyyy-mm-dd"
                               required>
                        @error('birthday')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group mt-4">
                <button type="submit" class="btn btn-primary">
                    Registrati e vai allo smistamento
                </button>
            </div>

            <hr>

            <div class="text-center">
                <p>Hai già un account? <a href="{{ route('login') }}">Accedi qui</a></p>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/gijgo@1.9.9/js/gijgo.min.js"></script>
<script>
    $('#datepicker').datepicker({
        uiLibrary: 'bootstrap4',
        format: 'yyyy-mm-dd'
    });
</script>
@endsection
