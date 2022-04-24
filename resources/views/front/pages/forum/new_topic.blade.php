@extends('front.layouts.app')

@section('title','Nuovo Topic')

@section('styles')
  <link href="{{ asset('inc/css/forum.css') }}" rel="stylesheet" />
@endsection

@section('content')
<div class="container rc">
  <div class="col-12 header-shop" style="background-image: url({{url('upload/forum/top.jpg')}})">
    <h3 id="title-shop">Forum</h3>
  </div>
  <div class="board-directory">
    <nav>
      <ul>
        <li><i class="fas fa-home"></i> <a href="{{url('/forum')}}">Indice</a></li>
        <li>{{$category->name}}</li>
        <li><a href="{{url('/forum/'.$section->slug)}}">{{$section->name}}</a></li>
        <li>Nuovo Topic</li>
      </ul>
    </nav>
  </div>
  <form method="POST" action="#" aria-label="{{ __('New Topic') }}" id="loginStyle" class="justify-content-center">
    @csrf

    <div class="board-form">

      <div class="board-form-group">
        <input class="form-control" type="text" name="name" placeholder="Titolo del Topic"/>
      </div>

      <div class="board-form-group">
        <textarea rows="20" class="form-control" name="text" placeholder="Inizia a scrivere..."></textarea>
      </div>

      <div class="board-form-group">
        <button class="btn btn-dark" type="submit">
          {{__('Pubblica Topic')}}
        </button>
      </div>

    </div>
  </form>
</div>
@endsection
