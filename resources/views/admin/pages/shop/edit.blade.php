@extends('admin.layouts.app')

@section('content')


  <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Crea un nuovo Negozio</h3>
              </div>
              <!-- /.box-header -->
              <!-- form start -->
            {{ Form::open(['files' => true]) }}


            <div class="box">

              <div class="box-body">
                <div class="row">
                  <div class="col-xs-5">
                    <label>Nome</label>
                    <input type="text" class="form-control" name="name" placeholder="nome" value="{{$shop->name}}" required>
                  </div>
                  <div class="col-xs-7">
                    <label>Descrizione</label>
                    <textarea class="form-control" rows="3" name="description" placeholder="Descrizione">{{$shop->description}}</textarea>
                  </div>
                </div>

                <div class="row">
                  <div class="col-xs-5">
                    <img src="{{url('upload/shop/'.$shop->image)}}">
                  </div>
                  <div class="col-xs-7">
                    <img src="{{url('upload/shop/'.$shop->background)}}" style="width:40%; height:40%">
                  </div>
                </div>

                <div class="row">
                  <div class="col-xs-5">
                    <label>Immagine</label>
                    <input type="file" name="image">
                    <p class="help-block">L'immagine sarà ridimensionata a 250x250</p>
                  </div>
                  <div class="col-xs-7">
                    <label>Background</label>
                    <input type="file" name="background">
                    <p class="help-block">L'immagine non sarà ridimensionata</p>
                  </div>
                </div>

                <div class="row">
                  <div class="col-xs-5">
                    <label>color</label>
                    <input type="text" class="form-control" name="color" placeholder="color" value="{{$shop->color}}">
                  </div>
                  <div class="col-xs-7">
                    <label>style</label>
                    <input type="text" class="form-control" name="style" placeholder="style" value="{{$shop->style}}">
                  </div>
                </div>

              </div>
            </div>

          <div class="box-footer">
            <button class="btn btn-success form-control">Crea Creatura</button>
          </div>
        </form>
      </div>


@endsection
