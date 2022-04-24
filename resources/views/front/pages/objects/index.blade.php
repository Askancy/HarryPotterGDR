@extends('front.layouts.app')

@section('title', $object->name.' - Prodotto')


@section('content')
<div class="container rc sk-container">
  <div class="row justify-content-center" style="margin-bottom: 35px">

    <div class="col-12 header-shop" style="background-image: url(http://www.potterpedia.it/imagewatermark/immagini/2302-p1bo7koh8p12av1hsh11nc1fsh1of64.jpg)">
      <h3 id="title-shop">{{$object->name}}</h3>
    </div>

    @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
      @endif

      @if (\Auth::user()->money >= $object->price)
        {{ Form::open(['files' => true,'class'=> 'col-12']) }}
      @endif
        <div class="col-12" style="margin-top:40px">
            <div class="row justify-content-center">
              <div class="col-3">
                <img src="http://via.placeholder.com/250x250">
                {{-- <img src="{{url('upload/obj'.$object->image)}}"> --}}
              </div>
              <div class="col-5">
                <ul>
                  <li><strong>Nome:</strong> {{$object->name}}</li>
                  <li><strong>Descrizione:</strong> {{$object->description}}</li>
                  <li><strong>Costo:</strong> {{$object->price}}</li>
                </ul>
              </div>
          </div>
          <hr>

          <input type="hidden" name="_token" value="{{ csrf_token() }}">
        @if (\Auth::user()->money >= $object->price)
            <div class="col-12">
              <button type="submit" class="btn btn-primary btn-shadow btn-block">Acquista</button>
            </div>
        @else
          <center><i>Non hai abbastanza soldi per acquistare questo prodotto</i></center>
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-shadow btn-block" disabled>Acquista</button>
          </div>
        @endif
        </div>

        @if (\Auth::user()->money >= $object->price)
          {{ Form::close() }}
        @endif
        <h3>Prodotti Simili</h3>

  </div>
</div>
@endsection
