@extends('admin.layouts.app')

@section('content')


  <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Crea nuova Quest</h3>
              </div>
              <!-- /.box-header -->
              <!-- form start -->
            {{ Form::open(['files' => true]) }}
                <div class="box-body">

                  <div class="form-group">
                    <label>Mappa Precedente</label>
                    <input type="text" class="form-control" name="id_maps_prev" placeholder="Mappa Precedente" required>
                  </div>
                  <div class="form-group">
                    <label>Mappa Successiva</label>
                    <input type="text" class="form-control" name="id_maps_prec" placeholder="Mappa Successiva" required>
                  </div>
                  <div class="form-group">
                    <label>Privacy</label>
                    <select name="status" class="form-control">
                      <option value="0">Quest Pubblica</option>
                      <option value="1">Quest Privata</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                      <option value="0">Non attiva</option>
                      <option value="1">Attiva</option>
                      <option value="2">Terminata e disattiva</option>
                    </select>
                  </div>
<hr>

                    <select name="id_user[]" class="form-group" multiple>
                      @foreach ($user as $value)
                        <option value="{{$value->id}}"> {{$value->username}}</option>
                      @endforeach
                    </select>

<hr>
          <h2>Creazione Chat</h2>
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
                    <label>id quest</label>
                    <input type="text" class="form-control" name="id_quest" placeholder="id_quest">
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
