@extends('admin.layouts.modern')

@section('content')
<div x-data="housePointsManager()" class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Gestione Punti Case</h1>
        <p class="text-gray-600">Assegna, rimuovi e monitora i punti delle case di Hogwarts</p>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        {{ session('success') }}
    </div>
    @endif

    <!-- House Rankings -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        @foreach($houses as $index => $house)
        <div class="bg-white rounded-lg shadow-lg overflow-hidden transform hover:scale-105 transition-transform">
            <div class="h-2 bg-gradient-to-r {{ $index === 0 ? 'from-yellow-400 to-yellow-600' : 'from-gray-300 to-gray-400' }}"></div>
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-800">{{ $house->name }}</h3>
                    @if($index === 0)
                    <span class="text-2xl">🏆</span>
                    @else
                    <span class="text-gray-400 font-bold">#{{ $index + 1 }}</span>
                    @endif
                </div>
                <div class="text-4xl font-bold mb-2
                    {{ $house->id == 1 ? 'text-red-600' : '' }}
                    {{ $house->id == 2 ? 'text-green-700' : '' }}
                    {{ $house->id == 3 ? 'text-blue-700' : '' }}
                    {{ $house->id == 4 ? 'text-yellow-600' : '' }}
                ">
                    {{ $house->points ?? 0 }}
                </div>
                <p class="text-gray-600 text-sm mb-4">Punti Totali</p>
                <button @click="openAwardModal({{ $house->id }}, '{{ $house->name }}')"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
                    Assegna Punti
                </button>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Azioni Rapide</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <button @click="openBulkAwardModal()"
                class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-6 rounded-lg">
                <i class="fas fa-users mr-2"></i> Assegnazione di Massa
            </button>
            <button @click="openResetModal()"
                class="bg-orange-600 hover:bg-orange-700 text-white font-semibold py-3 px-6 rounded-lg">
                <i class="fas fa-redo mr-2"></i> Reset Punti (Nuovo Anno)
            </button>
            <a href="{{ route('house-points.public') }}" target="_blank"
                class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-3 px-6 rounded-lg text-center">
                <i class="fas fa-chart-line mr-2"></i> Classifica Pubblica
            </a>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Attività Recente</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Data</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Casa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Punti</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Motivo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Destinatario</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Assegnato da</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($recentActivity as $activity)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($activity->created_at)->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded
                                {{ $activity->house_id == 1 ? 'bg-red-100 text-red-800' : '' }}
                                {{ $activity->house_id == 2 ? 'bg-green-100 text-green-800' : '' }}
                                {{ $activity->house_id == 3 ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $activity->house_id == 4 ? 'bg-yellow-100 text-yellow-800' : '' }}
                            ">
                                {{ $activity->house_name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-bold {{ $activity->points > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $activity->points > 0 ? '+' : '' }}{{ $activity->points }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-gray-100 text-gray-800">
                                {{ ucfirst(str_replace('_', ' ', $activity->type)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">
                            {{ $activity->reason ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $activity->recipient_name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $activity->awarder_name ?? 'Sistema' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Award Points Modal -->
    <div x-show="showAwardModal"
         x-cloak
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
         @click.self="showAwardModal = false">
        <div class="bg-white rounded-lg shadow-xl p-8 max-w-md w-full mx-4">
            <h3 class="text-2xl font-bold mb-6" x-text="'Assegna Punti a ' + selectedHouseName"></h3>

            <form method="POST" action="{{ route('admin.house-points.award') }}">
                @csrf
                <input type="hidden" name="house_id" x-model="selectedHouseId">

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Punti</label>
                    <input type="number" name="points" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Es: 10 o -5">
                    <p class="text-sm text-gray-500 mt-1">Usa numeri negativi per rimuovere punti</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Tipo</label>
                    <select name="type" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="manual">Assegnazione Manuale</option>
                        <option value="quest_complete">Quest Completata</option>
                        <option value="achievement">Achievement</option>
                        <option value="event_win">Vittoria Evento</option>
                        <option value="good_behavior">Buon Comportamento</option>
                        <option value="rule_violation">Violazione Regole</option>
                        <option value="competition">Competizione</option>
                        <option value="attendance">Presenza</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Motivo</label>
                    <input type="text" name="reason" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Es: Eccellente lavoro in classe">
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Username Studente (Opzionale)</label>
                    <input type="text" name="user_username"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Lascia vuoto per assegnazione generale">
                </div>

                <div class="flex gap-4">
                    <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg">
                        Assegna Punti
                    </button>
                    <button type="button" @click="showAwardModal = false"
                        class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-3 px-6 rounded-lg">
                        Annulla
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Award Modal -->
    <div x-show="showBulkModal"
         x-cloak
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
         @click.self="showBulkModal = false">
        <div class="bg-white rounded-lg shadow-xl p-8 max-w-md w-full mx-4">
            <h3 class="text-2xl font-bold mb-6">Assegnazione di Massa</h3>

            <form method="POST" action="{{ route('admin.house-points.bulk-award') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Casa</label>
                    <select name="house_id" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach($houses as $house)
                        <option value="{{ $house->id }}">{{ $house->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Punti per Studente</label>
                    <input type="number" name="points_per_user" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Es: 5">
                    <p class="text-sm text-gray-500 mt-1">Ogni membro della casa riceverà questi punti</p>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Motivo</label>
                    <input type="text" name="reason" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Es: Vittoria Coppa delle Case">
                </div>

                <div class="flex gap-4">
                    <button type="submit"
                        class="flex-1 bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-6 rounded-lg">
                        Assegna a Tutti
                    </button>
                    <button type="button" @click="showBulkModal = false"
                        class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-3 px-6 rounded-lg">
                        Annulla
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reset Modal -->
    <div x-show="showResetModal"
         x-cloak
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
         @click.self="showResetModal = false">
        <div class="bg-white rounded-lg shadow-xl p-8 max-w-md w-full mx-4">
            <h3 class="text-2xl font-bold mb-4 text-red-600">⚠️ Reset Punti Case</h3>
            <p class="text-gray-700 mb-6">
                Questa azione resetterà i punti di TUTTE le case a 0. I dati attuali saranno archiviati.
                Questa operazione è irreversibile!
            </p>

            <form method="POST" action="{{ route('admin.house-points.reset') }}">
                @csrf

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Digita "RESET" per confermare
                    </label>
                    <input type="text" name="confirm" required
                        class="w-full border border-red-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-red-500"
                        placeholder="RESET">
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Nome Stagione</label>
                    <input type="text" name="season"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Es: Anno Scolastico 2025"
                        value="Anno Scolastico {{ date('Y') }}">
                </div>

                <div class="flex gap-4">
                    <button type="submit"
                        class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-6 rounded-lg">
                        Conferma Reset
                    </button>
                    <button type="button" @click="showResetModal = false"
                        class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-3 px-6 rounded-lg">
                        Annulla
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function housePointsManager() {
    return {
        showAwardModal: false,
        showBulkModal: false,
        showResetModal: false,
        selectedHouseId: null,
        selectedHouseName: '',

        openAwardModal(houseId, houseName) {
            this.selectedHouseId = houseId;
            this.selectedHouseName = houseName;
            this.showAwardModal = true;
        },

        openBulkAwardModal() {
            this.showBulkModal = true;
        },

        openResetModal() {
            this.showResetModal = true;
        }
    }
}
</script>

<style>
[x-cloak] { display: none !important; }
</style>
@endsection
