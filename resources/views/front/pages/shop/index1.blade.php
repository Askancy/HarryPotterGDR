@extends('front.layouts.app')

@section('title', $shop->name)


@section('content')

  @php
    $position = new  App\Models\Position();

    $position->id_user = Auth::id();
    $position->id_maps = "0";
    $position->id_shop = $shop->id;
    $position->save();
  @endphp

<div class="container rc sk-container">
  <div class="row justify-content-center" style="margin-bottom: 35px">

    <div class="col-12 header-shop" style="background-image: url({{url('upload/shop/'.$shop->background)}})">
      <h3 id="title-shop">Negozio {{$shop->name}}</h3>
    </div>


    <hr>

    <div class="col-12 row" style="margin-top: 25px">
      @include('front.components.shop.item-lg')
    </div>

  </div>
</div>
@endsection
