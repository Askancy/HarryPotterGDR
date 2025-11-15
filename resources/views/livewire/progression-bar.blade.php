<div class="bg-gradient-to-r from-purple-600 to-blue-600 rounded-lg shadow-lg p-4 text-white">
    <div class="flex items-center justify-between mb-2">
        <div>
            <h3 class="text-sm font-semibold opacity-90">Livello {{ $level }}</h3>
            <p class="text-xs opacity-75">{{ $currentExp }} / {{ $requiredExp }} EXP</p>
        </div>
        <div class="text-right">
            <div class="text-2xl font-bold">{{ $percentage }}%</div>
        </div>
    </div>

    <div class="w-full bg-white bg-opacity-30 rounded-full h-3 overflow-hidden">
        <div class="bg-white h-full rounded-full transition-all duration-500 ease-out"
             style="width: {{ $percentage }}%">
        </div>
    </div>

    <div class="mt-2 flex items-center justify-between text-xs opacity-90">
        <span>
            <i class="fas fa-star"></i> Progressione
        </span>
        <span>
            Prossimo livello: {{ $requiredExp - $currentExp }} EXP
        </span>
    </div>
</div>
