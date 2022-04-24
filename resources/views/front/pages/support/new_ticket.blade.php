@extends('front.layouts.app')

@section('title','Nuovo Ticket - Assistenza')

@section('styles')
  <link href="{{ asset('inc/css/support.css') }}" rel="stylesheet" />
@endsection

@section('content')
<div class="container rc">
  <div class="col-12 header-support" style="background-image: url({{url('upload/support/top.jpg')}})">
    <h3 id="title-shop">Centro Assistenza</h3>
  </div>

  <div class="support-directory">
    <nav>
      <ul>
        <li><i class="fas fa-life-ring"></i> <a href="{{url('/support')}}">Centro Assistenza</a></li>
        <li>Nuovo Ticket</li>
      </ul>
    </nav>
  </div>

  <form method="POST" action="#" aria-label="{{ __('New Ticket') }}" class="justify-content-center">
    @csrf

    <div class="ticket-form">

      <div class="ticket-form-group">
        <input class="form-control" type="text" name="name" placeholder="Titolo del Ticket"/>
      </div>

      <div class="ticket-form-group">
        <select class="form-control" name="type">
          <option>Account</option>
          <option>Problemi in gioco</option>
        </select>
      </div>

      <div class="ticket-form-group">
        <textarea rows="20" class="form-control" name="text" placeholder="Inizia a scrivere..."></textarea>
      </div>

      <div class="ticket-form-group">
        <button class="btn btn-dark" type="submit">
          {{__('Invia Ticket')}}
        </button>
      </div>

    </div>
  </form>

</div>
@endsection
