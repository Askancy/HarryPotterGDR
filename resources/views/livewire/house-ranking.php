<div wire:poll.{{ $refreshInterval }}ms="loadRanking" class="bg-white rounded-lg shadow-lg p-6">
    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
        <span class="text-2xl mr-2">🏆</span>
        Classifica Case
    </h3>

    <div class="space-y-3">
        @foreach($houses as $house)
        <div class="relative overflow-hidden rounded-lg border-2 {{ $house['rank'] === 1 ? 'border-yellow-400 shadow-lg' : 'border-gray-300' }}">
            <!-- Background gradient -->
            <div class="absolute inset-0 opacity-10"
                 style="background: linear-gradient(to right, {{ $house['color'] }}, {{ $house['color'] }}80);">
            </div>

            <!-- Content -->
            <div class="relative p-4 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="text-2xl font-bold {{ $house['rank'] === 1 ? 'text-yellow-500' : 'text-gray-400' }}">
                        #{{ $house['rank'] }}
                    </div>
                    <div>
                        <div class="font-bold text-gray-800">{{ $house['name'] }}</div>
                        <div class="text-sm text-gray-600">
                            {{ $house['members'] }} membri
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold"
                         style="color: {{ $house['color'] }}">
                        {{ number_format($house['points']) }}
                    </div>
                    <div class="text-xs text-gray-500">punti</div>
                </div>
            </div>

            <!-- Winner crown -->
            @if($house['rank'] === 1)
            <div class="absolute -top-2 -right-2 bg-yellow-400 text-white rounded-full p-2 shadow-lg">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
            </div>
            @endif
        </div>
        @endforeach
    </div>

    <!-- Progress bars showing relative standings -->
    <div class="mt-6 pt-6 border-t border-gray-200">
        <div class="text-sm text-gray-600 mb-3 font-semibold">Distribuzione Punti</div>
        @foreach($houses as $house)
        <div class="mb-2">
            <div class="flex justify-between text-xs text-gray-600 mb-1">
                <span>{{ $house['name'] }}</span>
                <span>{{ number_format($house['points']) }} pts</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="h-2 rounded-full transition-all duration-500"
                     style="width: {{ $maxPoints > 0 ? ($house['points'] / $maxPoints * 100) : 0 }}%; background: linear-gradient(to right, {{ $house['color'] }}, {{ $house['color'] }}cc);">
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Last update indicator -->
    <div class="mt-4 text-xs text-gray-500 text-center">
        <span wire:loading.remove>Aggiornato pochi secondi fa</span>
        <span wire:loading>Aggiornamento in corso...</span>
    </div>
</div>
