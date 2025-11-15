@extends('admin.layouts.modern')

@section('page-title', 'Gestione Oggetti Magici')

@section('content')
<div x-data="{ search: '', shopFilter: 'all' }">

    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-magic font-bold text-gray-900">Gestione Oggetti Magici</h2>
            <p class="mt-1 text-sm text-gray-500">Gestisci bacchette, libri e altri oggetti magici</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.objects.create') }}"
               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Nuovo Oggetto
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-6 bg-white shadow-md rounded-lg p-6">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <!-- Search -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-search mr-1"></i> Ricerca
                </label>
                <input type="text"
                       x-model="search"
                       id="search"
                       placeholder="Cerca oggetti..."
                       class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
            </div>

            <!-- Shop Filter -->
            <div>
                <label for="shop-filter" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-store mr-1"></i> Negozio
                </label>
                <select x-model="shopFilter"
                        id="shop-filter"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    <option value="all">Tutti i negozi</option>
                    @foreach(\App\Models\Shop::all() as $shop)
                    <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Objects Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @forelse($objects as $object)
        <div x-show="(search === '' || '{{ strtolower($object->name) }}'.includes(search.toLowerCase())) &&
                     (shopFilter === 'all' || shopFilter === '{{ $object->id_shop }}')"
             class="bg-white shadow-lg rounded-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:scale-105">

            <!-- Object Image/Icon -->
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 p-6 flex items-center justify-center h-48">
                @if($object->image)
                <img src="{{ asset('upload/objects/'.$object->image) }}"
                     alt="{{ $object->name }}"
                     class="max-h-full max-w-full object-contain">
                @else
                <i class="fas fa-wand-magic text-6xl text-white opacity-75"></i>
                @endif
            </div>

            <!-- Object Info -->
            <div class="p-5">
                <div class="mb-3">
                    <h3 class="text-lg font-magic font-bold text-gray-900 mb-1">
                        {{ $object->name }}
                    </h3>
                    @php
                        $shop = \App\Models\Shop::find($object->id_shop);
                    @endphp
                    @if($shop)
                    <p class="text-xs text-gray-500">
                        <i class="fas fa-store mr-1"></i>
                        {{ $shop->name }}
                    </p>
                    @endif
                </div>

                @if($object->description)
                <p class="text-sm text-gray-600 mb-4 line-clamp-3">
                    {{ Str::limit($object->description, 100) }}
                </p>
                @endif

                <!-- Price and Stats -->
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-xs text-gray-500">Prezzo</p>
                        <p class="text-lg font-bold text-yellow-600">
                            <i class="fas fa-coins mr-1"></i>
                            {{ number_format($object->price) }}
                        </p>
                    </div>

                    @if($object->attack || $object->defense)
                    <div class="flex space-x-3 text-xs">
                        @if($object->attack)
                        <div class="text-center">
                            <p class="text-gray-500">ATK</p>
                            <p class="font-bold text-red-600">+{{ $object->attack }}</p>
                        </div>
                        @endif
                        @if($object->defense)
                        <div class="text-center">
                            <p class="text-gray-500">DEF</p>
                            <p class="font-bold text-blue-600">+{{ $object->defense }}</p>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>

                <!-- Type Badge -->
                @if($object->type)
                <div class="mb-4">
                    @php
                        $typeLabels = [
                            'wand' => ['label' => 'Bacchetta', 'color' => 'purple', 'icon' => 'fa-wand-magic'],
                            'book' => ['label' => 'Libro', 'color' => 'blue', 'icon' => 'fa-book'],
                            'potion' => ['label' => 'Pozione', 'color' => 'green', 'icon' => 'fa-flask'],
                            'equipment' => ['label' => 'Equipaggiamento', 'color' => 'gray', 'icon' => 'fa-shield-alt'],
                            'misc' => ['label' => 'Varie', 'color' => 'yellow', 'icon' => 'fa-star'],
                        ];
                        $typeInfo = $typeLabels[$object->type] ?? ['label' => ucfirst($object->type), 'color' => 'gray', 'icon' => 'fa-cube'];
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-{{ $typeInfo['color'] }}-100 text-{{ $typeInfo['color'] }}-800">
                        <i class="fas {{ $typeInfo['icon'] }} mr-1"></i>
                        {{ $typeInfo['label'] }}
                    </span>
                </div>
                @endif

                <!-- Actions -->
                <div class="flex space-x-2">
                    <a href="{{ route('admin.objects.edit', $object->id) }}"
                       class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white text-center px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        <i class="fas fa-edit mr-1"></i> Modifica
                    </a>
                    <button onclick="if(confirm('Eliminare questo oggetto?')) { document.getElementById('delete-{{ $object->id }}').submit(); }"
                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        <i class="fas fa-trash"></i>
                    </button>
                    <form id="delete-{{ $object->id }}"
                          action="{{ route('admin.objects.destroy', $object->id) }}"
                          method="POST"
                          class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="bg-white shadow-lg rounded-lg p-12 text-center">
                <i class="fas fa-wand-magic text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-magic font-bold text-gray-900 mb-2">Nessun oggetto trovato</h3>
                <p class="text-gray-500 mb-6">Inizia creando il primo oggetto magico</p>
                <a href="{{ route('admin.objects.create') }}"
                   class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    <i class="fas fa-plus mr-2"></i>
                    Crea Oggetto
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($objects->hasPages())
    <div class="mt-8">
        {{ $objects->links() }}
    </div>
    @endif
</div>
@endsection
