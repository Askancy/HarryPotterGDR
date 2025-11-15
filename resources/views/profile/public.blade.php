@extends('front.layouts.app')

@section('title', $user->username . ' - Profilo')

@section('styles')
<style>
    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 3rem 0;
        color: white;
        margin-bottom: 2rem;
    }
    .profile-avatar {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        border: 5px solid white;
        object-fit: cover;
    }
    .equipped-slot {
        border: 2px dashed #ddd;
        padding: 1rem;
        text-align: center;
        border-radius: 8px;
        min-height: 120px;
        background: #f8f9fa;
    }
    .equipped-item {
        border: 2px solid #28a745;
        background: white;
    }
    .rarity-common { color: #6c757d; }
    .rarity-rare { color: #007bff; }
    .rarity-epic { color: #6f42c1; }
    .rarity-legendary { color: #ffc107; }
    .stat-badge {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        margin: 0.25rem;
        background: #e9ecef;
        border-radius: 4px;
        font-size: 0.875rem;
    }
</style>
@endsection

@section('content')
<div class="profile-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-3 text-center">
                <img src="{{ asset('upload/user/' . $user->avatar()) }}" class="profile-avatar" alt="{{ $user->username }}">
            </div>
            <div class="col-md-9">
                <h1 class="mb-2">{{ $user->username }}</h1>
                @if($user->profile_title)
                    <h5 class="text-white-50">{{ $user->profile_title }}</h5>
                @endif
                <p class="mb-2">
                    <i class="fas fa-home"></i> {{ $user->team() }} |
                    <i class="fas fa-star"></i> Livello {{ $user->level ?? 1 }} |
                    <i class="fas fa-eye"></i> {{ $user->profile_views }} visualizzazioni
                </p>
                <p class="mb-0">
                    <i class="fas fa-users"></i> {{ $friendsCount }} Amici
                </p>

                @auth
                    @if(Auth::id() != $user->id)
                        <div class="mt-3">
                            @if($isFriend)
                                <span class="badge badge-success"><i class="fas fa-check"></i> Amici</span>
                                <a href="{{ route('messages.index') }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-envelope"></i> Invia Messaggio
                                </a>
                            @elseif($hasPendingRequest)
                                <span class="badge badge-warning">Richiesta in sospeso</span>
                            @else
                                <form action="{{ route('friends.send-request') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="fas fa-user-plus"></i> Aggiungi agli Amici
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</div>

<div class="container mb-5">
    <div class="row">
        <!-- Biografia -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Biografia</h5>
                </div>
                <div class="card-body">
                    @if($user->biography)
                        {!! nl2br(e($user->biography)) !!}
                    @else
                        <p class="text-muted">Nessuna biografia disponibile.</p>
                    @endif
                </div>
            </div>

            <!-- Statistiche -->
            @if($user->show_stats || Auth::id() == $user->id)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Statistiche dai Vestiti</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-4 mb-3">
                                <h4 class="text-danger">{{ $clothingStats['strength'] }}</h4>
                                <small class="text-muted">Forza</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <h4 class="text-info">{{ $clothingStats['intelligence'] }}</h4>
                                <small class="text-muted">Intelligenza</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <h4 class="text-success">{{ $clothingStats['dexterity'] }}</h4>
                                <small class="text-muted">Destrezza</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <h4 class="text-warning">{{ $clothingStats['charisma'] }}</h4>
                                <small class="text-muted">Carisma</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <h4 class="text-primary">{{ $clothingStats['defense'] }}</h4>
                                <small class="text-muted">Difesa</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <h4 class="text-purple">{{ $clothingStats['magic'] }}</h4>
                                <small class="text-muted">Magia</small>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Vestiti Equipaggiati -->
        <div class="col-md-4">
            @if($user->show_inventory || Auth::id() == $user->id)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-tshirt"></i> Equipaggiamento</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $slots = [
                                'hat' => 'Cappello',
                                'cloak' => 'Mantello',
                                'robe' => 'Veste',
                                'shirt' => 'Camicia',
                                'pants' => 'Pantaloni',
                                'shoes' => 'Scarpe',
                                'accessory' => 'Accessorio'
                            ];
                        @endphp

                        @foreach($slots as $slot => $label)
                            <div class="equipped-slot mb-2 {{ isset($equippedClothing[$slot]) ? 'equipped-item' : '' }}">
                                <strong>{{ $label }}</strong>
                                @if(isset($equippedClothing[$slot]))
                                    @php $item = $equippedClothing[$slot]->clothing; @endphp
                                    <div class="mt-2">
                                        <span class="rarity-{{ $item->rarity }}">
                                            {{ $item->name }}
                                        </span>
                                        @if($item->total_bonus > 0)
                                            <div class="mt-1">
                                                <small class="text-success">
                                                    <i class="fas fa-arrow-up"></i> +{{ $item->total_bonus }} stats
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="mt-2">
                                        <small class="text-muted">Vuoto</small>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
