@extends('admin.layouts.app')

@section('content')
<div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Lista Punti</h3>

              <div class="box-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                  <div class="input-group-btn">
                    <a href="{{url('admin/point/new')}}"><button type="submit" class="btn btn-primary">Nuovo</button></a>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tbody><tr>
                  <th>ID</th>
                  <th>Punti</th>
                  <th>Casata</th>
                  <th>Azione</th>
                </tr>
                @foreach ($logs_point as $value)
                <tr>
                  <td>{{$value->id}}</td>
                  <td>{{$value->point}}</td>
                  <td>{{$value->id_team}}</td>
                  <td>
                    <a href="{{url('admin/point/edit/'.$value->id)}}"><button type="button" class="btn btn-block btn-info btn-flat">Edit</button></a>
                  </td>
                </tr>
                @endforeach
              </tbody></table>
            </div>
            {{ $logs_point->links() }}

            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>
    @endsection
