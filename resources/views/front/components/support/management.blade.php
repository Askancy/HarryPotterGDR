<div class="col-xs-12">
  <div class="box">
    <div class="box-header">
      <h3 class="box-title">Gestione Ticket</h3>
      <div class="box-tools">
        <div class="input-group input-group-sm" style="width: 150px;">
        </div>
      </div>
    </div>
    {{$get_tickets->links()}}
    <!-- /.box-header -->
    <div class="box-body table-responsive no-padding">
      <table class="table table-hover">
        <tbody><tr>
          <th>ID</th>
          <th>Status</th>
          <th>Utente</th>
          <th>Titolo</th>
          <th></th>
          <th></th>
        </tr>
        @foreach($get_tickets as $value)
        @php
          $user = App\Models\User::where('id',$value->id_user)->first();
        @endphp
        <tr>
          <td>{{$value->id}}</td>
          <td>@if($value->status) Chiuso @else Aperto @endif</td>
          <td>{{$user->name}} {{$user->surname}} ({{$user->username}})</td>
          <td>{{$value->name}}</td>
          <td><a href="{{url('/support/ticket/view/'.$value->slug)}}"><button type="button" class="btn btn-block btn-info btn-flat">Visualizza Ticket</button></a></td>
          @if($value->status == 0)<td><a href="{{url('/support/ticket/management/'.$value->id.'/locked')}}"><button type="button" class="btn btn-block btn-danger btn-flat">Chiudi Ticket</button></a></td>@endif
        </tr>
        @endforeach
      </tbody></table>
    </div>
    <!-- /.box-body -->
    {{$get_tickets->links()}}
  </div>
  <!-- /.box -->
</div>
