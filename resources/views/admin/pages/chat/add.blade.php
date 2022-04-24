@extends('admin.layouts.app')

@section('content')


  <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Crea nuova Chat</h3>
              </div>
              <!-- /.box-header -->
              <!-- form start -->
            {{ Form::open(['files' => true]) }}
                <div class="box-body">
                  <div class="form-group">
                    <label>Nome</label>
                    <input type="text" class="form-control" name="name" placeholder="name" required>
                  </div>
                  <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" name="description"></textarea>
                  </div>
                  <div class="form-group">
                    <label>Chat Team</label>
                    <select name="id_team" class="form-control">
                      <option value="0"> -- </option>
                      @php
                        $team = \App\Models\Team::get();
                      @endphp
                      @foreach ($team as $value)
                        <option value="{{$value->id}}">{{$value->name}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Restrizione</label>
                    <select name="restrinction" class="form-control">
                      <option value="0"> -- </option>
                      <option value="1">Casata</option>
                      <option value="2">Prenotata</option>
                      <option value="3">Quest</option>
                      <option value="4">Quest Chiusa</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Quest</label>
                    <select name="id_quest" class="form-control">
                      <option value="0">No</option>
                      <option value="1">Si</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>È per una quest</label>
                    <select name="is_quest" class="form-control">
                      <option value="0">No</option>
                      <option value="1">Si</option>
                    </select>
                  </div>
                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                  <button class="btn btn-success form-control">Crea</button>
                </div>
              </form>
            </div>


@endsection
