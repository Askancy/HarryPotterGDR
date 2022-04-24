@extends('admin.layouts.app')

@section('content')


  <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Crea una nuova sezione</h3>
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
                    <label>Categoria</label>
                    <select class="form-control" name="parent" onchange="addInput(this.value)">
                      <option value="-1" selected>Crea nuova categoria</option>
                      @foreach($categories as $value)
                      <option value="{{$value->id}}">{{$value->name}}</option>
                      @endforeach
                    </select>
                  </div>

                  <div id="input" class="form-group"></div>

                  <script type="text/javascript">
                    document.onload = addInput("-1");
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
                    <input type="text" class="form-control" name="description" placeholder="Descrizione" required>
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
