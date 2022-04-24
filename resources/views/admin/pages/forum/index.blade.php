@extends('admin.layouts.app')

@section('content')
<div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Lista Forum</h3>

              <div class="box-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                  <div class="input-group-btn">
                    <a href="{{url('admin/forum/new')}}"><button type="submit" class="btn btn-primary">Nuovo</button></a>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tbody><tr>
                  <th>ID</th>
                  <th>Status</th>
                  <th>Nome</th>
                  <th>Categoria</th>
                  <th>Descrizione</th>
                  <th>Azione</th>
                </tr>
                @foreach($forum as $value)
                @php
                  $categ = App\Models\ForumCategory::where('id',$value->id_category)->first();
                @endphp
                <tr>
                  <td>{{$value->id}}</td>
                  <td>@if($value->status) Privato @else Pubblico @endif</td>
                  <td>{{$value->name}}</td>
                  <td>{{$categ->name}}</td>
                  <td>{{$value->description}}</td>
                  <td><a href="{{url('admin/forum/edit/'.$value->id)}}"><button type="button" class="btn btn-block btn-info btn-flat">Edit</button></a></td>
                </tr>
                @endforeach
              </tbody></table>
            </div>
            {{ $forum->links() }}

            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>
    @endsection
