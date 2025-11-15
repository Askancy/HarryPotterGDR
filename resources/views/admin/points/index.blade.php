@extends('admin.layouts.modern')

@section('page-title', 'Gestione Punti Casate')

@section('content')
<div x-data="{ showAddPoints: false, selectedHouse: '', points: '', reason: '' }">

    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-2xl font-magic font-bold text-gray-900">Gestione Punti Casate</h2>
        <p class="mt-1 text-sm text-gray-500">Assegna o rimuovi punti alle casate di Hogwarts</p>
    </div>

    <!-- Houses Ranking -->
    <div class="mb-8 bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-magic font-bold text-gray-900">
                <i class="fas fa-trophy mr-2 text-yellow-500"></i>
                Classifica Attuale
            </h3>
        </div>
        <div class="px-6 py-6">
            @php
                $houses = \App\Models\Team::orderBy('point', 'desc')->get();
                $houseIcons = [
                    1 => 'fa-dragon',      // Grifondoro
                    2 => 'fa-snake',        // Serpeverde
                    3 => 'fa-crow',         // Corvonero
                    4 => 'fa-otter',        // Tassorosso
                ];
                $houseColors = [
                    1 => ['bg' => 'bg-gradient-to-br from-red-600 to-yellow-600', 'text' => 'text-white', 'badge' => 'bg-red-100 text-red-800', 'border' => 'border-red-500'],
                    2 => ['bg' => 'bg-gradient-to-br from-green-700 to-gray-800', 'text' => 'text-white', 'badge' => 'bg-green-100 text-green-800', 'border' => 'border-green-600'],
                    3 => ['bg' => 'bg-gradient-to-br from-blue-700 to-blue-900', 'text' => 'text-white', 'badge' => 'bg-blue-100 text-blue-800', 'border' => 'border-blue-600'],
                    4 => ['bg' => 'bg-gradient-to-br from-yellow-500 to-yellow-700', 'text' => 'text-white', 'badge' => 'bg-yellow-100 text-yellow-800', 'border' => 'border-yellow-500'],
                ];
            @endphp

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($houses as $index => $house)
                <div class="relative {{ $houseColors[$house->id]['bg'] }} rounded-xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                    @if($index == 0)
                    <div class="absolute -top-3 -right-3">
                        <div class="bg-yellow-400 rounded-full p-3 shadow-lg">
                            <i class="fas fa-crown text-2xl text-yellow-800"></i>
                        </div>
                    </div>
                    @endif

                    <div class="flex flex-col items-center text-center">
                        <div class="mb-3">
                            <i class="fas {{ $houseIcons[$house->id] ?? 'fa-flag' }} text-5xl {{ $houseColors[$house->id]['text'] }} opacity-90"></i>
                        </div>

                        <h3 class="text-2xl font-magic font-bold {{ $houseColors[$house->id]['text'] }} mb-2">
                            {{ $house->name }}
                        </h3>

                        <div class="mb-4">
                            <p class="text-sm {{ $houseColors[$house->id]['text'] }} opacity-75 mb-1">
                                {{ $index + 1 }}° Posto
                            </p>
                            <p class="text-5xl font-bold {{ $houseColors[$house->id]['text'] }}">
                                {{ number_format($house->point) }}
                            </p>
                            <p class="text-sm {{ $houseColors[$house->id]['text'] }} opacity-75">
                                punti
                            </p>
                        </div>

                        <!-- Quick actions -->
                        <div class="flex space-x-2 w-full">
                            <button @click="showAddPoints = true; selectedHouse = '{{ $house->id }}'; points = ''"
                                    class="flex-1 bg-white bg-opacity-20 hover:bg-opacity-30 {{ $houseColors[$house->id]['text'] }} px-3 py-2 rounded-lg text-sm font-medium transition-all">
                                <i class="fas fa-plus mr-1"></i> Aggiungi
                            </button>
                            <button @click="showAddPoints = true; selectedHouse = '{{ $house->id }}'; points = '-'"
                                    class="flex-1 bg-white bg-opacity-20 hover:bg-opacity-30 {{ $houseColors[$house->id]['text'] }} px-3 py-2 rounded-lg text-sm font-medium transition-all">
                                <i class="fas fa-minus mr-1"></i> Rimuovi
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Points Log -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 flex justify-between items-center">
            <div>
                <h3 class="text-lg leading-6 font-magic font-bold text-gray-900">
                    <i class="fas fa-history mr-2 text-indigo-500"></i>
                    Storico Modifiche Punti
                </h3>
                <p class="mt-1 text-sm text-gray-500">Ultime modifiche ai punti delle casate</p>
            </div>
            <button @click="showAddPoints = true"
                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Assegna Punti
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Data
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Casata
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Punti
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Motivo
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Assegnato da
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($logs as $log)
                    @php
                        $house = \App\Models\Team::find($log->id_team);
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $log->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($house)
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $houseColors[$house->id]['badge'] }}">
                                <i class="fas {{ $houseIcons[$house->id] ?? 'fa-flag' }} mr-2"></i>
                                {{ $house->name }}
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($log->type == 1)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="fas fa-plus-circle mr-1"></i>
                                +{{ $log->point }} punti
                            </span>
                            @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                <i class="fas fa-minus-circle mr-1"></i>
                                -{{ $log->point }} punti
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $log->reason ?? 'Nessun motivo specificato' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $admin = \App\Models\User::find($log->id_user);
                            @endphp
                            @if($admin)
                            <div class="flex items-center">
                                <img class="h-8 w-8 rounded-full object-cover mr-2"
                                     src="{{ url('upload/user/'.$admin->avatar()) }}"
                                     alt="{{ $admin->username }}">
                                <span class="text-sm text-gray-900">{{ $admin->username }}</span>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="text-gray-400">
                                <i class="fas fa-history text-4xl mb-4"></i>
                                <p class="text-lg font-medium">Nessuna modifica registrata</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $logs->links() }}
        </div>
        @endif
    </div>

    <!-- Modal Add/Remove Points -->
    <div x-show="showAddPoints"
         @click.self="showAddPoints = false"
         x-cloak
         class="fixed inset-0 bg-gray-500 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full"
             @click.stop
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

            <form method="POST" action="{{ route('admin.point.store') }}">
                @csrf
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg font-magic font-bold text-gray-900">
                        <i class="fas fa-star mr-2"></i>
                        Modifica Punti Casata
                    </h3>
                </div>

                <div class="px-6 py-4 space-y-4">
                    <!-- House Selection -->
                    <div>
                        <label for="house" class="block text-sm font-medium text-gray-700 mb-2">
                            Seleziona Casata
                        </label>
                        <select name="id_team"
                                x-model="selectedHouse"
                                id="house"
                                required
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <option value="">Seleziona una casata...</option>
                            @foreach($houses as $house)
                            <option value="{{ $house->id }}">{{ $house->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Points -->
                    <div>
                        <label for="points" class="block text-sm font-medium text-gray-700 mb-2">
                            Punti
                        </label>
                        <input type="number"
                               name="point"
                               x-model="points"
                               id="points"
                               required
                               placeholder="Es: 10 per aggiungere, -10 per rimuovere"
                               class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        <p class="mt-1 text-xs text-gray-500">
                            Usa numeri positivi per aggiungere, negativi per rimuovere
                        </p>
                    </div>

                    <!-- Reason -->
                    <div>
                        <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                            Motivo
                        </label>
                        <textarea name="reason"
                                  x-model="reason"
                                  id="reason"
                                  rows="3"
                                  placeholder="Specifica il motivo dell'assegnazione/rimozione punti..."
                                  class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"></textarea>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3">
                    <button type="button"
                            @click="showAddPoints = false"
                            class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Annulla
                    </button>
                    <button type="submit"
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-save mr-2"></i>
                        Salva Modifiche
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Additional scripts if needed
</script>
@endpush
