<div class="bg-white rounded-lg shadow-lg">
    <!-- Visitors Sidebar -->
    <div class="border-b border-gray-200 p-4">
        <h3 class="text-lg font-semibold text-gray-900 mb-3">
            <i class="fas fa-users text-blue-600"></i> Visitatori Presenti ({{ count($visitors) }})
        </h3>
        <div class="flex flex-wrap gap-2">
            @foreach($visitors as $visitor)
                <div class="flex items-center bg-gray-100 rounded-full px-3 py-1">
                    <img src="/upload/user/{{ $visitor['avatar'] }}"
                         alt="{{ $visitor['username'] }}"
                         class="w-6 h-6 rounded-full mr-2">
                    <span class="text-sm text-gray-700">{{ $visitor['username'] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Chat Messages -->
    <div class="h-96 overflow-y-auto p-4 space-y-3" id="chat-messages">
        @foreach($messages as $message)
            <div class="flex items-start {{ $message['user_id'] == auth()->id() ? 'justify-end' : '' }}">
                @if($message['user_id'] != auth()->id())
                    <img src="/upload/user/{{ $message['user']['avatar'] ?? 'default.jpg' }}"
                         alt="{{ $message['user']['username'] }}"
                         class="w-8 h-8 rounded-full mr-2">
                @endif

                <div class="max-w-xs {{ $message['user_id'] == auth()->id() ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-900' }} rounded-lg px-4 py-2">
                    @if($message['user_id'] != auth()->id())
                        <p class="text-xs font-semibold mb-1">{{ $message['user']['username'] }}</p>
                    @endif

                    @if($message['message_type'] == 'system')
                        <p class="text-sm italic">{{ $message['message'] }}</p>
                    @elseif($message['message_type'] == 'action')
                        <p class="text-sm italic">*{{ $message['message'] }}*</p>
                    @else
                        <p class="text-sm">{{ $message['message'] }}</p>
                    @endif

                    <p class="text-xs opacity-75 mt-1">
                        {{ \Carbon\Carbon::parse($message['created_at'])->format('H:i') }}
                    </p>
                </div>

                @if($message['user_id'] == auth()->id())
                    <img src="/upload/user/{{ $message['user']['avatar'] ?? 'default.jpg' }}"
                         alt="{{ $message['user']['username'] }}"
                         class="w-8 h-8 rounded-full ml-2">
                @endif
            </div>
        @endforeach
    </div>

    <!-- Message Input -->
    <div class="border-t border-gray-200 p-4">
        <form wire:submit.prevent="sendMessage" class="flex gap-2">
            <input type="text"
                   wire:model="newMessage"
                   placeholder="Scrivi un messaggio..."
                   class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                   maxlength="500">
            <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-paper-plane"></i> Invia
            </button>
        </form>
        @error('newMessage')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <script>
        // Auto-scroll to bottom
        const chatMessages = document.getElementById('chat-messages');
        if (chatMessages) {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // Refresh messages every 5 seconds
        setInterval(() => {
            @this.call('refreshMessages');
        }, 5000);

        // Listen for new messages
        Livewire.on('message-sent', () => {
            setTimeout(() => {
                const chatMessages = document.getElementById('chat-messages');
                if (chatMessages) {
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }
            }, 100);
        });
    </script>
</div>
