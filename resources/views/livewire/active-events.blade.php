<div>
    @if(count($activeEvents) > 0)
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400 text-xl"></i>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-medium text-yellow-800">
                        Eventi Attivi ({{ count($activeEvents) }})
                    </h3>
                    <div class="mt-2 space-y-2">
                        @foreach($activeEvents as $event)
                            <div class="bg-white rounded-lg p-3 shadow-sm">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900">{{ $event['event']['name'] }}</h4>
                                        <p class="text-sm text-gray-600">{{ $event['event']['description'] }}</p>

                                        @if($event['location'])
                                            <p class="text-xs text-gray-500 mt-1">
                                                <i class="fas fa-map-marker-alt"></i> {{ $event['location']['name'] }}
                                            </p>
                                        @elseif($event['shop'])
                                            <p class="text-xs text-gray-500 mt-1">
                                                <i class="fas fa-store"></i> {{ $event['shop']['name'] }}
                                            </p>
                                        @endif

                                        <p class="text-xs text-red-600 mt-1">
                                            <i class="fas fa-clock"></i>
                                            Scade: {{ \Carbon\Carbon::parse($event['expires_at'])->diffForHumans() }}
                                        </p>
                                    </div>
                                    <a href="{{ route('events.show', $event['id']) }}"
                                       class="ml-3 bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 text-sm font-medium whitespace-nowrap">
                                        Partecipa
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        // Refresh events every minute
        setInterval(() => {
            @this.call('loadEvents');
        }, 60000);
    </script>
</div>
