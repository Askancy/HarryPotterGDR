@extends('front.layouts.app')

@section('title', 'Forum')

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
      </ul>
    </nav>
  </div>
  <h4><i class="fas fa-life-ring"></i>
    Ti serve assistenza? apri un <a href="{{ url('support/ticket/new') }}">ticket</a>
  </h4>
  @foreach($categories as $value)
  <div class="board-heading">{{$value->name}}</div>
  <p class="board-heading-caption">{!!$value->description!!}</p>
  <div class="board">
    <div class="board-head">
      <div>Sezioni</div>
      <div>Discussioni</div>
      <div>Ultimo Messaggio</div>
    </div>
    @php
      $sections = App\Models\ForumSection::where('id_category',$value->id)->get();
    @endphp
    @foreach($sections as $section)
    @php
      $count = App\Models\ForumTopic::where('id_section',$section->id)->count();
      $last_post = App\Models\ForumPost::with('last_topic','last_author')->where('id_section',$section->id)->orderBy('created_at','desc')->first();
    @endphp
    <div class="board-group">
      <div class="board-row">
        <div class="board-title">
          <a href="{{__('/forum/'.$section->slug)}}">
            <h4>{{$section->name}}</h4>
            <p>{!!$section->description!!}</p>
          </a>
        </div>
        <div class="board-thread">
            <p>{{$count}}</p>
          </div>
          <div class="board-lst">
            @if($count)
            <p class="board-profile"><a href="{{__('/profile/'.$last_post->last_author->slug)}}"><img src="{{asset('upload/user/'.$last_post->last_author->avatar)}}"/></a></p>
            <h4><a href="{{__('/forum/topic/'.$last_post->last_topic->slug.'#'.$last_post->id)}}">{{ $last_post->last_topic->name }}</a></h4>
            <p>{{ $last_post->created_at->format('d/m/Y H:i') }}</p>
            @else
            <p>Nessun Messaggio</p>
            @endif
          </div>
      </div>
    </div>
    @endforeach
    <div class="board-footer">
      <div>Sezioni</div>
      <div>Discussioni</div>
      <div>Ultimo Messaggio</div>
    </div>
  </div>
  @endforeach
</div>
@endsection
