@extends('admin.layouts.modern')

@section('page-title', 'Gestione Utenti')

@section('content')
<div x-data="{
    search: '',
    filter: 'all',
    roleFilter: 'all',
    houseFilter: 'all'
}">
    <!-- Header with actions -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-magic font-bold text-gray-900">Gestione Utenti</h2>
            <p class="mt-1 text-sm text-gray-500">Visualizza e gestisci tutti gli utenti del GDR</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.user.create') }}"
               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Nuovo Utente
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-6 bg-white shadow-md rounded-lg p-6">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
            <!-- Search -->
            <div class="sm:col-span-2">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-search mr-1"></i> Ricerca
                </label>
                <input type="text"
                       x-model="search"
                       id="search"
                       placeholder="Cerca per username, nome, email..."
                       class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
            </div>

            <!-- Role Filter -->
            <div>
                <label for="role-filter" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-user-tag mr-1"></i> Ruolo
                </label>
                <select x-model="roleFilter"
                        id="role-filter"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    <option value="all">Tutti i ruoli</option>
                    <option value="2">Amministratori</option>
                    <option value="1">Moderatori</option>
                    <option value="0">Utenti</option>
                </select>
            </div>

            <!-- House Filter -->
            <div>
                <label for="house-filter" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-flag mr-1"></i> Casata
                </label>
                <select x-model="houseFilter"
                        id="house-filter"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    <option value="all">Tutte le casate</option>
                    <option value="1">Grifondoro</option>
                    <option value="2">Serpeverde</option>
                    <option value="3">Corvonero</option>
                    <option value="4">Tassorosso</option>
                    <option value="0">Nessuna casata</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Utente
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Email
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Casata
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ruolo
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Livello
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Monete
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Registrato
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Azioni
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors"
                        x-show="(search === '' || '{{ strtolower($user->username) }}'.includes(search.toLowerCase()) || '{{ strtolower($user->name) }}'.includes(search.toLowerCase()) || '{{ strtolower($user->email) }}'.includes(search.toLowerCase())) &&
                                (roleFilter === 'all' || roleFilter === '{{ $user->group }}') &&
                                (houseFilter === 'all' || houseFilter === '{{ $user->team ?? 0 }}')">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full object-cover border-2 border-gray-300"
                                         src="{{ url('upload/user/'.$user->avatar()) }}"
                                         alt="{{ $user->username }}">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $user->username }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $user->name }} {{ $user->surname }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->team)
                                @php
                                    $house = \App\Models\Team::find($user->team);
                                    $houseColors = [
                                        1 => ['bg' => 'bg-red-100', 'text' => 'text-red-800'],
                                        2 => ['bg' => 'bg-green-100', 'text' => 'text-green-800'],
                                        3 => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
                                        4 => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800'],
                                    ];
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $houseColors[$user->team]['bg'] }} {{ $houseColors[$user->team]['text'] }}">
                                    {{ $house->name ?? 'N/A' }}
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Nessuna
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->group == 2)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                    <i class="fas fa-crown mr-1"></i> Admin
                                </span>
                            @elseif($user->group == 1)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    <i class="fas fa-shield-alt mr-1"></i> Moderatore
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    <i class="fas fa-user mr-1"></i> Utente
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                <i class="fas fa-star text-yellow-500 mr-1"></i>
                                {{ $user->level ?? 1 }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $user->exp ?? 0 }} XP
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                <i class="fas fa-coins text-yellow-600 mr-1"></i>
                                {{ $user->money ?? 0 }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('admin.user.edit', $user->id) }}"
                                   class="text-indigo-600 hover:text-indigo-900 transition-colors"
                                   title="Modifica">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('admin.user.show', $user->id) }}"
                                   class="text-green-600 hover:text-green-900 transition-colors"
                                   title="Visualizza">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button onclick="if(confirm('Sei sicuro di voler eliminare questo utente?')) { document.getElementById('delete-form-{{ $user->id }}').submit(); }"
                                        class="text-red-600 hover:text-red-900 transition-colors"
                                        title="Elimina">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-form-{{ $user->id }}"
                                      action="{{ route('admin.user.destroy', $user->id) }}"
                                      method="POST"
                                      class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="text-gray-400">
                                <i class="fas fa-users text-4xl mb-4"></i>
                                <p class="text-lg font-medium">Nessun utente trovato</p>
                                <p class="text-sm mt-1">Inizia creando il primo utente</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
    <div class="mt-6">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Additional scripts if needed
</script>
@endpush
