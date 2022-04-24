@extends('front.layouts.app')

@section('title', "Mappa di Londra")


@section('content')


<div class="container rc sk-container">
  <div class="row justify-content-center" style="margin-bottom: 35px">

    <div class="col-12">

      @if(Session::has('flash_message'))
          <div class="alert alert-success"><em> {!! session('flash_message') !!}</em></div>
      @endif

      <h3>Benvenuto a {{$user->username}}</h3>
        <hr>
      <h2>Coppa delle case</h2>
          <p>bla bla bla</p>
        <hr>
    </div>
    <div clas="col-12">
    <h2>Riassunto giornata</h2>
    {{-- Se adulto e ha negozio vede le vendite giornaliere--}}
    <table class="table table-striped">
      <thead>
        <tr>
          <th>#</th>
          <th>Prezzo</th>
          <th>Nome</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th scope="row">1</th>
          <td>50</td>
          <td>Bacchetta</td>
        </tr>
      </tbody>
    </table>
    <h2>Riassunto giornata</h2>
    {{-- Se studente vede l'andamento dei punteggi giornalieri--}}
    <table class="table table-striped">
      <thead>
        <tr>
          <th>#</th>
          <th>punti</th>
          <th>Nome</th>
          <th>casa</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th scope="row">1</th>
          <td>50</td>
          <td>Otto</td>
          <td>@serperverde</td>
        </tr>
      </tbody>
    </table>
  </div>


  </div>
</div>
@endsection
