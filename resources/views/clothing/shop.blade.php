@extends('front.layouts.app')

@section('title', 'Negozio Vestiti')

@section('styles')
<style>
    .clothing-shop-item {
        border: 2px solid #ddd;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        transition: all 0.3s;
        background: white;
    }
    .clothing-shop-item:hover {
        box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        transform: translateY(-3px);
    }
    .rarity-common { border-left: 5px solid #6c757d; }
    .rarity-rare { border-left: 5px solid #007bff; }
    .rarity-epic { border-left: 5px solid #6f42c1; }
    .rarity-legendary { border-left: 5px solid #ffc107; }

    .price-tag {
        font-size: 1.5rem;
        font-weight: bold;
        color: #28a745;
    }
    .stat-info {
        display: inline-block;
        padding: 0.3rem 0.6rem;
        margin: 0.2rem;
        background: #e9ecef;
        border-radius: 4px;
        font-size: 0.85rem;
    }
    .stat-positive {
        background: #d4edda;
        color: #155724;
    }
    .requirements {
        background: #fff3cd;
        padding: 0.5rem;
        border-radius: 4px;
        margin-top: 0.5rem;
    }
</style>
@endsection

@section('content')
<div class="container my-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="fas fa-store"></i> Negozio di Abbigliamento Magico</h2>
            <p class="text-muted">Acquista vestiti magici per migliorare le tue statistiche!</p>
        </div>
        <div class="col-md-4 text-right">
            <div class="card">
                <div class="card-body">
                    <h5>I tuoi Galeoni</h5>
                    <h3 class="text-success">
                        <i class="fas fa-coins"></i> {{ number_format(Auth::user()->money ?? 0) }}
                    </h3>
                    <a href="{{ route('clothing.index') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-tshirt"></i> Il tuo Inventario
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Tabs for Categories -->
    <ul class="nav nav-tabs mb-4" role="tablist">
        @php
            $categories = [
                'hat' => 'Cappelli',
                'cloak' => 'Mantelli',
                'robe' => 'Vesti',
                'shirt' => 'Camicie',
                'pants' => 'Pantaloni',
                'shoes' => 'Scarpe',
                'accessory' => 'Accessori'
            ];
            $first = true;
        @endphp
        @foreach($categories as $key => $label)
            @if(isset($clothing[$key]) && $clothing[$key]->count() > 0)
                <li class="nav-item">
                    <a class="nav-link {{ $first ? 'active' : '' }}" data-toggle="tab" href="#{{ $key }}">
                        {{ $label }} ({{ $clothing[$key]->count() }})
                    </a>
                </li>
                @php $first = false; @endphp
            @endif
        @endforeach
    </ul>

    <!-- Tab Content -->
    <div class="tab-content">
        @php $first = true; @endphp
        @foreach($categories as $key => $label)
            @if(isset($clothing[$key]) && $clothing[$key]->count() > 0)
                <div id="{{ $key }}" class="tab-pane {{ $first ? 'active' : '' }}">
                    <div class="row">
                        @foreach($clothing[$key] as $item)
                            <div class="col-md-6 col-lg-4">
                                <div class="clothing-shop-item rarity-{{ $item->rarity }}">
                                    <h5 class="text-{{ $item->rarity_color }}">
                                        {{ $item->name }}
                                        <span class="badge badge-secondary float-right">
                                            {{ $item->rarityLabel }}
                                        </span>
                                    </h5>

                                    @if($item->description)
                                        <p class="text-muted small">{{ $item->description }}</p>
                                    @endif

                                    <!-- Stats -->
                                    <div class="mt-2">
                                        @if($item->strength_bonus > 0)
                                            <span class="stat-info stat-positive">
                                                <i class="fas fa-fist-raised"></i> Forza +{{ $item->strength_bonus }}
                                            </span>
                                        @endif
                                        @if($item->intelligence_bonus > 0)
                                            <span class="stat-info stat-positive">
                                                <i class="fas fa-brain"></i> Intel. +{{ $item->intelligence_bonus }}
                                            </span>
                                        @endif
                                        @if($item->dexterity_bonus > 0)
                                            <span class="stat-info stat-positive">
                                                <i class="fas fa-running"></i> Destr. +{{ $item->dexterity_bonus }}
                                            </span>
                                        @endif
                                        @if($item->charisma_bonus > 0)
                                            <span class="stat-info stat-positive">
                                                <i class="fas fa-star"></i> Carisma +{{ $item->charisma_bonus }}
                                            </span>
                                        @endif
                                        @if($item->defense_bonus > 0)
                                            <span class="stat-info stat-positive">
                                                <i class="fas fa-shield-alt"></i> Difesa +{{ $item->defense_bonus }}
                                            </span>
                                        @endif
                                        @if($item->magic_bonus > 0)
                                            <span class="stat-info stat-positive">
                                                <i class="fas fa-magic"></i> Magia +{{ $item->magic_bonus }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Requirements -->
                                    @if($item->required_level > 1 || $item->required_house)
                                        <div class="requirements mt-3">
                                            <strong>Requisiti:</strong>
                                            @if($item->required_level > 1)
                                                <div><i class="fas fa-star"></i> Livello {{ $item->required_level }}</div>
                                            @endif
                                            @if($item->required_house)
                                                <div><i class="fas fa-home"></i> Casa: {{ \App\Models\Team::find($item->required_house)->name ?? 'N/A' }}</div>
                                            @endif
                                        </div>
                                    @endif

                                    <!-- Price and Purchase -->
                                    <div class="mt-3 d-flex justify-content-between align-items-center">
                                        <div class="price-tag">
                                            <i class="fas fa-coins"></i> {{ number_format($item->price) }}
                                        </div>
                                        <div>
                                            @php
                                                $hasItem = Auth::user()->clothing()->where('clothing_id', $item->id)->exists();
                                                $canBuy = $item->canBeWornBy(Auth::user()) && Auth::user()->money >= $item->price;
                                            @endphp

                                            @if($hasItem)
                                                <button class="btn btn-secondary" disabled>
                                                    <i class="fas fa-check"></i> Posseduto
                                                </button>
                                            @elseif(!$canBuy)
                                                <button class="btn btn-outline-danger" disabled>
                                                    <i class="fas fa-lock"></i> Non disponibile
                                                </button>
                                            @else
                                                <form action="{{ route('clothing.purchase') }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="clothing_id" value="{{ $item->id }}">
                                                    <button type="submit" class="btn btn-success"
                                                            onclick="return confirm('Confermi l\'acquisto di {{ $item->name }} per {{ number_format($item->price) }} Galeoni?')">
                                                        <i class="fas fa-shopping-cart"></i> Acquista
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @php $first = false; @endphp
            @endif
        @endforeach
    </div>

    @if($clothing->isEmpty())
        <div class="alert alert-info text-center">
            <h4><i class="fas fa-exclamation-circle"></i> Negozio Vuoto</h4>
            <p>Al momento non ci sono vestiti disponibili. Torna più tardi!</p>
        </div>
    @endif
</div>
@endsection
