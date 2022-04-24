@extends('admin.layouts.app')

@section('content')


  <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Modifica sezione</h3>
              </div>
              <!-- /.box-header -->
              <!-- form start -->
            {{ Form::open(['files' => true]) }}
                <div class="box-body">
                  <div class="form-group">
                    <label>Nome</label>
                    <input type="text" class="form-control" name="name" value="{{$section->name}}" required>
                  </div>

                  <div class="form-group">
                    <label>Categoria</label>
                      <input class="form-control" type="text" value="{{$categ->name}}" disabled>
                  </div>

                  <div id="input" class="form-group"></div>

                  <script type="text/javascript">
                    function addInput($value) {
                      let $label, $input;
                      $id = document.getElementById("input");
                      if($value == -1) {
                        //Creo un nuovo input
                        $label = document.createElement("label");
                        $input = document.createElement("input");
                        $id.appendChild($label);
                        $id.appendChild($input);
                        $label.innerHTML = "Categoria";
                        $input.placeholder  = "nome";
                        $input.className = "form-control";
                        $input.name = "categ-name";
                      } else {
                          let $len = $id.childNodes.length;
                          for(let $i=0;$i<$len;$i++){
                            $id.removeChild($id.firstChild);
                          }
                      }
                    }
                  </script>

                  <div class="form-group">
                    <label>Descrizione</label>
                    <input type="text" class="form-control" name="description" value="{{$section->description}}" required>
                  </div>

                  <div class="form-group">
                    <label>Status</label>
                    <select class="form-control" name="status">
                      <option value="0">Pubblico</option>
                      <option value="1">Privato</option>
                    </select>
                  </div>

                  <div class="form-group">
                    <label>Icona</label>
                    <input type="file" name="icon">
                    <p class="help-block">L'immagine sarà ridimensionata a 25x25</p>
                  </div>

                  <div class="form-group">
                    <label>Ordina da</label>
                    <input type="number" class="form-control" name="orderby" value="0">
                  </div>

                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                  <button class="btn btn-success form-control">Crea Sezione</button>
                </div>
              </form>
            </div>


@endsection
