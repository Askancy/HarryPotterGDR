@extends('front.layouts.app')

@section('title', 'Talenti e Abilità')

@section('styles')
<style>
    .perk-tree {
        padding: 2rem 0;
    }
    .perk-card {
        border: 2px solid #dee2e6;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        transition: all 0.3s;
        position: relative;
    }
    .perk-card:hover {
        box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        transform: translateY(-3px);
    }
    .perk-unlocked {
        border-color: #28a745;
        background: #d4edda;
    }
    .perk-locked {
        opacity: 0.6;
        background: #f8f9fa;
    }
    .perk-category-header {
        border-left: 5px solid;
        padding-left: 1rem;
    }
    .stats-panel {
        position: sticky;
        top: 20px;
    }
    .requirement-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
</style>
@endsection

@section('content')
<div class="container my-5">
    <div class="row">
        <!-- Perk Tree -->
        <div class="col-md-8">
            <h2 class="mb-4"><i class="fas fa-star"></i> Albero dei Talenti</h2>

            @if(session('message'))
                <div class="alert alert-success">{{ session('message') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @foreach($categories as $category)
                <div class="perk-category-header mb-3" style="border-color: {{ $category->color }}">
                    <h4>
                        <i class="fas {{ $category->icon ?? 'fa-star' }}"></i>
                        {{ $category->name }}
                    </h4>
                    @if($category->description)
                        <p class="text-muted">{{ $category->description }}</p>
                    @endif
                </div>

                <div class="perk-tree mb-5">
                    @foreach($category->perks as $perk)
                        @php
                            $hasPerк = isset($userPerks[$perk->id]);
                            $canUnlock = !$hasPerк && $perk->canBeUnlockedBy($user);
                        @endphp

                        <div class="perk-card {{ $hasPerk ? 'perk-unlocked' : ($canUnlock ? '' : 'perk-locked') }}">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h5>
                                        @if($hasPerk)
                                            <i class="fas fa-check-circle text-success"></i>
                                        @else
                                            <i class="fas fa-lock"></i>
                                        @endif
                                        {{ $perk->name }}
                                        <span class="badge badge-secondary ml-2">{{ $perk->typeLabel }}</span>
                                    </h5>
                                    <p class="mb-2">{{ $perk->description }}</p>

                                    <!-- Requirements -->
                                    <div class="mb-2">
                                        @if($perk->required_level > 1)
                                            <span class="badge requirement-badge badge-{{ $user->level >= $perk->required_level ? 'success' : 'danger' }}">
                                                <i class="fas fa-star"></i> Livello {{ $perk->required_level }}
                                            </span>
                                        @endif
                                        @if($perk->required_perk_id)
                                            <span class="badge requirement-badge badge-info">
                                                <i class="fas fa-link"></i> Richiede altro talento
                                            </span>
                                        @endif
                                        @if($perk->required_skill)
                                            @php [$skill, $value] = explode(':', $perk->required_skill); @endphp
                                            <span class="badge requirement-badge badge-warning">
                                                <i class="fas fa-chart-bar"></i> {{ ucfirst($skill) }} {{ $value }}
                                            </span>
                                        @endif
                                        @if($perk->required_subject)
                                            @php [$subj, $lvl] = explode(':', $perk->required_subject); @endphp
                                            <span class="badge requirement-badge badge-primary">
                                                <i class="fas fa-book"></i> {{ ucfirst($subj) }} Lv.{{ $lvl }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Effects -->
                                    @if(isset($perk->effects['stat_bonuses']))
                                        <div class="mt-2">
                                            <strong>Bonus:</strong>
                                            @foreach($perk->effects['stat_bonuses'] as $stat => $bonus)
                                                <span class="badge badge-success">
                                                    +{{ $bonus }} {{ ucfirst($stat) }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-4 text-right">
                                    @if($hasPerk)
                                        <div>
                                            <span class="badge badge-success mb-2">Sbloccato</span>
                                            @if($perk->type === 'toggle')
                                                <form action="{{ route('perks.toggle', $perk->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-{{ $userPerks[$perk->id]->pivot->is_equipped ? 'warning' : 'primary' }}">
                                                        {{ $userPerks[$perk->id]->pivot->is_equipped ? 'Disattiva' : 'Attiva' }}
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    @elseif($canUnlock)
                                        <form action="{{ route('perks.unlock', $perk->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-unlock"></i> Sblocca
                                                <br><small>({{ $perk->perk_points_cost }} Punti)</small>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">
                                            <i class="fas fa-lock"></i> Bloccato
                                            <br><small>Costo: {{ $perk->perk_points_cost }} punti</small>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>

        <!-- Stats Panel -->
        <div class="col-md-4">
            <div class="stats-panel">
                <div class="card">
                    <div class="card-header bg-warning text-white">
                        <h5 class="mb-0"><i class="fas fa-star"></i> Punti Talento</h5>
                    </div>
                    <div class="card-body text-center">
                        <h1 class="display-4">{{ $user->perk_points }}</h1>
                        <p class="text-muted">Punti disponibili</p>
                        <small>Totale guadagnati: {{ $user->total_perk_points_earned }}</small>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="fas fa-info-circle"></i> Come Guadagnare Punti</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled small">
                            <li class="mb-2"><i class="fas fa-fire text-danger"></i> <strong>7 giorni consecutivi</strong> di lezioni = 1 punto</li>
                            <li class="mb-2"><i class="fas fa-level-up-alt text-success"></i> <strong>Salendo di livello</strong> = 1-2 punti</li>
                            <li class="mb-2"><i class="fas fa-trophy text-warning"></i> <strong>Completando achievement</strong> = 1-3 punti</li>
                        </ul>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="fas fa-chart-bar"></i> Le Tue Statistiche</h6>
                    </div>
                    <div class="card-body">
                        @php $totalStats = $user->getTotalStats(); @endphp
                        <div class="mb-2">
                            <strong>Forza:</strong> {{ $totalStats['strength'] }}
                            <small class="text-muted">(base: {{ $user->strength }})</small>
                        </div>
                        <div class="mb-2">
                            <strong>Intelligenza:</strong> {{ $totalStats['intelligence'] }}
                            <small class="text-muted">(base: {{ $user->intelligence }})</small>
                        </div>
                        <div class="mb-2">
                            <strong>Destrezza:</strong> {{ $totalStats['dexterity'] }}
                            <small class="text-muted">(base: {{ $user->dexterity }})</small>
                        </div>
                        <div class="mb-2">
                            <strong>Carisma:</strong> {{ $totalStats['charisma'] }}
                            <small class="text-muted">(base: {{ $user->charisma }})</small>
                        </div>
                        <div class="mb-2">
                            <strong>Difesa:</strong> {{ $totalStats['defense'] }}
                            <small class="text-muted">(base: {{ $user->defense }})</small>
                        </div>
                        <div class="mb-2">
                            <strong>Potere Magico:</strong> {{ $totalStats['magic_power'] }}
                            <small class="text-muted">(base: {{ $user->magic_power }})</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
