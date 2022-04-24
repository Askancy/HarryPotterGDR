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

                  <div class="form-group">
                    <label>Tipo</label>
                    <select class="form-control" name="type">
                      <option value="1">Animali</option>
                      <option value="2">Bacchette</option>
                      <option value="3">Scope</option>
                      <option value="4">Pozioni</option>
                      <option value="5">Varie</option>
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
                    <input type="text" class="form-control" placeholder="id del negozio" name="id_shop">
                  </div>

                  <div class="input-group">
                    <span class="input-group-addon">$</span>
                    <input type="text" class="form-control" placeholder="price Galeoni,Falci" name="price" required>
                  </div>

                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                  <button class="btn btn-success form-control">Crea Oggetto</button>
                </div>
              </form>
            </div>


@endsection
