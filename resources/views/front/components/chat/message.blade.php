<div class="col-12">
    <div class="comments-container">
      <ul id="comments-list" class="comments-list">
        <li>
          @foreach ($mess_chat as $value)

            @php
              $usermsg = App\Models\User::where('id', $value->id_user)->first();
              $message = App\Models\ChatMessage::where('id_maps', $chat->id)->orwhere('id_user', Auth::user()->id)->orwhere('id_dest', Auth::user()->id)->orWhereNull('id_dest')->get();
            @endphp

            @if ($value->id_user == \Auth::user()->id || $value->id_dest == \Auth::user()->id || $value->id_dest == null )
                <div class="comment-main-level">
                <!-- Avatar -->
                <div class="comment-avatar"><img src="{{url('upload/user/'.$usermsg->avatar())}}" alt=""></div>
                <!-- Contenedor del Comentario -->
                <div class="comment-box">
                  <div class="comment-head">
                    <h6 class="comment-name by-author"><a href="{{url('profile/'.$usermsg->slug)}}">{{$usermsg->username}}</a></h6>
                    <span>{{ $usermsg->created_at->diffForHumans() }} </span>

                      @if ($value->type == "1")
                        @if ($value->id_dest != null)
                          @php $id_dest = \App\Models\User::where('id', $value->id_dest)->first(); @endphp
                          &nbsp;<span class="sussurro">Sussurro a {{$id_dest->name}}</span>
                        @else
                          &nbsp;<span class="sussurro">Sussurro</span>
                        @endif
                      @endif

                  </div>
                  <div class="comment-content" @if ($value->type == "1") style="background-color: rgb(232, 232, 232) !important" @endif>
                    {{$value->text}}
                  </div>
                </div>
              </div>
            @endif
          @endforeach
        </li>
      </ul>
  </div>

  <div class="col-12 justify-content-center">
    @include('front.components.chat.new_msg')
  </div>

</div>
