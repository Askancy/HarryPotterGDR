@extends('admin.layouts.app')

@section('content')


  <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Modifica Chat</h3>
              </div>
              <!-- /.box-header -->
              <!-- form start -->
            {{ Form::open(['files' => true]) }}
                <div class="box-body">
                  <div class="form-group">
                    <label>Nome</label>
                    <input type="text" class="form-control" name="name" placeholder="name" value="{{$chat->name}}">
                  </div>
                  <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" name="description">{{$chat->description}}</textarea>
                  </div>
                  <div class="form-group">
                    <label>Chat Team</label>
                    <select name="id_team" class="form-control">
                      <option value="0" @if ($chat->name == '0') selected @endif> -- </option>
                      @php
                        $team = \App\Models\Team::get();
                      @endphp
                      @foreach ($team as $value)
                        <option value="{{$value->id_team}}" @if ($value->id_team == $chat->id_team) selected @endif>{{$value->name}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Restrizione</label>
                    <select name="restrinction" class="form-control">
                      <option value="0" @if ($value->restrinction == '0') selected @endif> -- </option>
                      <option value="1" @if ($value->restrinction == '1') selected @endif>Casata</option>
                      <option value="2" @if ($value->restrinction == '2') selected @endif>Prenotata</option>
                      <option value="3" @if ($value->restrinction == '3') selected @endif>Quest</option>
                      <option value="4" @if ($value->restrinction == '4') selected @endif>Quest Chiusa</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Quest</label>
                    <select name="id_quest" class="form-control">
                      <option value="0" @if ($value->id_quest == '0') selected @endif>No</option>
                      <option value="1" @if ($value->id_quest == '1') selected @endif>Si</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>È per una quest</label>
                    <select name="is_quest" class="form-control">
                      <option value="0" @if ($value->is_quest == '0') selected @endif>No</option>
                      <option value="1" @if ($value->is_quest == '0') selected @endif>Si</option>
                    </select>
                  </div>
                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                  <button class="btn btn-success form-control">Modifica</button>
                </div>
              </form>
            </div>


@endsection
