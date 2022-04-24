@extends('admin.layouts.app')

@section('content')


  <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Crea un nuovo utente</h3>
              </div>
              <!-- /.box-header -->
              <!-- form start -->
            {{ Form::open(['files' => true]) }}
                <div class="box-body">
                  <div class="form-group">
                    <label>Username</label>
                    <input type="text" class="form-control" name="username" placeholder="username" required>
                  </div>
                  <div class="form-group">
                    <label>Password</label>
                    <input type="text" class="form-control" name="password" placeholder="password" required>
                  </div>
                  <div class="form-group">
                    <label>Email</label>
                    <input type="text" class="form-control" name="email" placeholder="email" required>
                  </div>

                  <div class="form-group">
                    <label>Nome</label>
                    <input type="text" class="form-control" name="name" placeholder="name" required>
                  </div>
                  <div class="form-group">
                    <label>Cognome</label>
                    <input type="text" class="form-control" name="surname" placeholder="surname" required>
                  </div>

                  <div class="form-group row {{ $errors->has('birthday') ? 'has-error' : '' }}">
                    {!! Form::label('birthday', 'Data di nascita', ['class' => 'col-md-4 col-form-label text-md-right']) !!}
                    <div class="col-md-6">
                      <div class="form-inline">
                        {!! Form::selectRange('day', 1, 31, null, ['class' => 'form-control']) !!} {!! Form::selectMonth('month', null, ['class' => 'form-control']) !!} {!! Form::selectYear('year', date('Y') - 3, date('Y') - 55, null, ['class' => 'form-control']) !!}
                      </div>
                      {{ $errors->first('birthday', '<span class="help-block">:message</span>') }}
                    </div>
                  </div>


                  <div class="form-group">
                    <label>Telegram</label>
                    <input type="text" class="form-control" name="telegram" placeholder="telegram" required>
                  </div>

                  <div class="form-group">
                    <label>Soldi</label>
                    <input type="text" class="form-control" name="money" placeholder="money" required>
                  </div>
                  <div class="form-group">
                    <label>Livello</label>
                    <input type="text" class="form-control" name="level" placeholder="level" required>
                  </div>
                  <div class="form-group">
                    <label>Esperienza</label>
                    <input type="text" class="form-control" name="exp" placeholder="exp" required>
                  </div>

                  <select name="sex" class="form-control">
                    <option value="0">Maschio</option>
                    <option value="1">Femmina</option>
                  </select>

                  <select name="mago" class="form-control">
                    <option value="0">Senza Bacchetta</option>
                    <option value="1">Con Bacchetta</option>
                  </select>

                  <select name="group" class="form-control">
                    <option value="0">Utente</option>
                    <option value="1">Moderatore</option>
                    <option value="2">Admin</option>
                  </select>

                  <select name="team" class="form-control">
                    <option value="1">Utente</option>
                    <option value="2">Moderatore</option>
                    <option value="3">Admin</option>
                    <option value="4">Admin</option>
                  </select>

                  <div class="form-group">
                    <label>Immagine</label>
                    <input type="file" name="avatar">
                    <p class="help-block">L'immagine sarà ridimensionata a 250x250</p>
                  </div>

                  <div class="form-group">
                    <label>Biografia</label>
                    <textarea name="biography"></textarea>
                  </div>


                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                  <button class="btn btn-success form-control">Crea</button>
                </div>
              </form>
            </div>


@endsection
