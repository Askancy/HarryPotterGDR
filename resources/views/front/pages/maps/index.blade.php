@extends('front.layouts.app')

@section('title', "Mappa di Londra")


@section('content')

  @php
    $position = new  App\Models\Position();

    $position->id_user = Auth::id();
    $position->id_maps = $chat->id_maps;
    $position->id_shop = "0";
    $position->save();
  @endphp

<div class="container rc sk-container">
  <div class="row justify-content-center" style="margin-bottom: 35px">

@if ($chat->id_team == "1" || $chat->id_team == "2" || $chat->id_team == "3" || $chat->id_team == "4")
  <h3>Benvenuto nella {{$chat->name}}</h3>
@else
  <h3>Benvenuto a {{$chat->name}}</h3>
@endif
    @include('front.components.chat.message')
  </div>
</div>
@endsection
