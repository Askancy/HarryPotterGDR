@extends('admin.layouts.app')

@section('content')


  <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Modifica utente</h3>
              </div>
              <!-- /.box-header -->
              <!-- form start -->
            {{ Form::open(['files' => true]) }}
                <div class="box-body">


                  <div class="box box-danger">
                      <div class="box-header with-border">
                        <h3 class="box-title">Informazioni Personali</h3>
                      </div>
                      <div class="box-body">
                        <div class="row">
                          <div class="col-xs-3">
                            <input type="text" class="form-control" name="name" placeholder="name" value="{{$user->name}}" required>
                          </div>
                          <div class="col-xs-4">
                            <input type="text" class="form-control" name="surname" placeholder="surname" value="{{$user->surname}}" required>
                          </div>
                          <div class="col-xs-5">
                            <input type="text" class="form-control" name="telegram" placeholder="telegram" value="{{$user->telegram}}" required>
                          </div>
                        </div>



                      </div>
                    </div>

                    <div class="box box-danger">
                        <div class="box-header with-border">
                          <h3 class="box-title">Informazioni Login</h3>
                        </div>
                        <div class="box-body">
                          <div class="row">
                            <div class="col-xs-3">
                              <input type="text" class="form-control" name="username" placeholder="username" value="{{$user->username}}" required>
                            </div>
                            <div class="col-xs-4">
                              <input type="text" class="form-control" name="password" placeholder="password" value="{{$user->password}}" required>
                            </div>
                            <div class="col-xs-5">
                              <input type="text" class="form-control" name="email" placeholder="email" value="{{$user->email}}" required>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="box box-danger">
                          <div class="box-header with-border">
                            <h3 class="box-title">Informazioni Gioco</h3>
                          </div>
                          <div class="box-body">
                            <div class="row">
                              <div class="col-xs-3">
                                <input type="text" class="form-control" name="money" placeholder="money" value="{{$user->money}}" required>
                              </div>
                              <div class="col-xs-4">
                                <input type="text" class="form-control" name="level" placeholder="level" value="{{$user->level}}" required>
                              </div>
                              <div class="col-xs-5">
                                <input type="text" class="form-control" name="exp" placeholder="exp" value="{{$user->exp}}" required>
                              </div>
                            </div>

                            <div class="row">
                              <div class="col-xs-3">
                                <select name="sex" class="form-control">
                                  <option value="0" @if ($user->sex == 0) Selected @endif>Maschio</option>
                                  <option value="1" @if ($user->sex == 0) Selected @endif>Femmina</option>
                                </select>
                              </div>
                              <div class="col-xs-4">
                                <select name="mago" class="form-control">
                                  <option value="0" @if ($user->mago == 0) Selected @endif>Senza Bacchetta</option>
                                  <option value="1" @if ($user->mago == 1) Selected @endif>Con Bacchetta</option>
                                </select>
                              </div>
                              <div class="col-xs-5">
                                <select name="group" class="form-control">
                                  <option value="0" @if ($user->group == 0) Selected @endif>Utente</option>
                                  <option value="1" @if ($user->group == 1) Selected @endif>Moderatore</option>
                                  <option value="2" @if ($user->group == 2) Selected @endif>Admin</option>
                                </select>
                              </div>
                            </div>


                          </div>
                        </div>


                        <div class="box box-danger">
                            <div class="box-header with-border">
                              <h3 class="box-title">Biografia</h3>
                            </div>
                            <div class="box-body">
                              <div class="row">
                                <div class="col-xs-12">
                                  <textarea name="biography" class="form-control">{{$user->biography}}</textarea>
                                </div>
                              </div>
                            </div>
                          </div>


                          <div class="box box-danger">
                              <div class="box-header with-border">
                                <h3 class="box-title">Informazioni Login</h3>
                              </div>
                              <div class="box-body">
                                <div class="row">
                                  <div class="col-xs-3">
                                    <select name="team" class="form-control">
                                      <option value="1" @if ($user->team == 1) Selected @endif>Grifondoro</option>
                                      <option value="2" @if ($user->team == 2) Selected @endif>Tassorosso</option>
                                      <option value="3" @if ($user->team == 3) Selected @endif>Corvonero</option>
                                      <option value="4" @if ($user->team == 4) Selected @endif>Serpeverde</option>
                                    </select>
                                  </div>
                                  <div class="col-xs-4">
                                  </div>
                                  <div class="col-xs-5">
                                    <div class="form-group">
                                      <label>Immagine</label>
                                      <input type="file" name="avatar">
                                      <p class="help-block">L'immagine sarà ridimensionata a 250x250</p>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>








                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                  <button class="btn btn-success form-control">Salve Modifiche</button>
                </div>
              </form>
            </div>


@endsection
