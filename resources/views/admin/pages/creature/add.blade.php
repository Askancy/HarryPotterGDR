@extends('admin.layouts.app')

@section('content')


  <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Crea un nuovo oggetto</h3>
              </div>
              <!-- /.box-header -->
              <!-- form start -->
            {{ Form::open(['files' => true]) }}
                <div class="box-body">
                  <div class="form-group">
                    <label>Nome</label>
                    <input type="text" class="form-control" name="name" placeholder="nome" required>
                  </div>
                  <div class="form-group">
                    <label>Descrizione</label>
                    <textarea class="form-control" rows="3" name="description" placeholder="Descrizione"></textarea>
                  </div>
                  <div class="form-group">
                    <label>Immagine</label>
                    <input type="file" name="image">
                    <p class="help-block">L'immagine sarà ridimensionata a 250x250</p>
                  </div>


                  <div class="input-group">
                    <span class="input-group-addon">Genere</span>
                    <select name="genre" class="form-control">
                      <option value="0"> -- </option>
                      @foreach ($genre as $value)
                        <option value="{{$value->id}}">{{$value->name}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="input-group">
                    <span class="input-group-addon">Livello</span>
                    <input type="text" class="form-control" placeholder="Livello" name="level" required>
                  </div>
                  <div class="input-group">
                    <span class="input-group-addon">Vita</span>
                    <input type="text" class="form-control" placeholder="Vita" name="hp" required>
                  </div>
                  <div class="input-group">
                    <span class="input-group-addon">Danni</span>
                    <input type="text" class="form-control" placeholder="Danni" name="dmg" required>
                  </div>

                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                  <button class="btn btn-success form-control">Crea Creatura</button>
                </div>
              </form>
            </div>


@endsection
