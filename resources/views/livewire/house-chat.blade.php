<div wire:poll.3s="refreshMessages" class="flex flex-col h-full">
    <!-- Chat Messages Area -->
    <div class="flex-1 overflow-y-auto p-4 space-y-4" id="chat-messages">
        @foreach($messages as $message)
        <div class="flex items-start space-x-3 {{ $message['is_own'] ? 'flex-row-reverse space-x-reverse' : '' }}">
            <!-- Avatar -->
            <img src="{{ $message['user_avatar'] }}"
                 alt="{{ $message['user_name'] }}"
                 class="w-10 h-10 rounded-full border-2 border-gray-300">

            <!-- Message Bubble -->
            <div class="{{ $message['is_own'] ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-800' }} rounded-lg px-4 py-2 max-w-xs lg:max-w-md">
                @if(!$message['is_own'])
                <div class="text-xs font-semibold mb-1 text-gray-600">
                    {{ $message['user_name'] }}
                </div>
                @endif

                <div class="text-sm">
                    {{ $message['message'] }}
                </div>

                <div class="text-xs mt-1 {{ $message['is_own'] ? 'text-blue-200' : 'text-gray-500' }}">
                    {{ $message['created_at'] }}
                </div>
            </div>
        </div>
        @endforeach

        @if(count($messages) === 0)
        <div class="text-center text-gray-500 py-8">
            <i class="fas fa-comments text-4xl mb-2"></i>
            <p>Nessun messaggio ancora. Inizia una conversazione!</p>
        </div>
        @endif
    </div>

    <!-- Online Members Sidebar -->
    <div class="border-t border-gray-200 bg-gray-50 p-4">
        <h4 class="text-sm font-semibold text-gray-700 mb-2">
            Membri Online ({{ count(array_filter($onlineMembers, fn($m) => $m['is_online'])) }})
        </h4>
        <div class="flex flex-wrap gap-2">
            @foreach(array_slice($onlineMembers, 0, 10) as $member)
            @if($member['is_online'])
            <div class="flex items-center space-x-2 bg-white rounded-full px-3 py-1 shadow-sm">
                <div class="relative">
                    <img src="{{ $member['avatar'] }}"
                         alt="{{ $member['name'] }}"
                         class="w-6 h-6 rounded-full">
                    <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></div>
                </div>
                <span class="text-xs font-medium text-gray-700">{{ $member['name'] }}</span>
                <span class="text-xs text-gray-500">Lv.{{ $member['level'] }}</span>
            </div>
            @endif
            @endforeach
        </div>
    </div>

    <!-- Message Input -->
    <div class="border-t border-gray-200 p-4 bg-white">
        <form wire:submit="sendMessage" class="flex space-x-2">
            <input type="text"
                   wire:model="newMessage"
                   placeholder="Scrivi un messaggio..."
                   maxlength="500"
                   class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                   autocomplete="off">

            <button type="submit"
                    wire:loading.attr="disabled"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition-colors disabled:opacity-50">
                <span wire:loading.remove>
                    <i class="fas fa-paper-plane"></i>
                </span>
                <span wire:loading>
                    <i class="fas fa-spinner fa-spin"></i>
                </span>
            </button>
        </form>

        <!-- Character Counter -->
        <div class="text-xs text-gray-500 mt-1 text-right">
            {{ strlen($newMessage) }}/500 caratteri
        </div>

        @error('newMessage')
        <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
        @enderror
    </div>
</div>

<script>
    // Auto-scroll to bottom when new messages arrive
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('message-sent', () => {
            const chatContainer = document.getElementById('chat-messages');
            if (chatContainer) {
                chatContainer.scrollTop = chatContainer.scrollHeight;
            }
        });
    });
</script>
