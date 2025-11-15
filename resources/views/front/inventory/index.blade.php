@extends('front.layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-8" x-data="inventoryManager()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-magic font-bold text-gray-900">
                        <i class="fas fa-backpack mr-3 text-indigo-600"></i>
                        Il Tuo Inventario
                    </h1>
                    <p class="mt-2 text-sm text-gray-600">
                        Gestisci i tuoi oggetti magici, equipaggiamento e pozioni
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Capacità Inventario</p>
                    <p class="text-2xl font-bold text-indigo-600">
                        <span x-text="items.length"></span> / {{ $maxSlots ?? 50 }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Stats Bar -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-4 mb-8">
            <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-yellow-500">
                <div class="flex items-center">
                    <i class="fas fa-coins text-3xl text-yellow-500 mr-4"></i>
                    <div>
                        <p class="text-sm text-gray-600">Galeoni</p>
                        <p class="text-2xl font-bold text-gray-900">{{ Auth::user()->money ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-red-500">
                <div class="flex items-center">
                    <i class="fas fa-fist-raised text-3xl text-red-500 mr-4"></i>
                    <div>
                        <p class="text-sm text-gray-600">Attacco</p>
                        <p class="text-2xl font-bold text-gray-900" x-text="totalStats.attack"></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-blue-500">
                <div class="flex items-center">
                    <i class="fas fa-shield-alt text-3xl text-blue-500 mr-4"></i>
                    <div>
                        <p class="text-sm text-gray-600">Difesa</p>
                        <p class="text-2xl font-bold text-gray-900" x-text="totalStats.defense"></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-purple-500">
                <div class="flex items-center">
                    <i class="fas fa-hat-wizard text-3xl text-purple-500 mr-4"></i>
                    <div>
                        <p class="text-sm text-gray-600">Livello</p>
                        <p class="text-2xl font-bold text-gray-900">{{ Auth::user()->level ?? 1 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="mb-6">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <button @click="currentFilter = 'all'"
                            :class="currentFilter === 'all' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                        <i class="fas fa-th mr-2"></i>
                        Tutti (<span x-text="items.length"></span>)
                    </button>
                    <button @click="currentFilter = 'wand'"
                            :class="currentFilter === 'wand' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                        <i class="fas fa-wand-magic mr-2"></i>
                        Bacchette
                    </button>
                    <button @click="currentFilter = 'book'"
                            :class="currentFilter === 'book' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                        <i class="fas fa-book mr-2"></i>
                        Libri
                    </button>
                    <button @click="currentFilter = 'potion'"
                            :class="currentFilter === 'potion' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                        <i class="fas fa-flask mr-2"></i>
                        Pozioni
                    </button>
                    <button @click="currentFilter = 'equipment'"
                            :class="currentFilter === 'equipment' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                        <i class="fas fa-tshirt mr-2"></i>
                        Equipaggiamento
                    </button>
                </nav>
            </div>
        </div>

        <!-- Equipped Items Section -->
        <div class="mb-8">
            <h2 class="text-xl font-magic font-bold text-gray-900 mb-4">
                <i class="fas fa-user-shield mr-2 text-green-600"></i>
                Equipaggiamento Attivo
            </h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                <!-- Wand Slot -->
                <div class="bg-white rounded-lg shadow-md p-4 border-2 border-dashed border-purple-300 hover:border-purple-500 transition-colors">
                    <div class="text-center">
                        <i class="fas fa-wand-magic text-4xl text-purple-300 mb-2"></i>
                        <p class="text-xs text-gray-500 font-medium">Bacchetta</p>
                        <template x-if="equippedItems.wand">
                            <div class="mt-2 p-2 bg-purple-50 rounded">
                                <p class="text-xs font-bold text-purple-900" x-text="equippedItems.wand.name"></p>
                                <button @click="unequipItem('wand')" class="text-xs text-red-600 hover:text-red-800 mt-1">
                                    <i class="fas fa-times"></i> Rimuovi
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Robe Slot -->
                <div class="bg-white rounded-lg shadow-md p-4 border-2 border-dashed border-blue-300 hover:border-blue-500 transition-colors">
                    <div class="text-center">
                        <i class="fas fa-user-graduate text-4xl text-blue-300 mb-2"></i>
                        <p class="text-xs text-gray-500 font-medium">Veste</p>
                        <template x-if="equippedItems.robe">
                            <div class="mt-2 p-2 bg-blue-50 rounded">
                                <p class="text-xs font-bold text-blue-900" x-text="equippedItems.robe.name"></p>
                                <button @click="unequipItem('robe')" class="text-xs text-red-600 hover:text-red-800 mt-1">
                                    <i class="fas fa-times"></i> Rimuovi
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Accessory Slots -->
                <div class="bg-white rounded-lg shadow-md p-4 border-2 border-dashed border-yellow-300 hover:border-yellow-500 transition-colors">
                    <div class="text-center">
                        <i class="fas fa-ring text-4xl text-yellow-300 mb-2"></i>
                        <p class="text-xs text-gray-500 font-medium">Accessorio 1</p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-4 border-2 border-dashed border-yellow-300 hover:border-yellow-500 transition-colors">
                    <div class="text-center">
                        <i class="fas fa-gem text-4xl text-yellow-300 mb-2"></i>
                        <p class="text-xs text-gray-500 font-medium">Accessorio 2</p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-4 border-2 border-dashed border-green-300 hover:border-green-500 transition-colors">
                    <div class="text-center">
                        <i class="fas fa-book-open text-4xl text-green-300 mb-2"></i>
                        <p class="text-xs text-gray-500 font-medium">Libro</p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-4 border-2 border-dashed border-red-300 hover:border-red-500 transition-colors">
                    <div class="text-center">
                        <i class="fas fa-shield-alt text-4xl text-red-300 mb-2"></i>
                        <p class="text-xs text-gray-500 font-medium">Scudo</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory Grid -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-magic font-bold text-gray-900">
                    Oggetti nell'Inventario
                </h2>
                <div class="flex space-x-2">
                    <button @click="sortBy = 'name'"
                            :class="sortBy === 'name' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700'"
                            class="px-3 py-1 rounded text-sm font-medium transition-colors">
                        <i class="fas fa-sort-alpha-down mr-1"></i> Nome
                    </button>
                    <button @click="sortBy = 'rarity'"
                            :class="sortBy === 'rarity' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700'"
                            class="px-3 py-1 rounded text-sm font-medium transition-colors">
                        <i class="fas fa-star mr-1"></i> Rarità
                    </button>
                    <button @click="sortBy = 'type'"
                            :class="sortBy === 'type' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700'"
                            class="px-3 py-1 rounded text-sm font-medium transition-colors">
                        <i class="fas fa-tags mr-1"></i> Tipo
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <template x-for="item in filteredItems" :key="item.id">
                    <div @click="selectItem(item)"
                         class="relative bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg p-4 border-2 border-gray-300 hover:border-indigo-500 hover:shadow-xl transition-all duration-200 cursor-pointer transform hover:scale-105">

                        <!-- Rarity Border -->
                        <div :class="{
                            'border-gray-400': item.rarity === 'common',
                            'border-green-500': item.rarity === 'uncommon',
                            'border-blue-500': item.rarity === 'rare',
                            'border-purple-500': item.rarity === 'epic',
                            'border-yellow-500': item.rarity === 'legendary'
                        }" class="absolute inset-0 rounded-lg border-4 opacity-50"></div>

                        <!-- Quantity Badge -->
                        <template x-if="item.quantity > 1">
                            <div class="absolute -top-2 -right-2 bg-indigo-600 text-white rounded-full h-6 w-6 flex items-center justify-center text-xs font-bold shadow-lg z-10">
                                <span x-text="item.quantity"></span>
                            </div>
                        </template>

                        <!-- Item Icon/Image -->
                        <div class="flex items-center justify-center mb-2 h-16">
                            <template x-if="item.image">
                                <img :src="'/upload/objects/' + item.image" :alt="item.name" class="max-h-full max-w-full object-contain">
                            </template>
                            <template x-if="!item.image">
                                <i :class="getItemIcon(item.type)" class="text-4xl text-gray-400"></i>
                            </template>
                        </div>

                        <!-- Item Name -->
                        <p class="text-xs font-bold text-center text-gray-900 truncate" x-text="item.name"></p>

                        <!-- Item Stats -->
                        <div class="mt-2 flex justify-center space-x-2 text-xs">
                            <template x-if="item.attack > 0">
                                <span class="text-red-600 font-bold">
                                    <i class="fas fa-sword"></i> <span x-text="item.attack"></span>
                                </span>
                            </template>
                            <template x-if="item.defense > 0">
                                <span class="text-blue-600 font-bold">
                                    <i class="fas fa-shield"></i> <span x-text="item.defense"></span>
                                </span>
                            </template>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Empty State -->
            <div x-show="filteredItems.length === 0" class="text-center py-12">
                <i class="fas fa-box-open text-6xl text-gray-300 mb-4"></i>
                <p class="text-xl font-medium text-gray-500">Nessun oggetto trovato</p>
                <p class="text-sm text-gray-400 mt-2">Visita i negozi di Diagon Alley per acquistare oggetti magici!</p>
            </div>
        </div>

    </div>

    <!-- Item Detail Modal -->
    <div x-show="selectedItem !== null"
         @click.self="selectedItem = null"
         x-cloak
         class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">

        <div @click.stop
             class="relative bg-white rounded-lg shadow-2xl max-w-md w-full transform"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <template x-if="selectedItem">
                <div>
                    <!-- Header -->
                    <div :class="{
                        'bg-gradient-to-r from-gray-400 to-gray-600': selectedItem.rarity === 'common',
                        'bg-gradient-to-r from-green-400 to-green-600': selectedItem.rarity === 'uncommon',
                        'bg-gradient-to-r from-blue-400 to-blue-600': selectedItem.rarity === 'rare',
                        'bg-gradient-to-r from-purple-400 to-purple-600': selectedItem.rarity === 'epic',
                        'bg-gradient-to-r from-yellow-400 to-yellow-600': selectedItem.rarity === 'legendary'
                    }" class="rounded-t-lg p-6 text-white">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-2xl font-magic font-bold" x-text="selectedItem.name"></h3>
                                <p class="text-sm opacity-90 mt-1 capitalize" x-text="selectedItem.rarity + ' ' + selectedItem.type"></p>
                            </div>
                            <button @click="selectedItem = null" class="text-white hover:text-gray-200">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="p-6">
                        <!-- Image -->
                        <div class="flex justify-center mb-4">
                            <template x-if="selectedItem.image">
                                <img :src="'/upload/objects/' + selectedItem.image" :alt="selectedItem.name" class="max-h-32 object-contain">
                            </template>
                            <template x-if="!selectedItem.image">
                                <i :class="getItemIcon(selectedItem.type)" class="text-6xl text-gray-400"></i>
                            </template>
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <p class="text-sm text-gray-600" x-text="selectedItem.description || 'Un oggetto magico misterioso...'"></p>
                        </div>

                        <!-- Stats -->
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <template x-if="selectedItem.attack > 0">
                                <div class="bg-red-50 rounded-lg p-3 text-center">
                                    <p class="text-xs text-gray-600 mb-1">Attacco</p>
                                    <p class="text-2xl font-bold text-red-600">+<span x-text="selectedItem.attack"></span></p>
                                </div>
                            </template>
                            <template x-if="selectedItem.defense > 0">
                                <div class="bg-blue-50 rounded-lg p-3 text-center">
                                    <p class="text-xs text-gray-600 mb-1">Difesa</p>
                                    <p class="text-2xl font-bold text-blue-600">+<span x-text="selectedItem.defense"></span></p>
                                </div>
                            </template>
                        </div>

                        <!-- Quantity -->
                        <div class="mb-4 bg-gray-50 rounded-lg p-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Quantità posseduta:</span>
                                <span class="text-lg font-bold text-indigo-600" x-text="selectedItem.quantity"></span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex space-x-2">
                            <template x-if="selectedItem.type === 'wand' || selectedItem.type === 'equipment'">
                                <button @click="equipItem(selectedItem)"
                                        class="flex-1 bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                                    <i class="fas fa-hand-sparkles mr-2"></i>
                                    Equipaggia
                                </button>
                            </template>
                            <template x-if="selectedItem.type === 'potion'">
                                <button @click="useItem(selectedItem)"
                                        class="flex-1 bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                                    <i class="fas fa-flask-potion mr-2"></i>
                                    Usa
                                </button>
                            </template>
                            <button @click="sellItem(selectedItem)"
                                    class="flex-1 bg-yellow-600 hover:bg-yellow-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                                <i class="fas fa-coins mr-2"></i>
                                Vendi
                            </button>
                            <button @click="dropItem(selectedItem)"
                                    class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

@push('scripts')
<script>
function inventoryManager() {
    return {
        items: @json($inventory),
        equippedItems: {
            wand: null,
            robe: null,
            accessory1: null,
            accessory2: null,
            book: null,
            shield: null
        },
        selectedItem: null,
        currentFilter: 'all',
        sortBy: 'name',

        get filteredItems() {
            let filtered = this.items;

            // Filter by type
            if (this.currentFilter !== 'all') {
                filtered = filtered.filter(item => item.type === this.currentFilter);
            }

            // Sort
            if (this.sortBy === 'name') {
                filtered.sort((a, b) => a.name.localeCompare(b.name));
            } else if (this.sortBy === 'rarity') {
                const rarityOrder = { legendary: 5, epic: 4, rare: 3, uncommon: 2, common: 1 };
                filtered.sort((a, b) => (rarityOrder[b.rarity] || 0) - (rarityOrder[a.rarity] || 0));
            } else if (this.sortBy === 'type') {
                filtered.sort((a, b) => a.type.localeCompare(b.type));
            }

            return filtered;
        },

        get totalStats() {
            let attack = 0;
            let defense = 0;

            Object.values(this.equippedItems).forEach(item => {
                if (item) {
                    attack += parseInt(item.attack) || 0;
                    defense += parseInt(item.defense) || 0;
                }
            });

            return { attack, defense };
        },

        getItemIcon(type) {
            const icons = {
                wand: 'fas fa-wand-magic',
                book: 'fas fa-book',
                potion: 'fas fa-flask',
                equipment: 'fas fa-tshirt',
                misc: 'fas fa-cube'
            };
            return icons[type] || 'fas fa-cube';
        },

        selectItem(item) {
            this.selectedItem = item;
        },

        equipItem(item) {
            if (item.type === 'wand') {
                this.equippedItems.wand = item;
            } else if (item.type === 'equipment') {
                this.equippedItems.robe = item;
            }

            // AJAX call to save equipped items
            this.saveEquipment();
            this.selectedItem = null;
        },

        unequipItem(slot) {
            this.equippedItems[slot] = null;
            this.saveEquipment();
        },

        useItem(item) {
            if (confirm(`Vuoi usare ${item.name}?`)) {
                // AJAX call to use item
                alert('Pozione utilizzata!');
                this.selectedItem = null;
            }
        },

        sellItem(item) {
            const sellPrice = Math.floor(item.price * 0.5);
            if (confirm(`Vendere ${item.name} per ${sellPrice} Galeoni?`)) {
                // AJAX call to sell item
                alert(`${item.name} venduto per ${sellPrice} Galeoni!`);
                this.selectedItem = null;
            }
        },

        dropItem(item) {
            if (confirm(`Sei sicuro di voler eliminare ${item.name}?`)) {
                // AJAX call to drop item
                this.items = this.items.filter(i => i.id !== item.id);
                this.selectedItem = null;
            }
        },

        saveEquipment() {
            // AJAX call to save equipped items to backend
            console.log('Saving equipment...', this.equippedItems);
        }
    }
}
</script>
@endpush

@endsection
