@extends('admin.layouts.app')

@section('content')


  <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Modifica l'oggetto {{$obj->name}}</h3>
              </div>
              <!-- /.box-header -->
              <!-- form start -->
            {{ Form::open(['files' => true]) }}
                <div class="box-body">
                  <div class="form-group">
                    <label>Nome</label>
                    <input type="text" class="form-control" name="name" placeholder="nome" value="{{$obj->name}}" required>
                  </div>
                  <div class="form-group">
                    <label>Descrizione</label>
                    <textarea class="form-control" rows="3" name="description" placeholder="Descrizione">{{$obj->description}}</textarea>
                  </div>
                  <div class="form-group">
                    <label>Immagine</label>
                    <img src="{{url('upload/obj/'.$obj->image)}}">
                    <input type="file" name="image">
                    <p class="help-block">L'immagine sarà ridimensionata a 250x250</p>
                  </div>

                  <div class="form-group">
                    <label>Tipo</label>
                    <select class="form-control" name="type">
                      <option value="1" @if ($obj->type == "1")Selected @endif>Animali</option>
                      <option value="2" @if ($obj->type == "2")Selected @endif>Bacchette</option>
                      <option value="3" @if ($obj->type == "3")Selected @endif>Scope</option>
                      <option value="4" @if ($obj->type == "4")Selected @endif>Pozioni</option>
                      <option value="5" @if ($obj->type == "5")Selected @endif>Varie</option>
                    </select>
                  </div>

                  <div class="form-group">
                    <label>Rarità</label>
                    <select class="form-control" name="rare">
                      <option value="1">Comune</option>
                      <option value="2">Non Comune</option>
                      <option value="3">Raro</option>
                      <option value="4">Epico</option>
                      <option value="5">Leggendario</option>
                    </select>
                  </div>

                  <div class="input-group">
                    <span class="input-group-addon">--</span>
                    <input type="text" class="form-control" placeholder="id del negozio" name="id_shop" value="{{$obj->id_shop}}" required>
                  </div>

                  <div class="input-group">
                    <span class="input-group-addon">$</span>
                    <input type="text" class="form-control" placeholder="price Galeoni,Falci" name="price" value="{{$obj->price}}" required>
                  </div>

                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                  <button class="btn btn-success form-control">Salva Modifiche</button>
                </div>
              </form>
            </div>


@endsection
