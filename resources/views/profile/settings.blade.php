@extends('front.layouts.app')

@section('title', 'Impostazioni Profilo')

@section('content')
<div class="container my-5">
    <h2 class="mb-4"><i class="fas fa-cog"></i> Impostazioni Profilo</h2>

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user-cog"></i> Privacy e Visibilità</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update-settings') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="profile_public"
                                       name="profile_public" value="1"
                                       {{ $user->profile_public ? 'checked' : '' }}>
                                <label class="custom-control-label" for="profile_public">
                                    <strong>Profilo Pubblico</strong>
                                </label>
                            </div>
                            <small class="form-text text-muted">
                                Se disabilitato, solo tu potrai vedere il tuo profilo
                            </small>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="show_inventory"
                                       name="show_inventory" value="1"
                                       {{ $user->show_inventory ? 'checked' : '' }}>
                                <label class="custom-control-label" for="show_inventory">
                                    <strong>Mostra Equipaggiamento</strong>
                                </label>
                            </div>
                            <small class="form-text text-muted">
                                Permetti agli altri di vedere i tuoi vestiti equipaggiati
                            </small>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="show_stats"
                                       name="show_stats" value="1"
                                       {{ $user->show_stats ? 'checked' : '' }}>
                                <label class="custom-control-label" for="show_stats">
                                    <strong>Mostra Statistiche</strong>
                                </label>
                            </div>
                            <small class="form-text text-muted">
                                Permetti agli altri di vedere le tue statistiche dai vestiti
                            </small>
                        </div>

                        <hr>

                        <div class="form-group">
                            <label for="profile_title">
                                <strong>Titolo Profilo</strong>
                            </label>
                            <input type="text" class="form-control" id="profile_title"
                                   name="profile_title" value="{{ $user->profile_title }}"
                                   placeholder="Es: Il Grande Mago" maxlength="50">
                            <small class="form-text text-muted">
                                Un titolo personalizzato che apparirà sul tuo profilo (max 50 caratteri)
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="biography">
                                <strong>Biografia</strong>
                            </label>
                            <textarea class="form-control" id="biography" name="biography"
                                      rows="6" maxlength="1000"
                                      placeholder="Racconta qualcosa su di te...">{{ $user->biography }}</textarea>
                            <small class="form-text text-muted">
                                Descrivi il tuo personaggio (max 1000 caratteri)
                            </small>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Salva Modifiche
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-eye"></i> Anteprima</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <img src="{{ asset('upload/user/' . $user->avatar()) }}"
                             class="rounded-circle"
                             style="width: 100px; height: 100px; object-fit: cover;"
                             alt="{{ $user->username }}">
                    </div>
                    <h5 class="text-center">{{ $user->username }}</h5>
                    @if($user->profile_title)
                        <p class="text-center text-muted">{{ $user->profile_title }}</p>
                    @endif
                    <hr>
                    <p class="small text-muted mb-1">
                        <i class="fas fa-home"></i> {{ $user->team() }}
                    </p>
                    <p class="small text-muted mb-1">
                        <i class="fas fa-star"></i> Livello {{ $user->level ?? 1 }}
                    </p>
                    <p class="small text-muted mb-1">
                        <i class="fas fa-eye"></i> {{ $user->profile_views }} visualizzazioni
                    </p>
                    <hr>
                    <a href="/user/{{ $user->slug }}" class="btn btn-sm btn-primary btn-block">
                        <i class="fas fa-user"></i> Visualizza Profilo Pubblico
                    </a>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Info</h5>
                </div>
                <div class="card-body">
                    <p class="small">
                        Il tuo profilo pubblico permette agli altri utenti di vedere le tue informazioni,
                        statistiche e vestiti equipaggiati.
                    </p>
                    <p class="small mb-0">
                        Puoi controllare cosa mostrare utilizzando le opzioni di privacy.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
