@extends('front.layouts.app')

@section('content')
<div class="min-h-screen py-8"
     x-data="commonRoom()"
     x-init="init()"
     :class="{
         'bg-gradient-to-br from-red-900 via-red-800 to-yellow-900': houseId === 1,
         'bg-gradient-to-br from-green-900 via-green-800 to-gray-900': houseId === 2,
         'bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900': houseId === 3,
         'bg-gradient-to-br from-yellow-800 via-yellow-700 to-yellow-900': houseId === 4
     }">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header Sala Comune -->
        <div class="text-center mb-8">
            <h1 class="text-5xl font-magic font-bold text-white mb-2 drop-shadow-2xl">
                <i :class="{
                    'fas fa-lion text-red-400': houseId === 1,
                    'fas fa-snake text-green-400': houseId === 2,
                    'fas fa-crow text-blue-400': houseId === 3,
                    'fas fa-otter text-yellow-400': houseId === 4
                }" class="mr-3"></i>
                Sala Comune <span x-text="houseName"></span>
            </h1>
            <p class="text-xl text-gray-200">
                <i class="fas fa-users mr-2"></i>
                <span x-text="onlineMembers"></span> membri online
                <span class="mx-2">•</span>
                <span x-text="totalPoints"></span> punti casa
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Left Sidebar - Info & Members -->
            <div class="lg:col-span-1 space-y-6">

                <!-- House Stats -->
                <div class="bg-black bg-opacity-50 backdrop-blur-lg rounded-xl p-6 border-2"
                     :class="{
                         'border-red-500': houseId === 1,
                         'border-green-500': houseId === 2,
                         'border-blue-500': houseId === 3,
                         'border-yellow-500': houseId === 4
                     }">
                    <h3 class="text-xl font-magic font-bold text-white mb-4">
                        <i class="fas fa-chart-line mr-2"></i>
                        Statistiche Casa
                    </h3>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">Posizione Classifica:</span>
                            <span class="text-2xl font-bold"
                                  :class="{
                                      'text-red-400': houseId === 1,
                                      'text-green-400': houseId === 2,
                                      'text-blue-400': houseId === 3,
                                      'text-yellow-400': houseId === 4
                                  }"
                                  x-text="houseRank + '°'"></span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">Punti Totali:</span>
                            <span class="text-xl font-bold text-yellow-400" x-text="totalPoints"></span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">Membri Totali:</span>
                            <span class="text-xl font-bold text-white" x-text="totalMembers"></span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">Quest Completate:</span>
                            <span class="text-xl font-bold text-green-400" x-text="completedQuests"></span>
                        </div>
                    </div>
                </div>

                <!-- Online Members -->
                <div class="bg-black bg-opacity-50 backdrop-blur-lg rounded-xl p-6 border-2"
                     :class="{
                         'border-red-500': houseId === 1,
                         'border-green-500': houseId === 2,
                         'border-blue-500': houseId === 3,
                         'border-yellow-500': houseId === 4
                     }">
                    <h3 class="text-xl font-magic font-bold text-white mb-4">
                        <i class="fas fa-users mr-2"></i>
                        Membri Online
                    </h3>

                    <div class="space-y-3 max-h-64 overflow-y-auto">
                        <template x-for="member in members" :key="member.id">
                            <div class="flex items-center space-x-3 p-2 rounded-lg hover:bg-white hover:bg-opacity-10 transition-colors">
                                <div class="relative">
                                    <img :src="'/upload/user/' + member.avatar"
                                         :alt="member.username"
                                         class="w-10 h-10 rounded-full border-2"
                                         :class="{
                                             'border-red-400': houseId === 1,
                                             'border-green-400': houseId === 2,
                                             'border-blue-400': houseId === 3,
                                             'border-yellow-400': houseId === 4
                                         }">
                                    <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-black rounded-full"
                                         x-show="member.is_online"></div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-white font-medium text-sm" x-text="member.username"></p>
                                    <p class="text-xs text-gray-400">Livello <span x-text="member.level"></span></p>
                                </div>
                                <template x-if="member.is_prefect">
                                    <i class="fas fa-star text-yellow-400" title="Prefetto"></i>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Announcements -->
                <div class="bg-black bg-opacity-50 backdrop-blur-lg rounded-xl p-6 border-2"
                     :class="{
                         'border-red-500': houseId === 1,
                         'border-green-500': houseId === 2,
                         'border-blue-500': houseId === 3,
                         'border-yellow-500': houseId === 4
                     }">
                    <h3 class="text-xl font-magic font-bold text-white mb-4">
                        <i class="fas fa-bullhorn mr-2"></i>
                        Annunci
                    </h3>

                    <div class="space-y-3">
                        <template x-for="announcement in announcements" :key="announcement.id">
                            <div class="p-3 rounded-lg"
                                 :class="{
                                     'bg-red-900 bg-opacity-30 border border-red-600': announcement.priority === 'urgent',
                                     'bg-yellow-900 bg-opacity-30 border border-yellow-600': announcement.priority === 'high',
                                     'bg-blue-900 bg-opacity-30 border border-blue-600': announcement.priority === 'medium',
                                     'bg-gray-900 bg-opacity-30 border border-gray-600': announcement.priority === 'low'
                                 }">
                                <div class="flex items-start justify-between mb-2">
                                    <h4 class="font-bold text-white text-sm" x-text="announcement.title"></h4>
                                    <span class="text-xs text-gray-400" x-text="formatDate(announcement.created_at)"></span>
                                </div>
                                <p class="text-sm text-gray-300" x-text="announcement.content"></p>
                                <p class="text-xs text-gray-500 mt-2">
                                    - <span x-text="announcement.posted_by"></span>
                                </p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Main Content - Chat -->
            <div class="lg:col-span-2">
                <div class="bg-black bg-opacity-50 backdrop-blur-lg rounded-xl border-2 overflow-hidden"
                     :class="{
                         'border-red-500': houseId === 1,
                         'border-green-500': houseId === 2,
                         'border-blue-500': houseId === 3,
                         'border-yellow-500': houseId === 4
                     }">

                    <!-- Chat Header -->
                    <div class="p-4 border-b"
                         :class="{
                             'border-red-600 bg-red-900 bg-opacity-30': houseId === 1,
                             'border-green-600 bg-green-900 bg-opacity-30': houseId === 2,
                             'border-blue-600 bg-blue-900 bg-opacity-30': houseId === 3,
                             'border-yellow-600 bg-yellow-900 bg-opacity-30': houseId === 4
                         }">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-magic font-bold text-white">
                                <i class="fas fa-comments mr-2"></i>
                                Chat di Casa
                            </h3>
                            <div class="flex items-center space-x-3">
                                <button @click="loadMessages()" class="text-gray-300 hover:text-white transition-colors" title="Ricarica">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                                <div class="flex items-center space-x-1">
                                    <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                                    <span class="text-sm text-gray-300">Online</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Messages Container -->
                    <div id="messages-container"
                         class="h-96 md:h-[500px] overflow-y-auto p-4 space-y-4"
                         x-ref="messagesContainer">

                        <template x-for="message in messages" :key="message.id">
                            <div class="flex"
                                 :class="message.user_id === currentUserId ? 'justify-end' : 'justify-start'">

                                <!-- Other's Message -->
                                <div x-show="message.user_id !== currentUserId"
                                     class="flex items-start space-x-2 max-w-lg">
                                    <img :src="'/upload/user/' + message.avatar"
                                         class="w-8 h-8 rounded-full">
                                    <div>
                                        <div class="flex items-center space-x-2 mb-1">
                                            <span class="text-xs font-bold"
                                                  :class="{
                                                      'text-red-400': houseId === 1,
                                                      'text-green-400': houseId === 2,
                                                      'text-blue-400': houseId === 3,
                                                      'text-yellow-400': houseId === 4
                                                  }"
                                                  x-text="message.username"></span>
                                            <span class="text-xs text-gray-500" x-text="formatTime(message.created_at)"></span>
                                        </div>
                                        <div class="bg-white bg-opacity-20 rounded-lg rounded-tl-none px-4 py-2">
                                            <p class="text-white text-sm" x-text="message.message"></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Own Message -->
                                <div x-show="message.user_id === currentUserId"
                                     class="flex items-start space-x-2 max-w-lg">
                                    <div class="flex-1 text-right">
                                        <div class="flex items-center justify-end space-x-2 mb-1">
                                            <span class="text-xs text-gray-500" x-text="formatTime(message.created_at)"></span>
                                            <span class="text-xs font-bold text-white">Tu</span>
                                        </div>
                                        <div class="inline-block rounded-lg rounded-tr-none px-4 py-2"
                                             :class="{
                                                 'bg-red-600': houseId === 1,
                                                 'bg-green-600': houseId === 2,
                                                 'bg-blue-600': houseId === 3,
                                                 'bg-yellow-600': houseId === 4
                                             }">
                                            <p class="text-white text-sm" x-text="message.message"></p>
                                        </div>
                                    </div>
                                    <img :src="'/upload/user/' + message.avatar"
                                         class="w-8 h-8 rounded-full">
                                </div>
                            </div>
                        </template>

                        <!-- Empty State -->
                        <div x-show="messages.length === 0" class="text-center py-12">
                            <i class="fas fa-comments text-6xl text-gray-600 mb-4"></i>
                            <p class="text-gray-400">Nessun messaggio ancora. Inizia la conversazione!</p>
                        </div>
                    </div>

                    <!-- Message Input -->
                    <div class="p-4 border-t"
                         :class="{
                             'border-red-600 bg-red-900 bg-opacity-20': houseId === 1,
                             'border-green-600 bg-green-900 bg-opacity-20': houseId === 2,
                             'border-blue-600 bg-blue-900 bg-opacity-20': houseId === 3,
                             'border-yellow-600 bg-yellow-900 bg-opacity-20': houseId === 4
                         }">
                        <form @submit.prevent="sendMessage()" class="flex items-center space-x-3">
                            <input type="text"
                                   x-model="newMessage"
                                   placeholder="Scrivi un messaggio alla tua casa..."
                                   class="flex-1 bg-white bg-opacity-10 border border-gray-600 rounded-lg px-4 py-3 text-white placeholder-gray-400 focus:outline-none focus:border-opacity-50 transition-colors"
                                   :class="{
                                       'focus:border-red-400': houseId === 1,
                                       'focus:border-green-400': houseId === 2,
                                       'focus:border-blue-400': houseId === 3,
                                       'focus:border-yellow-400': houseId === 4
                                   }"
                                   maxlength="500">

                            <button type="submit"
                                    :disabled="!newMessage.trim()"
                                    class="px-6 py-3 rounded-lg font-bold text-white transition-all transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                                    :class="{
                                        'bg-red-600 hover:bg-red-700': houseId === 1,
                                        'bg-green-600 hover:bg-green-700': houseId === 2,
                                        'bg-blue-600 hover:bg-blue-700': houseId === 3,
                                        'bg-yellow-600 hover:bg-yellow-700': houseId === 4
                                    }">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Invia
                            </button>
                        </form>
                        <p class="text-xs text-gray-500 mt-2">
                            <span x-text="newMessage.length"></span>/500 caratteri
                        </p>
                    </div>
                </div>

                <!-- House Events -->
                <div class="mt-6 bg-black bg-opacity-50 backdrop-blur-lg rounded-xl p-6 border-2"
                     :class="{
                         'border-red-500': houseId === 1,
                         'border-green-500': houseId === 2,
                         'border-blue-500': houseId === 3,
                         'border-yellow-500': houseId === 4
                     }">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-magic font-bold text-white">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            Eventi della Casa
                        </h3>
                        <button @click="showEventModal = true"
                                class="px-4 py-2 rounded-lg font-medium text-white transition-colors"
                                :class="{
                                    'bg-red-600 hover:bg-red-700': houseId === 1,
                                    'bg-green-600 hover:bg-green-700': houseId === 2,
                                    'bg-blue-600 hover:bg-blue-700': houseId === 3,
                                    'bg-yellow-600 hover:bg-yellow-700': houseId === 4
                                }">
                            <i class="fas fa-plus mr-2"></i>
                            Crea Evento
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <template x-for="event in events" :key="event.id">
                            <div class="bg-white bg-opacity-10 rounded-lg p-4 hover:bg-opacity-20 transition-colors">
                                <div class="flex items-start justify-between mb-2">
                                    <h4 class="font-bold text-white" x-text="event.title"></h4>
                                    <span class="px-2 py-1 rounded text-xs font-medium"
                                          :class="{
                                              'bg-purple-600 text-white': event.type === 'quest',
                                              'bg-blue-600 text-white': event.type === 'meeting',
                                              'bg-red-600 text-white': event.type === 'tournament',
                                              'bg-yellow-600 text-gray-900': event.type === 'celebration',
                                              'bg-green-600 text-white': event.type === 'study'
                                          }"
                                          x-text="event.type"></span>
                                </div>
                                <p class="text-sm text-gray-300 mb-3" x-text="event.description"></p>
                                <div class="flex items-center justify-between text-xs text-gray-400">
                                    <span>
                                        <i class="far fa-calendar mr-1"></i>
                                        <span x-text="formatDate(event.event_date)"></span>
                                    </span>
                                    <span>
                                        <i class="fas fa-users mr-1"></i>
                                        <span x-text="event.participants_count"></span> partecipanti
                                    </span>
                                </div>
                                <button @click="joinEvent(event.id)"
                                        class="w-full mt-3 px-4 py-2 rounded font-medium text-white transition-colors"
                                        :class="{
                                            'bg-red-600 hover:bg-red-700': houseId === 1,
                                            'bg-green-600 hover:bg-green-700': houseId === 2,
                                            'bg-blue-600 hover:bg-blue-700': houseId === 3,
                                            'bg-yellow-600 hover:bg-yellow-700': houseId === 4
                                        }">
                                    <i class="fas fa-check mr-2"></i>
                                    Partecipa
                                </button>
                            </div>
                        </template>

                        <div x-show="events.length === 0" class="col-span-2 text-center py-8 text-gray-400">
                            <i class="fas fa-calendar-times text-4xl mb-2"></i>
                            <p>Nessun evento programmato</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function commonRoom() {
    return {
        houseId: {{ $house->id }},
        houseName: '{{ $house->name }}',
        currentUserId: {{ auth()->id() }},
        messages: [],
        members: [],
        announcements: [],
        events: [],
        newMessage: '',
        onlineMembers: 0,
        totalPoints: {{ $house->point }},
        houseRank: {{ $houseRank }},
        totalMembers: {{ $totalMembers }},
        completedQuests: {{ $completedQuests }},
        lastMessageId: 0,
        showEventModal: false,

        init() {
            this.loadMessages();
            this.loadMembers();
            this.loadAnnouncements();
            this.loadEvents();

            // Polling ogni 3 secondi per nuovi messaggi
            setInterval(() => {
                this.loadNewMessages();
            }, 3000);

            // Refresh membri online ogni 10 secondi
            setInterval(() => {
                this.loadMembers();
            }, 10000);
        },

        async loadMessages() {
            try {
                const response = await fetch('/api/house/messages?house_id=' + this.houseId);
                const data = await response.json();
                this.messages = data.messages || [];
                if (this.messages.length > 0) {
                    this.lastMessageId = this.messages[this.messages.length - 1].id;
                }
                this.$nextTick(() => this.scrollToBottom());
            } catch (error) {
                console.error('Error loading messages:', error);
            }
        },

        async loadNewMessages() {
            try {
                const response = await fetch('/api/house/messages/new?house_id=' + this.houseId + '&after_id=' + this.lastMessageId);
                const data = await response.json();
                if (data.messages && data.messages.length > 0) {
                    this.messages = [...this.messages, ...data.messages];
                    this.lastMessageId = this.messages[this.messages.length - 1].id;
                    this.$nextTick(() => this.scrollToBottom());
                }
            } catch (error) {
                console.error('Error loading new messages:', error);
            }
        },

        async sendMessage() {
            if (!this.newMessage.trim()) return;

            try {
                const response = await fetch('/api/house/messages', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        house_id: this.houseId,
                        message: this.newMessage
                    })
                });

                if (response.ok) {
                    this.newMessage = '';
                    await this.loadNewMessages();
                }
            } catch (error) {
                console.error('Error sending message:', error);
            }
        },

        async loadMembers() {
            try {
                const response = await fetch('/api/house/members?house_id=' + this.houseId);
                const data = await response.json();
                this.members = data.members || [];
                this.onlineMembers = this.members.filter(m => m.is_online).length;
            } catch (error) {
                console.error('Error loading members:', error);
            }
        },

        async loadAnnouncements() {
            try {
                const response = await fetch('/api/house/announcements?house_id=' + this.houseId);
                const data = await response.json();
                this.announcements = data.announcements || [];
            } catch (error) {
                console.error('Error loading announcements:', error);
            }
        },

        async loadEvents() {
            try {
                const response = await fetch('/api/house/events?house_id=' + this.houseId);
                const data = await response.json();
                this.events = data.events || [];
            } catch (error) {
                console.error('Error loading events:', error);
            }
        },

        async joinEvent(eventId) {
            try {
                const response = await fetch('/api/house/events/' + eventId + '/join', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (response.ok) {
                    alert('Ti sei iscritto all\'evento!');
                    this.loadEvents();
                }
            } catch (error) {
                console.error('Error joining event:', error);
            }
        },

        scrollToBottom() {
            const container = this.$refs.messagesContainer;
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        },

        formatTime(timestamp) {
            const date = new Date(timestamp);
            return date.toLocaleTimeString('it-IT', { hour: '2-digit', minute: '2-digit' });
        },

        formatDate(timestamp) {
            const date = new Date(timestamp);
            return date.toLocaleDateString('it-IT', { day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit' });
        }
    }
}
</script>
@endpush

@endsection
