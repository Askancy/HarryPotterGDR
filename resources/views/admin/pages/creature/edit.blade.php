@extends('admin.layouts.app')

@section('content')


  <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Modifica Creatura {{$creature->name}}</h3>
              </div>
              <!-- /.box-header -->
              <!-- form start -->
            {{ Form::open(['files' => true]) }}
                <div class="box-body">
                  <div class="form-group">
                    <label>Nome</label>
                    <input type="text" class="form-control" name="name" placeholder="nome" value="{{$creature->name}}" required>
                  </div>
                  <div class="form-group">
                    <label>Descrizione</label>
                    <textarea class="form-control" rows="3" name="description" placeholder="Descrizione">{{$creature->description}}</textarea>
                  </div>
                  <div class="form-group">
                    <label>Immagine</label>
                    <img src="{{url('upload/creature/'.$creature->image)}}">
                    <input type="file" name="image">
                    <p class="help-block">L'immagine sarà ridimensionata a 250x250</p>
                  </div>


                  <div class="input-group">
                    <span class="input-group-addon">Genere</span>
                    <select name="genre" class="form-control">
                      <option value="0" @if ($genre == '0') selected @endif> -- </option>
                      @foreach ($genre as $value)
                        <option value="{{$value->id}}" @if ($genre == $value->id) selected @endif>{{$value->name}}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="input-group">
                    <span class="input-group-addon">Livello</span>
                    <input type="text" class="form-control" placeholder="livello" name="level" value="{{$creature->level}}" required>
                  </div>

                  <div class="input-group">
                    <span class="input-group-addon">HP</span>
                    <input type="text" class="form-control" placeholder="vita" name="hp" value="{{$creature->hp}}" required>
                  </div>
                  <div class="input-group">
                    <span class="input-group-addon">Danni</span>
                    <input type="text" class="form-control" placeholder="Danni" name="dmg" value="{{$creature->dmg}}" required>
                  </div>
                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                  <button class="btn btn-success form-control">Salva Modifiche</button>
                </div>
              </form>
            </div>


@endsection
