<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classifica Case - Hogwarts</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        .house-title {
            font-family: 'Cinzel', serif;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 min-h-screen">
    <!-- Floating particles background -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-10 w-2 h-2 bg-yellow-400 rounded-full animate-pulse"></div>
        <div class="absolute top-40 right-20 w-1 h-1 bg-blue-400 rounded-full animate-pulse delay-100"></div>
        <div class="absolute bottom-20 left-1/4 w-1.5 h-1.5 bg-red-400 rounded-full animate-pulse delay-200"></div>
        <div class="absolute bottom-40 right-1/3 w-1 h-1 bg-green-400 rounded-full animate-pulse delay-300"></div>
    </div>

    <div class="container mx-auto px-4 py-8 relative z-10">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-5xl font-bold text-white mb-4 house-title">
                🏆 Coppa delle Case di Hogwarts 🏆
            </h1>
            <p class="text-gray-300 text-lg">
                Classifica ufficiale dei punti delle quattro nobili case
            </p>
        </div>

        <!-- Main Leaderboard -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            @foreach($houses as $index => $house)
            <div class="relative transform hover:scale-105 transition-transform duration-300">
                <!-- Rank badge -->
                @if($index === 0)
                <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 z-20">
                    <div class="bg-gradient-to-br from-yellow-400 to-yellow-600 text-white rounded-full w-16 h-16 flex items-center justify-center shadow-2xl border-4 border-white">
                        <span class="text-3xl">👑</span>
                    </div>
                </div>
                @endif

                <!-- Card -->
                <div class="bg-white rounded-lg shadow-2xl overflow-hidden mt-6
                    {{ $index === 0 ? 'ring-4 ring-yellow-400' : '' }}">
                    <!-- House color header -->
                    <div class="h-3 bg-gradient-to-r
                        {{ $house->id == 1 ? 'from-red-600 to-orange-500' : '' }}
                        {{ $house->id == 2 ? 'from-green-700 to-green-900' : '' }}
                        {{ $house->id == 3 ? 'from-blue-700 to-blue-900' : '' }}
                        {{ $house->id == 4 ? 'from-yellow-500 to-yellow-600' : '' }}
                    "></div>

                    <div class="p-6">
                        <!-- Rank number -->
                        <div class="flex justify-between items-start mb-4">
                            <span class="text-5xl font-bold {{ $index === 0 ? 'text-yellow-500' : 'text-gray-300' }}">
                                #{{ $house->rank }}
                            </span>
                            @if($index === 0)
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2 py-1 rounded">
                                PRIMA CASA
                            </span>
                            @endif
                        </div>

                        <!-- House name -->
                        <h2 class="text-2xl font-bold mb-2
                            {{ $house->id == 1 ? 'text-red-600' : '' }}
                            {{ $house->id == 2 ? 'text-green-700' : '' }}
                            {{ $house->id == 3 ? 'text-blue-700' : '' }}
                            {{ $house->id == 4 ? 'text-yellow-600' : '' }}
                        ">
                            {{ $house->name }}
                        </h2>

                        <!-- Points -->
                        <div class="mb-4">
                            <div class="text-5xl font-bold text-gray-800 mb-1">
                                {{ number_format($house->points ?? 0) }}
                            </div>
                            <div class="text-sm text-gray-600">Punti Totali</div>
                        </div>

                        <!-- Members -->
                        <div class="flex items-center text-gray-600 text-sm">
                            <i class="fas fa-users mr-2"></i>
                            <span>{{ $house->members }} Studenti</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Weekly Top Contributors -->
        <div class="bg-white rounded-lg shadow-2xl p-8 mb-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                <span class="text-3xl mr-3">⭐</span>
                Migliori Studenti della Settimana
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                @foreach($weeklyContributors as $index => $contributor)
                <div class="text-center">
                    <div class="relative inline-block mb-3">
                        <img src="{{ $contributor->avatar ?? '/images/default-avatar.png' }}"
                             alt="{{ $contributor->name }}"
                             class="w-20 h-20 rounded-full border-4
                                {{ $contributor->house_id == 1 ? 'border-red-600' : '' }}
                                {{ $contributor->house_id == 2 ? 'border-green-700' : '' }}
                                {{ $contributor->house_id == 3 ? 'border-blue-700' : '' }}
                                {{ $contributor->house_id == 4 ? 'border-yellow-600' : '' }}
                             ">
                        @if($index < 3)
                        <div class="absolute -top-2 -right-2 bg-gradient-to-br
                            {{ $index === 0 ? 'from-yellow-400 to-yellow-600' : '' }}
                            {{ $index === 1 ? 'from-gray-300 to-gray-400' : '' }}
                            {{ $index === 2 ? 'from-orange-400 to-orange-500' : '' }}
                            text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold">
                            {{ $index + 1 }}
                        </div>
                        @endif
                    </div>
                    <div class="font-semibold text-gray-800">{{ $contributor->name }}</div>
                    <div class="text-sm text-gray-600 mb-1">{{ $contributor->house_name }}</div>
                    <div class="text-lg font-bold
                        {{ $contributor->house_id == 1 ? 'text-red-600' : '' }}
                        {{ $contributor->house_id == 2 ? 'text-green-700' : '' }}
                        {{ $contributor->house_id == 3 ? 'text-blue-700' : '' }}
                        {{ $contributor->house_id == 4 ? 'text-yellow-600' : '' }}
                    ">
                        +{{ $contributor->total_points }} pts
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-lg shadow-2xl p-8 mb-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                <span class="text-3xl mr-3">📜</span>
                Attività Recente
            </h2>

            <div class="space-y-3">
                @foreach($recentActivity as $activity)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center
                            {{ $activity->house_id == 1 ? 'bg-red-100' : '' }}
                            {{ $activity->house_id == 2 ? 'bg-green-100' : '' }}
                            {{ $activity->house_id == 3 ? 'bg-blue-100' : '' }}
                            {{ $activity->house_id == 4 ? 'bg-yellow-100' : '' }}
                        ">
                            <span class="text-2xl font-bold
                                {{ $activity->house_id == 1 ? 'text-red-600' : '' }}
                                {{ $activity->house_id == 2 ? 'text-green-700' : '' }}
                                {{ $activity->house_id == 3 ? 'text-blue-700' : '' }}
                                {{ $activity->house_id == 4 ? 'text-yellow-600' : '' }}
                            ">
                                {{ $activity->points > 0 ? '+' : '' }}{{ $activity->points }}
                            </span>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-800">
                                {{ $activity->house_name }}
                                @if($activity->recipient_name)
                                - {{ $activity->recipient_name }}
                                @endif
                            </div>
                            <div class="text-sm text-gray-600">
                                {{ $activity->reason ?? ucfirst(str_replace('_', ' ', $activity->type)) }}
                            </div>
                        </div>
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Archived Seasons -->
        @if($archivedSeasons->count() > 0)
        <div class="bg-white rounded-lg shadow-2xl p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                <span class="text-3xl mr-3">🏛️</span>
                Archivio Stagioni Precedenti
            </h2>

            <div class="space-y-4">
                @foreach($archivedSeasons as $season)
                @php
                    $seasonData = json_decode($season->houses_data);
                    $winner = collect($seasonData)->sortByDesc('points')->first();
                @endphp
                <div class="p-4 bg-gray-50 rounded-lg flex justify-between items-center">
                    <div>
                        <div class="font-bold text-gray-800">{{ $season->season }}</div>
                        <div class="text-sm text-gray-600">
                            Vincitore: {{ $winner->name }} con {{ $winner->points }} punti
                        </div>
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ \Carbon\Carbon::parse($season->created_at)->format('d/m/Y') }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Footer -->
    <div class="text-center text-gray-400 py-8">
        <p>Hogwarts School of Witchcraft and Wizardry</p>
        <p class="text-sm">Draco Dormiens Nunquam Titillandus</p>
    </div>
</body>
</html>
