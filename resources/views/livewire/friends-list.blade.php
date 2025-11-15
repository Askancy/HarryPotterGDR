<div>
    @if(session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('message') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif
    @if(session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <!-- Tabs -->
    <div class="tab-buttons mb-4">
        <button wire:click="$set('activeTab', 'friends')"
                class="btn {{ $activeTab == 'friends' ? 'btn-primary' : 'btn-outline-primary' }}">
            <i class="fas fa-users"></i> Amici ({{ $friends->count() }})
        </button>
        <button wire:click="$set('activeTab', 'requests')"
                class="btn {{ $activeTab == 'requests' ? 'btn-primary' : 'btn-outline-primary' }}">
            <i class="fas fa-user-clock"></i> Richieste ({{ $pendingRequests->count() }})
        </button>
        <button wire:click="$set('activeTab', 'search')"
                class="btn {{ $activeTab == 'search' ? 'btn-primary' : 'btn-outline-primary' }}">
            <i class="fas fa-search"></i> Cerca Utenti
        </button>
    </div>

    <!-- Friends Tab -->
    @if($activeTab == 'friends')
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-users"></i> I tuoi Amici</h5>
            </div>
            <div class="card-body">
                @if($friends->isEmpty())
                    <div class="alert alert-info">
                        Non hai ancora amici. Cerca utenti e invia richieste di amicizia!
                    </div>
                @else
                    @foreach($friends as $friend)
                        <div class="friend-card">
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('upload/user/' . $friend->avatar()) }}"
                                     class="friend-avatar mr-3"
                                     alt="{{ $friend->username }}">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">
                                        <a href="/profile/{{ $friend->slug }}">{{ $friend->username }}</a>
                                    </h6>
                                    <p class="mb-0 text-muted small">
                                        <i class="fas fa-home"></i> {{ $friend->team() }} |
                                        <i class="fas fa-star"></i> Livello {{ $friend->level ?? 1 }}
                                    </p>
                                </div>
                                <div>
                                    <a href="/messages" class="btn btn-sm btn-primary mr-2">
                                        <i class="fas fa-envelope"></i> Messaggio
                                    </a>
                                    <button wire:click="removeFriend({{ $friend->id }})"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Sei sicuro di voler rimuovere questo amico?')">
                                        <i class="fas fa-user-times"></i> Rimuovi
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    @endif

    <!-- Requests Tab -->
    @if($activeTab == 'requests')
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user-clock"></i> Richieste di Amicizia</h5>
            </div>
            <div class="card-body">
                @if($pendingRequests->isEmpty())
                    <div class="alert alert-info">
                        Non hai richieste di amicizia in sospeso.
                    </div>
                @else
                    @foreach($pendingRequests as $request)
                        <div class="friend-card">
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('upload/user/' . $request->user->avatar()) }}"
                                     class="friend-avatar mr-3"
                                     alt="{{ $request->user->username }}">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">
                                        <a href="/profile/{{ $request->user->slug }}">
                                            {{ $request->user->username }}
                                        </a>
                                    </h6>
                                    <p class="mb-0 text-muted small">
                                        <i class="fas fa-home"></i> {{ $request->user->team() }} |
                                        <i class="fas fa-star"></i> Livello {{ $request->user->level ?? 1 }}
                                    </p>
                                    <p class="mb-0 text-muted small">
                                        <i class="fas fa-clock"></i> {{ $request->requested_at->diffForHumans() }}
                                    </p>
                                </div>
                                <div>
                                    <button wire:click="acceptFriendRequest({{ $request->id }})"
                                            class="btn btn-sm btn-success mr-2">
                                        <i class="fas fa-check"></i> Accetta
                                    </button>
                                    <button wire:click="declineFriendRequest({{ $request->id }})"
                                            class="btn btn-sm btn-danger">
                                        <i class="fas fa-times"></i> Rifiuta
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    @endif

    <!-- Search Tab -->
    @if($activeTab == 'search')
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-search"></i> Cerca Utenti</h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <input type="text"
                           wire:model.live.debounce.500ms="searchQuery"
                           class="form-control"
                           placeholder="Cerca per username...">
                </div>

                @if(strlen($searchQuery) < 3 && strlen($searchQuery) > 0)
                    <div class="alert alert-info">
                        Inserisci almeno 3 caratteri per cercare.
                    </div>
                @endif

                @if($searchResults->isNotEmpty())
                    @foreach($searchResults as $user)
                        <div class="friend-card">
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('upload/user/' . $user->avatar()) }}"
                                     class="friend-avatar mr-3"
                                     alt="{{ $user->username }}">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">
                                        <a href="/profile/{{ $user->slug }}">{{ $user->username }}</a>
                                    </h6>
                                    <p class="mb-0 text-muted small">
                                        <i class="fas fa-home"></i> {{ $user->team() }} |
                                        <i class="fas fa-star"></i> Livello {{ $user->level ?? 1 }}
                                    </p>
                                </div>
                                <div>
                                    @php
                                        $isFriend = auth()->user()->isFriendsWith($user);
                                        $hasPending = \App\Models\Friendship::where(function ($q) use ($user) {
                                            $q->where('user_id', auth()->id())->where('friend_id', $user->id);
                                        })->orWhere(function ($q) use ($user) {
                                            $q->where('user_id', $user->id)->where('friend_id', auth()->id());
                                        })->where('status', 'pending')->exists();
                                    @endphp

                                    @if($isFriend)
                                        <span class="badge badge-success">
                                            <i class="fas fa-check"></i> Amici
                                        </span>
                                    @elseif($hasPending)
                                        <span class="badge badge-warning">In sospeso</span>
                                    @else
                                        <button wire:click="sendFriendRequest({{ $user->id }})"
                                                class="btn btn-sm btn-success">
                                            <i class="fas fa-user-plus"></i> Aggiungi
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @elseif(strlen($searchQuery) >= 3)
                    <div class="alert alert-info">
                        Nessun utente trovato.
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>

<style>
.friend-card {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
    transition: all 0.3s;
}
.friend-card:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
.friend-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
}
</style>
