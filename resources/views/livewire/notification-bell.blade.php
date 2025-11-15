<div class="relative">
    <button wire:click="toggleDropdown" class="relative p-2 text-gray-600 hover:text-gray-900">
        <i class="fas fa-bell text-xl"></i>
        @if($unreadCount > 0)
            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                {{ $unreadCount }}
            </span>
        @endif
    </button>

    @if($showDropdown)
        <div class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 z-50">
            <div class="p-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Notifiche</h3>
            </div>

            <div class="max-h-96 overflow-y-auto">
                @forelse($notifications as $notification)
                    <div wire:click="markAsRead({{ $notification['id'] }})"
                         class="p-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer {{ $notification['is_read'] ? 'opacity-60' : 'bg-blue-50' }}">
                        <div class="flex items-start">
                            @if($notification['icon'])
                                <i class="{{ $notification['icon'] }} text-blue-600 mr-3 mt-1"></i>
                            @endif
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900">{{ $notification['title'] }}</h4>
                                <p class="text-sm text-gray-600">{{ $notification['message'] }}</p>
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ \Carbon\Carbon::parse($notification['created_at'])->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center text-gray-500">
                        Nessuna notifica
                    </div>
                @endforelse
            </div>

            @if(count($notifications) > 0)
                <div class="p-3 border-t border-gray-200 text-center">
                    <a href="{{ route('notifications.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Vedi tutte le notifiche
                    </a>
                </div>
            @endif
        </div>
    @endif

    <script>
        // Refresh notifications every 30 seconds
        setInterval(() => {
            @this.call('loadNotifications');
        }, 30000);
    </script>
</div>
