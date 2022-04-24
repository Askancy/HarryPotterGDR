@extends('front.layouts.app')

@section('title', 'Assistenza')

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
      </ul>
    </nav>
  </div>

  @auth
  <div class="col-md-6">
    <a href="{{url('support/ticket/new')}}">
    <button class="btn btn-dark">Apri un nuovo ticket</button>
  </a>
  @if(Auth::user()->group > 0)
  <a href="{{url('support/ticket/management')}}">
    <button class="btn btn-dark">Gestione ticket</button>
  </a>
  @endif
  </div>
  @endauth

  <div class="panel panel-dark">
    <div class="panel-header">
      <h4>Ticket Recenti</h4>
    </div>
    <div class="panel-body">
    @auth
      @if($tickets->count())
      @foreach($tickets as $value)
        @php
          $l_msg = \App\Models\TicketMessage::where('id_ticket',$value->id)->orderBy('created_at','desc')->first();
        @endphp
        <div id="ticket-{{$value->id}}" class="@if($l_msg->read == 0) new @endif">
          <span class="time">{{$value->created_at->format('d-m-Y H:i')}}</span>
          <span><a href="{{url('support/ticket/view/'.$value->slug)}}">{{$value->name}}</a></span>
        </div>
      @endforeach
      @else
      <div>
        <h5 style="padding:40px;">Non hai attività da visualizzare</h5>
      </div>
      @endif
    @else
    <div>
      <h5 style="padding:40px;"><a href="{{url('/')}}" style="text-decoration:underline;">Connettiti</a> per poter visualizzare le tue attività</h5>
    </div>
    @endauth
    </div>
  </div>

  <div class="panel panel-dark">
    <div class="panel-header">
      <h4>Domande Frequenti</h4>
    </div>
    <div class="panel-body">
      <div>
        <span class="time">Sezione</span>
        <span>Domanda 1</span>
      </div>
      <div>
        <span class="time">Sezione</span>
        <span>Domanda 2</span>
      </div>
      <div>
        <span class="time">Sezione</span>
        <span>Domanda 3</span>
      </div>
      <div>
        <span class="time">Sezione</span>
        <span>Domanda 4</span>
      </div>
      <div>
        <span class="time">Sezione</span>
        <span>Domanda 5</span>
      </div>
    </div>
  </div>

</div>
@endsection
