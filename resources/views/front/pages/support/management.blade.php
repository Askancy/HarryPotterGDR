@extends('front.layouts.app')

@section('title', 'Gestione Ticket - Assistenza')
@section('styles')
  <link href="{{ asset('inc/css/support.css') }}" rel="stylesheet" />
@endsection

@section('content')
<div class="container rc">
    <div class="col-12 header-support" style="background-image: url({{url('upload/support/top.jpg')}})">
      <h3 id="title-shop">Gestione Ticket</h3>
    </div>

    <div class="support-directory">
      <nav>
        <ul>
          <li><i class="fas fa-life-ring"></i> <a href="{{url('/support')}}">Centro Assistenza</a></li>
          <li>Gestione Ticket</li>
        </ul>
      </nav>
    </div>

  @auth
    @if(Auth::user()->group > 0)
      @include('front.components.support.management')
    @endif
  @endauth
</div>
@endsection
