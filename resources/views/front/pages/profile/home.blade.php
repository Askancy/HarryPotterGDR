@extends('front.layouts.app')

@section('title', 'Profilo di '.$user->username)


@section('content')
<div class="container rc sk-container">
    <div class="row justify-content-center" style="margin-bottom: 35px">

                <h3 id="titlepage">Profilo di {{$user->username}}</h3>


    <div class="col-12">
      <div class="row justify-content-center">
            <div class="col-3">
              <img src="{{url('upload/user/'.$user->avatar())}}" style="padding: 15px">
            </div>


              <div class="col-md-6 col-sm-12 col-xs-12">
                <div class="col-md-12 row sk-row">
                  <div class="col-md-6"><span class="title-date">Nome:</span></div>
                  <div class="col-md-6"><strong>{{$user->name}}</strong></div>
                </div>
                <div class="col-md-12 row sk-row">
                  <div class="col-md-6"><span class="title-date">Cognome:</span></div>
                  <div class="col-md-6"><strong>{{$user->surname}}</strong></div>
                </div>
                <div class="col-md-12 row sk-row">
                  <div class="col-md-6"><span class="title-date">Casata:</span></div>
                  <div class="col-md-6"><strong><img src="{{url($user->team_img())}}">  {{$user->team()}}</strong></div>
                </div>
                <div class="col-md-12 row sk-row">
                  <div class="col-md-6"><span class="title-date">Anni / Sesso:</span></div>
                  <div class="col-md-6"><strong>{{$user->age()}} / {{$user->sex()}}</strong></div>
                </div>
                <div class="col-md-12 row sk-row">
                  <div class="col-md-6"><span class="title-date">Ultimo luogo visitato:</span></div>
                  <div class="col-md-6"><strong>ì</strong></div>
                </div>
              </div>

      </div>

      <hr>
      <div style="text-align:center">
        <button type="button" class="btn btn-primary">Aggiungi agli amici</button>
        <button type="button" class="btn btn-danger">Segnala</button>
      </div>

      <hr>
        <h3 id="titlepage">Biografia</h3>
          <div id="biography">
            <p>{{$user->biography}}</p>
          </div>
        <hr>
          <h3 id="titlepage">Amici</h3>

          <div class="col-12 row">

            <img src="http://via.placeholder.com/100x100" class="rounded" style="margin-right: 5px">
            <img src="http://via.placeholder.com/100x100" class="rounded" style="margin-right: 5px">
            <img src="http://via.placeholder.com/100x100" class="rounded" style="margin-right: 5px">
            <img src="http://via.placeholder.com/100x100" class="rounded" style="margin-right: 5px">
            <img src="http://via.placeholder.com/100x100" class="rounded" style="margin-right: 5px">
            <img src="http://via.placeholder.com/100x100" class="rounded" style="margin-right: 5px">

          </div>

    </div>
</div>
</div>
@endsection
