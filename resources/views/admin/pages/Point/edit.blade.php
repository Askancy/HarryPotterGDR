@extends('admin.layouts.app')

@section('content')


  <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Crea Punti</h3>
              </div>
              <!-- /.box-header -->
              <!-- form start -->
            {{ Form::open(['files' => true]) }}
                <div class="box-body">
                  <div class="form-group">
                    <label>Team</label>
                    <select name="id_team" class="form-control">
                      <option value="0"> -- </option>
                      @foreach ($team as $value)
                        <option value="{{$value->id}}" @if ($logs_point->id_team == $value->id) selected @endif>{{$value->name}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Utente</label>
                    <select name="id_user" class="form-control">
                      <option value="0"> -- </option>
                      @foreach ($user as $value)
                        <option value="{{$value->id}}" @if ($logs_point->id_user == $value->id) selected @endif>{{$value->username}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Tipo voto</label>
                    <select name="positive" class="form-control">
                      <option value="0" @if ($logs_point->positive == "0") selected @endif>Positivo</option>
                      <option value="1" @if ($logs_point->positive == "1") selected @endif>Negativo</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>motivation</label>
                    <textarea class="form-control" name="motivation">{{$logs_point->motivation}}</textarea>
                  </div>
                  <div class="form-group">
                    <label>Punti</label>
                    <input class="form-control" name="point" value="{{$logs_point->point}}">
                  </div>



                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                  <button class="btn btn-success form-control">Aggiungi Punti</button>
                </div>
              </form>
            </div>


@endsection
