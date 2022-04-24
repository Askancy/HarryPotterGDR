@extends('admin.layouts.app')

@section('content')
<div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Lista Negozi</h3>

              <div class="box-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                  <div class="input-group-btn">
                    <a href="{{url('admin/shop/new')}}"><button type="submit" class="btn btn-primary">Nuovo</button></a>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tbody><tr>
                  <th>ID</th>
                  <th>Nome</th>
                  <th>desc</th>
                  <th>Azione</th>
                </tr>
                @foreach ($shop as $value)
                <tr>
                  <td>{{$value->id}}</td>
                  <td>{{$value->name}}</td>
                  <td>{{$value->desc}}</td>
                  <td>
                    <a href="{{url('admin/shop/edit/'.$value->id)}}"><button type="button" class="btn btn-block btn-info btn-flat">Edit</button></a>
                  </td>
                </tr>
                @endforeach
              </tbody></table>
            </div>
            {{ $shop->links() }}

            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>
    @endsection
