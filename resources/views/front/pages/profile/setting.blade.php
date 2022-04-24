@extends('front.layouts.app')

@section('content')
<div class="container rc sk-container">
    <div class="row justify-content-center" style="margin-bottom: 35px">

                <h3 id="titlepage">Impostazioni del Profilo</h3>


  {{ Form::open(['files' => true]) }}
    <div class="col-12">
      <div class="row justify-content-center">
        <div class="col-12 text-center">
          <img src="{{url('upload/user/'.$user->avatar())}}" style="padding: 15px">
          <center><input type="file" class="form-control-file" name="avatar" id="avatar" aria-describedby="fileHelp"></center>
        </div>


        <hr>


          <div class="col-6" style="margin-top: 25px">
            <div class="row">
              <div class="col-md-6 col-sm-12">
                <div class="form-group">
                  <div class="input-group">
                    <input type="text" class="form-control" name="email" value="{{$user->email}}" placeholder="email@email.it">
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-sm-12">
                <div class="form-group">
                  <div class="input-group">
                    <input type="text" class="form-control" name="telegram" value="{{$user->telegram}}" placeholder="telegram">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12" style="margin-bottom: 35px">
              <label>Biografia:</label>
              <textarea class="form-control" name="biography">{{$user->biography}}</textarea>
          </div>

          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-shadow btn-block">Salva modifiche</button>
          </div>
  {{ Form::close() }}
      </div>
      <hr>
      <div class="col-12 m-t-25">
        <h3>Cambia Password</h3>
        {!! Form::open(array('url'=>'profilo/changepassword','id'=>'form-password','class'=>'col-12')) !!}
        {{csrf_field()}}
        <div class="col-12">
          <div class="row offset-md-2">


            <div class="col-md-3 col-sm-12 form-group{{ $errors->has('old') ? ' has-error' : '' }}">
              <label for="password">Vecchia Password</label>
              <input class="form-control" id="old" type="password" name="old">
              @if ($errors->has('old'))
                <small id="emailHelp" class="form-text help-block">{{ $errors->first('old') }}</small>
              @endif
            </div>
            <div class="col-md-3 col-sm-12 form-group {{ $errors->has('password') ? ' has-error' : '' }}">
              <label for="password">Nuova Password</label>
              <input class="form-control" id="password" type="password" name="password">
              @if ($errors->has('password'))
                <small id="emailHelp" class="form-text help-block">{{ $errors->first('password') }}</small>
              @endif
            </div>
            <div class="col-md-3 col-sm-12 form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
              <label for="password">Ripeti Password</label>
              <input class="form-control" id="password_confirmation" type="password" name="password_confirmation">
              @if ($errors->has('password_confirmation'))
                <small id="emailHelp" class="form-text help-block">{{ $errors->first('password_confirmation') }}</small>
              @endif
            </div>
          </div>
          <button type="submit" class="btn btn-danger btn-shadow  btn-block">Cambia Password</button>

        </div>

      </form>
    </div>





    </div>
</div>
</div>
@endsection
