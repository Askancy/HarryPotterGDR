@extends('front.layouts.app')

@section('title', $section->name)

@section('styles')
  <link href="{{ asset('inc/css/forum.css') }}" rel="stylesheet" />
@endsection

@section('content')
@php
  if(Auth::user() && Auth::user()->group > 0){
    $topics = App\Models\ForumTopic::where('id_section', $section->id)->orderBy('notable','desc')->orderBy('last_msg','desc')->paginate(9);
  }else{
    $topics = App\Models\ForumTopic::where('id_section', $section->id)->where('deleted',0)->orderBy('notable','desc')->orderBy('last_msg','desc')->paginate(9);
  }
@endphp
<div class="container rc">
  <div class="col-12 header-shop" style="background-image: url({{url('upload/forum/top.jpg')}})">
    <h3 id="title-shop">Forum</h3>
  </div>
  <div class="board-directory">
    <nav>
      <ul>
        <li><i class="fas fa-home"></i> <a href="{{url('/forum')}}">Indice</a></li>
        <li>{{$category->name}}</li>
        <li>{{$section->name}}</li>
      </ul>
    </nav>
  </div>
  @auth
  @if(Auth::user()->group > 0 || !($section->status))
  <div class="gadget">
    <div><a href="{{__('/forum/'.$section->slug.'/new')}}"><button class="btn btn-dark">Crea nuovo topic</button></a></div>
  </div>
  @else
  <div class="gadget">
    <div><button class="btn btn-dark" title="Sezione bloccata" disabled><i class="fas fa-lock"></i> Crea nuovo topic</button></div>
  </div>
  @endif
  @endauth
  {{$topics->links()}}
  <div class="board">
    <div class="board-head">
      <div></div>
      <div>Topic</div>
      <div>Risposte</div>
      <div>Ultimo Messaggio</div>
    </div>
    @if(count($topics))
    @foreach($topics as $topic)
    @php
      $count = App\Models\ForumPost::where('id_topic', $topic->id)->count();
      $last_post = App\Models\ForumPost::where('id_topic', $topic->id)->orderBy('created_at','desc')->first();
      $user = App\Models\User::where('id',$last_post->id_user)->first();
    @endphp
    <div class="board-group @if($topic->notable) board-group-important @endif">
      <div class="board-row" id="{{$topic->id}}">
        <div class="board-icon">
          {!!$topic->getStatus()!!}
        </div>
        <div class="board-title">
          <a href="{{__('/forum/topic/'.$topic->slug)}}">
            <h4>{{$topic->name}}</h4>
          </a>
        </div>
          <div class="board-thread">
            <p>{{$count}}</p>
          </div>
          <div class="board-lst">
            @if($count)
             <p class="board-profile"><a href="{{__('/profile/'.$user->slug)}}"><img src="{{asset('upload/user/'.$user->avatar)}}"/></a></p>
            <h4><a href="{{__('/forum/topic/'.$topic->slug.'#'.$last_post->id)}}">{{ $user->username }}</a></h4>
            <p>{{ $last_post->created_at->format('d/m/Y H:i') }}</p>
            @endif
          </div>
      </div>
    </div>
    @endforeach
    @endif
    <div class="board-footer">
      <div></div>
      <div>Sezioni</div>
      <div>Discussioni</div>
      <div>Ultimo Messaggio</div>
    </div>
    {{$topics->links()}}
  </div>
  <div class="board-legend">
    <ul>
      <li><i class="fas fa-comments"></i> Discussione Aperta</li>
      <li><i class="fas fa-lock"></i> Discussione Chiusa</li>
    </ul>
  </div>
</div>
@endsection
