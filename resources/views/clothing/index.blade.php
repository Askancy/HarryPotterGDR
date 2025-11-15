@extends('front.layouts.app')

@section('title', 'Inventario Vestiti')

@section('styles')
<style>
    .clothing-item {
        cursor: move;
        border: 2px solid #ddd;
        padding: 1rem;
        margin-bottom: 0.5rem;
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
        padding: 1.5rem;
        text-align: center;
        border-radius: 8px;
        min-height: 120px;
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
@endsection

@section('content')
<div class="container my-5">
    <h2 class="mb-4"><i class="fas fa-tshirt"></i> Inventario Vestiti</h2>

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <!-- Equipaggiamento Attuale -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Equipaggiamento</h5>
                </div>
                <div class="card-body">
                    @livewire('clothing-inventory')
                </div>
            </div>
        </div>

        <!-- Inventario -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-box"></i> Il tuo Inventario</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        <i class="fas fa-info-circle"></i> Trascina i vestiti sugli slot per equipaggiarli
                    </p>

                    @livewire('clothing-inventory')
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-shopping-bag"></i> Negozio Vestiti</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('clothing.shop') }}" class="btn btn-primary">
                        <i class="fas fa-store"></i> Visita il Negozio
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Drag and Drop functionality
document.addEventListener('DOMContentLoaded', function() {
    const clothingItems = document.querySelectorAll('.clothing-item');
    const slots = document.querySelectorAll('.equipped-slot');

    clothingItems.forEach(item => {
        item.addEventListener('dragstart', function(e) {
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/html', this.innerHTML);
            e.dataTransfer.setData('clothing-id', this.dataset.clothingId);
            this.classList.add('dragging');
        });

        item.addEventListener('dragend', function() {
            this.classList.remove('dragging');
        });
    });

    slots.forEach(slot => {
        slot.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
            this.classList.add('drag-over');
        });

        slot.addEventListener('dragleave', function() {
            this.classList.remove('drag-over');
        });

        slot.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('drag-over');

            const clothingId = e.dataTransfer.getData('clothing-id');

            // Send AJAX request to equip clothing
            fetch('/clothing/equip', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    clothing_id: clothingId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message);
                }
            });
        });
    });
});
</script>
@endsection
