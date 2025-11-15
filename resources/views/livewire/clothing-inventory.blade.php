<div>
    @if(session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('message') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif
    @if(session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <!-- Stats Summary -->
    @if($showStats)
    <div class="card mb-3 bg-light">
        <div class="card-body p-2">
            <h6 class="mb-2"><i class="fas fa-chart-bar"></i> Bonus Totali:</h6>
            <div class="row text-center small">
                <div class="col-4 mb-1">
                    <strong class="text-danger">{{ $stats['strength'] }}</strong>
                    <div class="text-muted" style="font-size: 0.7rem;">Forza</div>
                </div>
                <div class="col-4 mb-1">
                    <strong class="text-info">{{ $stats['intelligence'] }}</strong>
                    <div class="text-muted" style="font-size: 0.7rem;">Intel.</div>
                </div>
                <div class="col-4 mb-1">
                    <strong class="text-success">{{ $stats['dexterity'] }}</strong>
                    <div class="text-muted" style="font-size: 0.7rem;">Destr.</div>
                </div>
                <div class="col-4 mb-1">
                    <strong class="text-warning">{{ $stats['charisma'] }}</strong>
                    <div class="text-muted" style="font-size: 0.7rem;">Carisma</div>
                </div>
                <div class="col-4 mb-1">
                    <strong class="text-primary">{{ $stats['defense'] }}</strong>
                    <div class="text-muted" style="font-size: 0.7rem;">Difesa</div>
                </div>
                <div class="col-4 mb-1">
                    <strong style="color: #6f42c1;">{{ $stats['magic'] }}</strong>
                    <div class="text-muted" style="font-size: 0.7rem;">Magia</div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Equipped Slots -->
    @foreach($slots as $slot => $label)
        <div class="equipped-slot mb-2 {{ isset($equipped[$slot]) ? 'equipped-item' : '' }}"
             data-slot="{{ $slot }}"
             ondrop="handleDrop(event)"
             ondragover="handleDragOver(event)"
             ondragleave="handleDragLeave(event)">
            <strong>{{ $label }}</strong>
            @if(isset($equipped[$slot]))
                @php $item = $equipped[$slot]->clothing; @endphp
                <div class="mt-2">
                    <span class="rarity-{{ $item->rarity }}">
                        {{ $item->name }}
                    </span>
                    <div class="mt-1">
                        <small class="text-muted">{{ $item->rarityLabel }}</small>
                        @if($item->total_bonus > 0)
                            <small class="text-success d-block">
                                <i class="fas fa-arrow-up"></i> +{{ $item->total_bonus }} stats
                            </small>
                        @endif
                    </div>
                    <button wire:click="unequipSlot('{{ $slot }}')" class="btn btn-sm btn-danger mt-2">
                        <i class="fas fa-times"></i> Rimuovi
                    </button>
                </div>
            @else
                <div class="mt-2">
                    <small class="text-muted">Vuoto</small>
                </div>
            @endif
        </div>
    @endforeach

    <!-- Inventory Items (only show on main inventory page) -->
    @if(request()->routeIs('clothing.index'))
        <hr class="my-4">
        <h5><i class="fas fa-box"></i> Il tuo Inventario</h5>
        <p class="text-muted small">
            <i class="fas fa-info-circle"></i> Trascina i vestiti sugli slot per equipaggiarli
        </p>

        @if($inventory->isEmpty())
            <div class="alert alert-info">
                Non hai ancora vestiti. Visita il <a href="{{ route('clothing.shop') }}">negozio</a>!
            </div>
        @else
            @foreach($inventory as $type => $items)
                <h6 class="mt-3">{{ $slots[$type] ?? ucfirst($type) }}</h6>
                @foreach($items as $clothing)
                    <div class="clothing-item rarity-{{ $clothing->rarity }} mb-2"
                         draggable="true"
                         data-clothing-id="{{ $clothing->id }}"
                         ondragstart="handleDragStart(event)">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <strong class="text-{{ $clothing->rarity_color }}">
                                    {{ $clothing->name }}
                                </strong>
                                <span class="badge badge-secondary ml-2">{{ $clothing->rarityLabel }}</span>

                                @if($clothing->required_level > 1)
                                    <span class="badge badge-warning ml-1">Lv. {{ $clothing->required_level }}</span>
                                @endif

                                <div class="mt-1 small">
                                    @if($clothing->strength_bonus > 0)
                                        <span class="stat-bonus positive">
                                            <i class="fas fa-fist-raised"></i> +{{ $clothing->strength_bonus }}
                                        </span>
                                    @endif
                                    @if($clothing->intelligence_bonus > 0)
                                        <span class="stat-bonus positive">
                                            <i class="fas fa-brain"></i> +{{ $clothing->intelligence_bonus }}
                                        </span>
                                    @endif
                                    @if($clothing->dexterity_bonus > 0)
                                        <span class="stat-bonus positive">
                                            <i class="fas fa-running"></i> +{{ $clothing->dexterity_bonus }}
                                        </span>
                                    @endif
                                    @if($clothing->charisma_bonus > 0)
                                        <span class="stat-bonus positive">
                                            <i class="fas fa-star"></i> +{{ $clothing->charisma_bonus }}
                                        </span>
                                    @endif
                                    @if($clothing->defense_bonus > 0)
                                        <span class="stat-bonus positive">
                                            <i class="fas fa-shield-alt"></i> +{{ $clothing->defense_bonus }}
                                        </span>
                                    @endif
                                    @if($clothing->magic_bonus > 0)
                                        <span class="stat-bonus positive">
                                            <i class="fas fa-magic"></i> +{{ $clothing->magic_bonus }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <button wire:click="equipClothing({{ $clothing->id }})"
                                        class="btn btn-sm btn-success">
                                    <i class="fas fa-check"></i> Equipaggia
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforeach
        @endif
    @endif
</div>

<script>
function handleDragStart(e) {
    e.dataTransfer.effectAllowed = 'move';
    e.dataTransfer.setData('clothing-id', e.target.dataset.clothingId);
    e.target.classList.add('dragging');
}

function handleDragOver(e) {
    if (e.preventDefault) {
        e.preventDefault();
    }
    e.dataTransfer.dropEffect = 'move';
    e.currentTarget.classList.add('drag-over');
    return false;
}

function handleDragLeave(e) {
    e.currentTarget.classList.remove('drag-over');
}

function handleDrop(e) {
    if (e.stopPropagation) {
        e.stopPropagation();
    }
    e.currentTarget.classList.remove('drag-over');

    const clothingId = e.dataTransfer.getData('clothing-id');

    // Call Livewire method
    @this.call('equipClothing', clothingId);

    // Remove dragging class
    document.querySelectorAll('.dragging').forEach(el => {
        el.classList.remove('dragging');
    });

    return false;
}
</script>

<style>
.clothing-item {
    cursor: move;
    border: 2px solid #ddd;
    padding: 1rem;
    border-radius: 8px;
    background: white;
    transition: all 0.3s;
}
.clothing-item:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}
.clothing-item.dragging {
    opacity: 0.5;
}
.equipped-slot {
    border: 2px dashed #ddd;
    padding: 1rem;
    text-align: center;
    border-radius: 8px;
    min-height: 100px;
    background: #f8f9fa;
    transition: all 0.3s;
}
.equipped-slot.drag-over {
    border-color: #28a745;
    background: #d4edda;
}
.equipped-item {
    border: 2px solid #28a745;
    background: white;
}
.rarity-common { border-left: 4px solid #6c757d; }
.rarity-rare { border-left: 4px solid #007bff; }
.rarity-epic { border-left: 4px solid #6f42c1; }
.rarity-legendary { border-left: 4px solid #ffc107; }

.stat-bonus {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    margin: 0.1rem;
    background: #e9ecef;
    border-radius: 4px;
    font-size: 0.75rem;
}
.stat-bonus.positive {
    background: #d4edda;
    color: #155724;
}
</style>
