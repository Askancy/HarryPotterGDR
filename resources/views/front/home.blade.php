@extends('front.layouts.app')

@section('content')
<div class="container rc">
  <div class="row justify-content-center">

    <div class="col-12" id="statistics">

      @if(Session::has('flash_message'))
          <div class="alert alert-success"><em> {!! session('flash_message') !!}</em></div>
      @endif

      <ul>
        <li>Utenti Registrati: <strong>{{$user_registered}}</strong></li>
        <li>Utenti Attivi: <strong>0</strong></li>
      </ul>
    </div>
    <div class="col-12">

      <div class="row fix_reg">
        <div class="col-6" id="loginForm">
          @include('auth.login')
        </div>

        <div class="col-6" id="registerForm">
          <h3>Registrati</h3>
          @include('auth.register')
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
