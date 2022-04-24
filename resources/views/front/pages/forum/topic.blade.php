@extends('front.layouts.app')

@section('title', $topic->name.' - '.$section->name)

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
        <li><i class="fas fa-home"></i> <a href="{{__('/forum')}}">Indice</a></li>
        <li>{{$category->name}}</li>
        <li><a href="{{__('/forum/'.$section->slug)}}">{{$section->name}}</a></li>
        <li>{{$topic->name}}</li>
      </ul>
    </nav>
  </div>
  @auth
  @if(Auth::user()->group > 0)
  <div class="gadget">
    @include('front.components.forum.tool_mod')
  </div>
  @endif
  @endauth
  {{$posts->links()}}
    <div class="post-heading">
      <div>{!!$topic->getStatus()!!}</div>
      <div>Titolo Topic: <strong>{{$topic->name}}</strong></div>
    </div>
  @foreach($posts as $post)
    @php
      $user = App\Models\User::where('id',$post->id_user)->first();
      $count = App\Models\ForumPost::where('id_user',$user->id)->count();
    @endphp
    <div class="post" id="{{$post->id}}">
      <div class="post-header">
        <div><a href="{{url('/profile/'.$user->slug)}}">
          <img src="{{asset('upload/user/'.$user->avatar)}}" alt="Avatar di {{$user->username}}">
        </a></div>
        <div class="post-info">
          <span>#{{ $post->id }}</span>
        </div>
        <div class="post-meta">
          <h2 style="font-size:18px"><a href="{{url('/profile/'.$user->slug)}}">
            {{$user->username}}
          </a></h2>
          <span>Messaggi: {{$count}}</span>
          <span>{!!$user->role()!!}</span>
        </div>
      </div>
      <div class="post-body">
        {!! $post->text !!}
      </div>
      <hr>
      <div class="post-footer">
        <div>
          <span><i class="far fa-clock"></i>
          @if($post->created_at != $post->updated_at)
           Modificato
          @else
           Inviato
          @endif
          {{-- formatLocalized('%A %d %B %Y') && --}}
          {{ $post->updated_at->diffForHumans() }}</span>
        </div>
        @auth
        <div class="post-info">
          @if((Auth::user()->id == $user->id) || Auth::user()->group > 0)
          <span><a href="{{url('/forum/post/'.$post->id.'/edit')}}">
            <i class="fas fa-edit" title="Modifica Post"></i>
          </a></span>
          <span><a id="del" data-target="{{$post->id}}" href="#delpost-{{$post->id}}">
            <i class="fas fa-times" title="Elimina Post"></i>
          </a></span>
          @endif
          @if(Auth::user()->id != $user->id)
          <span><a href="{{url('#')}}"<i class="fas fa-flag" title="Segnala Post" data-toggle="modal" data-target="#reportModal_{{$post->id}}"></i></a></span>
          @endif
        </div>
        @endauth
      </div>
    </div>

    <!-- Modal Report Comment-->
    <div class="modal fade" id="reportModal_{{$post->id}}" tabindex="-1" role="dialog" aria-labelledby="reportModal" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Segnalazione del post nr° {{ $post->id }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p>Stai segnalando il post di {{ $user->username }}</p>
            <textarea class="form-control" placeholder="Scrivi la ragione della segnalazione"></textarea>
          </div>
          <div class="modal-footer">
            <input type="hidden" id="{{ $post->id }}">
            <button type="button" class="send btn btn-dark" data-dismiss="modal">Invia segnalazione</button>
          </div>
        </div>
      </div>
    </div>

  @endforeach
  {{$posts->links()}}

  <script type="text/javascript">
  // Elimina il post
  $(".post .post-info #del").click(function(e){
    e.preventDefault();
    var post_id = $(this).data("target");
    $.ajax({
      method: "GET",
      url: "{{url('ajax/deletePost')}}",
      cache: false,
      data: {
        id: post_id,
      },
      success: function(data){
        $(".post ~ #" + post_id).fadeOut("slow");
        $("body").append("<div class='alert-fixed fadeOut'><h3 class='alert "+data.status+"'>"+data.msg+"</h3></div>");
      }
    });
  });
  // Segnala il post
  $(".modal .btn").click(function(e){
    e.preventDefault();
    $.ajax({
      method: "GET",
      url: "{{url('ajax/reportPost')}}",
      cache: false,
      data: {
        id: $(this).prev().attr('id'),
        text: $(this).parent().prev().find("textarea").val(),
      },
      success: function(data){
        $("body").append("<div class='alert-fixed fadeOut'><h3 class='alert "+data.status+"'>"+data.msg+"</h3></div>");
      }
    });
  });
  </script>

  @auth
  @if(Auth::user()->group > 0 || !$topic->locked)
    @include('front.components.forum.answer')
  @endif
  @endauth
</div>
@endsection
