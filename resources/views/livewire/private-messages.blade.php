<div class="row">
    <!-- Conversations List -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-inbox"></i> Conversazioni</h5>
            </div>
            <div class="card-body p-0">
                <!-- Search New User -->
                <div class="p-3 border-bottom">
                    <input type="text"
                           wire:model.live.debounce.500ms="searchUser"
                           class="form-control form-control-sm"
                           placeholder="Cerca utente per nuovo messaggio...">

                    @if($searchResults->isNotEmpty())
                        <div class="list-group mt-2">
                            @foreach($searchResults as $user)
                                <button wire:click="startNewConversation({{ $user->id }})"
                                        class="list-group-item list-group-item-action p-2">
                                    <img src="{{ asset('upload/user/' . $user->avatar()) }}"
                                         class="user-avatar-small mr-2"
                                         alt="{{ $user->username }}">
                                    {{ $user->username }}
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Conversations -->
                <div class="conversations-list">
                    @if($conversations->isEmpty())
                        <div class="p-3 text-center text-muted">
                            <i class="fas fa-comments fa-3x mb-2"></i>
                            <p>Nessuna conversazione ancora</p>
                        </div>
                    @else
                        @foreach($conversations as $conversation)
                            @php
                                $otherUser = $conversation->getOtherUser(auth()->id());
                                $unreadCount = $conversation->getUnreadCountFor(auth()->id());
                            @endphp
                            <div class="conversation-item {{ $selectedConversationId == $conversation->id ? 'active' : '' }} {{ $unreadCount > 0 ? 'unread' : '' }}"
                                 wire:click="selectConversation({{ $conversation->id }})">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('upload/user/' . $otherUser->avatar()) }}"
                                         class="user-avatar-small mr-2"
                                         alt="{{ $otherUser->username }}">
                                    <div class="flex-grow-1">
                                        <strong>{{ $otherUser->username }}</strong>
                                        @if($unreadCount > 0)
                                            <span class="badge badge-danger ml-2">{{ $unreadCount }}</span>
                                        @endif
                                        @if($conversation->last_message_at)
                                            <div class="text-muted small">
                                                {{ $conversation->last_message_at->diffForHumans() }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Messages Area -->
    <div class="col-md-8">
        <div class="card">
            @if($selectedConversation)
                @php
                    $otherUser = $selectedConversation->getOtherUser(auth()->id());
                @endphp
                <div class="card-header bg-secondary text-white">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('upload/user/' . $otherUser->avatar()) }}"
                             class="user-avatar-small mr-2"
                             alt="{{ $otherUser->username }}">
                        <h5 class="mb-0">{{ $otherUser->username }}</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="messages-area mb-3" id="messagesArea">
                        @if($messages->isEmpty())
                            <div class="text-center text-muted">
                                <i class="fas fa-comment-slash fa-3x mb-2"></i>
                                <p>Inizia una conversazione!</p>
                            </div>
                        @else
                            @foreach($messages->reverse() as $message)
                                <div class="d-flex {{ $message->sender_id == auth()->id() ? 'justify-content-end' : 'justify-content-start' }} mb-2">
                                    <div class="message-bubble {{ $message->sender_id == auth()->id() ? 'message-sent' : 'message-received' }}">
                                        {{ $message->message }}
                                        <div class="small mt-1 {{ $message->sender_id == auth()->id() ? 'text-white-50' : 'text-muted' }}">
                                            {{ $message->created_at->format('H:i') }}
                                            @if($message->sender_id == auth()->id() && $message->is_read)
                                                <i class="fas fa-check-double"></i>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <!-- Send Message Form -->
                    <form wire:submit.prevent="sendMessage">
                        <div class="input-group">
                            <input type="text"
                                   wire:model="newMessage"
                                   class="form-control"
                                   placeholder="Scrivi un messaggio..."
                                   required>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i> Invia
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            @else
                <div class="card-body text-center text-muted p-5">
                    <i class="fas fa-comments fa-4x mb-3"></i>
                    <h5>Seleziona una conversazione o cercane una nuova</h5>
                    <p>I tuoi messaggi appariranno qui</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
// Scroll to bottom of messages
document.addEventListener('livewire:load', function () {
    Livewire.hook('message.processed', (message, component) => {
        const messagesArea = document.getElementById('messagesArea');
        if (messagesArea) {
            messagesArea.scrollTop = messagesArea.scrollHeight;
        }
    });
});

// Auto-scroll on new messages
window.addEventListener('messageSent', event => {
    setTimeout(() => {
        const messagesArea = document.getElementById('messagesArea');
        if (messagesArea) {
            messagesArea.scrollTop = messagesArea.scrollHeight;
        }
    }, 100);
});
</script>

<style>
.conversations-list {
    max-height: 600px;
    overflow-y: auto;
}
.conversation-item {
    padding: 1rem;
    border-bottom: 1px solid #ddd;
    cursor: pointer;
    transition: background 0.3s;
}
.conversation-item:hover {
    background: #f8f9fa;
}
.conversation-item.active {
    background: #e7f3ff;
    border-left: 4px solid #007bff;
}
.conversation-item.unread {
    background: #fff3cd;
}
.messages-area {
    height: 400px;
    overflow-y: auto;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 1rem;
    background: #f8f9fa;
}
.message-bubble {
    max-width: 70%;
    padding: 0.75rem 1rem;
    border-radius: 18px;
    margin-bottom: 0.5rem;
    word-wrap: break-word;
}
.message-sent {
    background: #007bff;
    color: white;
    text-align: right;
}
.message-received {
    background: white;
    border: 1px solid #ddd;
}
.user-avatar-small {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}
</style>
