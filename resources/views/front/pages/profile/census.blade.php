@extends('front.layouts.app')

@section('title', 'Censimento di Hogwarts')


@section('content')
<div class="container sk-container">
  <div class="row justify-content-center" style="margin-bottom: 35px">

    <div class="col-12">
      <div class="row justify-content-center">

        <table class="table table-sm">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">First</th>
              <th scope="col">Last</th>
              <th scope="col">Handle</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($user as $value)
            <tr>
              <th scope="row">{{$value->house->name}}</th>
              <td><a href="{{url('profile/'.$value->slug)}}">{{$value->name}} {{$value->surname}}</a></td>
              <td>{{$value->age()}}</td>
              <td>@mdo</td>
            </tr>
            @endforeach
          </tbody>
        </table>


      </div>
    </div>

  </div>
</div>
@endsection
