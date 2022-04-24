@extends('front.layouts.app')

@section('title', 'Ticket - Assistenza')

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
        <li>Ticket: <strong>{{$ticket->name}}</strong></li>
      </ul>
    </nav>
  </div>

  @foreach($ticket_msg as $value)
    @php
      $user = App\Models\User::where('id',$value->id_user)->first();
    @endphp
    <div id="{{$value->id}}" class="ticket">
      <div class="ticket-card">
        @if($user->group > 0)
        <span><img src="{{asset('upload/icon/staff.png')}}"></span>
        @else
        <span><img src="{{asset('upload/user/'.$user->avatar)}}"></span>
        @endif
        <div class="user-info">
          <span>{{$user->name}} {{$user->surname}}</span>
          <span>{{$user->role()}}</span>
        </div>
      </div>
      <div class="ticket-content">
        {!! $value->text !!}
      </div>
    </div>
  @endforeach
  @if($ticket->status == 0) {{-- Se il topic è aperto --}}
  @if((Auth::user()->id == $last_message->id_user) && Auth::user()->group == 0)
  <div>
    <h4>Attendi la risposta di un operatore</h4>
  </div>
  @else
    @include('front.components.support.answer')
  @endif
  @else
  <div>
    <h4>Questo ticket è stato chiuso da un operatore</h4>
  </div>
  @endif
</div>
@endsection
