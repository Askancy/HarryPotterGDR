@extends('admin.layouts.modern')

@section('page-title', 'Gestione Quest')

@section('content')
<div x-data="{ search: '', statusFilter: 'all', privacyFilter: 'all' }">

    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-magic font-bold text-gray-900">Gestione Quest</h2>
            <p class="mt-1 text-sm text-gray-500">Crea e gestisci le missioni per gli studenti</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.quest.create') }}"
               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Nuova Quest
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-6 bg-white shadow-md rounded-lg p-6">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <!-- Search -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-search mr-1"></i> Ricerca
                </label>
                <input type="text"
                       x-model="search"
                       id="search"
                       placeholder="Cerca quest..."
                       class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
            </div>

            <!-- Status Filter -->
            <div>
                <label for="status-filter" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-flag mr-1"></i> Stato
                </label>
                <select x-model="statusFilter"
                        id="status-filter"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    <option value="all">Tutti gli stati</option>
                    <option value="1">Attive</option>
                    <option value="0">Completate</option>
                </select>
            </div>

            <!-- Privacy Filter -->
            <div>
                <label for="privacy-filter" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-eye mr-1"></i> Privacy
                </label>
                <select x-model="privacyFilter"
                        id="privacy-filter"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    <option value="all">Tutte</option>
                    <option value="1">Pubbliche</option>
                    <option value="0">Private</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Quests Grid -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 xl:grid-cols-3">
        @forelse($quests as $quest)
        <div x-show="(search === '' || '{{ strtolower($quest->name) }}'.includes(search.toLowerCase())) &&
                     (statusFilter === 'all' || statusFilter === '{{ $quest->status }}') &&
                     (privacyFilter === 'all' || privacyFilter === '{{ $quest->privacy }}')"
             class="bg-white shadow-lg rounded-lg overflow-hidden hover:shadow-xl transition-all duration-300">

            <!-- Quest Header -->
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 p-5">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h3 class="text-xl font-magic font-bold text-white mb-1">
                            {{ $quest->name }}
                        </h3>
                        <div class="flex items-center space-x-2 mt-2">
                            @if($quest->privacy == 1)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-white bg-opacity-20 text-white">
                                <i class="fas fa-globe mr-1"></i> Pubblica
                            </span>
                            @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-white bg-opacity-20 text-white">
                                <i class="fas fa-lock mr-1"></i> Privata
                            </span>
                            @endif

                            @if($quest->status == 1)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Attiva
                            </span>
                            @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <i class="fas fa-times-circle mr-1"></i> Disattiva
                            </span>
                            @endif
                        </div>
                    </div>
                    <i class="fas fa-scroll text-3xl text-white opacity-50"></i>
                </div>
            </div>

            <!-- Quest Body -->
            <div class="p-5">
                @if($quest->description)
                <div class="mb-4">
                    <p class="text-sm text-gray-600 line-clamp-4">
                        {{ $quest->description }}
                    </p>
                </div>
                @endif

                <!-- Quest Stats -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    @if($quest->exp_reward)
                    <div class="bg-blue-50 rounded-lg p-3 text-center">
                        <p class="text-xs text-gray-600 mb-1">Ricompensa XP</p>
                        <p class="text-lg font-bold text-blue-600">
                            <i class="fas fa-star mr-1"></i>
                            {{ $quest->exp_reward }}
                        </p>
                    </div>
                    @endif

                    @if($quest->money_reward)
                    <div class="bg-yellow-50 rounded-lg p-3 text-center">
                        <p class="text-xs text-gray-600 mb-1">Ricompensa Monete</p>
                        <p class="text-lg font-bold text-yellow-600">
                            <i class="fas fa-coins mr-1"></i>
                            {{ $quest->money_reward }}
                        </p>
                    </div>
                    @endif

                    @php
                        $participants = \App\Models\Pivot_Quest::where('id_quest', $quest->id)->count();
                        $completed = \App\Models\Pivot_Quest::where('id_quest', $quest->id)->where('status', 1)->count();
                    @endphp

                    <div class="bg-green-50 rounded-lg p-3 text-center">
                        <p class="text-xs text-gray-600 mb-1">Partecipanti</p>
                        <p class="text-lg font-bold text-green-600">
                            <i class="fas fa-users mr-1"></i>
                            {{ $participants }}
                        </p>
                    </div>

                    <div class="bg-purple-50 rounded-lg p-3 text-center">
                        <p class="text-xs text-gray-600 mb-1">Completati</p>
                        <p class="text-lg font-bold text-purple-600">
                            <i class="fas fa-check mr-1"></i>
                            {{ $completed }}
                        </p>
                    </div>
                </div>

                <!-- Difficulty -->
                @if($quest->difficulty)
                <div class="mb-4">
                    <p class="text-xs text-gray-500 mb-1">Difficoltà</p>
                    <div class="flex items-center">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $quest->difficulty)
                            <i class="fas fa-star text-yellow-400"></i>
                            @else
                            <i class="far fa-star text-gray-300"></i>
                            @endif
                        @endfor
                    </div>
                </div>
                @endif

                <!-- Actions -->
                <div class="flex space-x-2 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.quest.edit', $quest->id) }}"
                       class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white text-center px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        <i class="fas fa-edit mr-1"></i> Modifica
                    </a>
                    <a href="{{ route('admin.quest.show', $quest->id) }}"
                       class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        <i class="fas fa-eye"></i>
                    </a>
                    <button onclick="if(confirm('Eliminare questa quest?')) { document.getElementById('delete-{{ $quest->id }}').submit(); }"
                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        <i class="fas fa-trash"></i>
                    </button>
                    <form id="delete-{{ $quest->id }}"
                          action="{{ route('admin.quest.destroy', $quest->id) }}"
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
                <i class="fas fa-scroll text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-magic font-bold text-gray-900 mb-2">Nessuna quest trovata</h3>
                <p class="text-gray-500 mb-6">Inizia creando la prima missione per gli studenti</p>
                <a href="{{ route('admin.quest.create') }}"
                   class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    <i class="fas fa-plus mr-2"></i>
                    Crea Quest
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($quests->hasPages())
    <div class="mt-8">
        {{ $quests->links() }}
    </div>
    @endif
</div>
@endsection
