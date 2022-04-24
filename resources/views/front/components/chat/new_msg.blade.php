{{ Form::open(['class'=>'row']) }}
<div class="col-12">
  <input class="d-none" name="id_maps" value="{{$chat->id}}">
  <textarea class="form-control" name="messaggio"></textarea>
</div>
<div class="col-12 row justify-content-center" style="margin-top: 15px">
  <button class="btn btn-primary btn-shadow col-4" type="submit" name="save">Invia</button>
  <select class="form-control col-4" name="type">
    <option value="0">standard</div>
    <option value="1">sussurro</div>
  </select>
  @php
    $alluser = App\Models\User::get();
  @endphp
  <select class="form-control col-4" name="id_dest" id="id_dest" data-allow_clear="1">
    <option value="0" selected>--</div>
    @foreach ($alluser as $value)
      <option value="{{$value->id}}">{{$value->username}}</div>
    @endforeach
  </select>
</div>
{!! csrf_field() !!}
{!! Form::close() !!}
