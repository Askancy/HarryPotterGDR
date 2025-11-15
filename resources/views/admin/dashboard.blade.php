@extends('admin.layouts.modern')

@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">

    <!-- Welcome Banner -->
    <div class="magical-gradient rounded-lg shadow-xl overflow-hidden">
        <div class="px-6 py-8 sm:p-10 sm:pb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-magic font-bold text-white">
                        Benvenuto, {{ Auth::user()->username }}!
                    </h2>
                    <p class="mt-2 text-indigo-100">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        {{ now()->isoFormat('dddd, D MMMM YYYY') }}
                    </p>
                </div>
                <div class="hidden sm:block">
                    <i class="fas fa-hat-wizard text-6xl text-white opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Total Users -->
        <div class="bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow duration-200">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white">
                            <i class="fas fa-users text-2xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Utenti Totali
                            </dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">
                                    {{ \App\Models\User::count() }}
                                </div>
                                <div class="ml-2 flex items-baseline text-sm font-semibold text-green-600">
                                    <i class="fas fa-arrow-up mr-1"></i>
                                    +12%
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('admin.user.index') }}" class="font-medium text-blue-600 hover:text-blue-500">
                        Visualizza tutti <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Active Quest -->
        <div class="bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow duration-200">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-purple-500 text-white">
                            <i class="fas fa-scroll text-2xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Quest Attive
                            </dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">
                                    {{ \App\Models\Quest::count() }}
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('admin.quest.index') }}" class="font-medium text-purple-600 hover:text-purple-500">
                        Gestisci quest <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Creatures -->
        <div class="bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow duration-200">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-green-500 text-white">
                            <i class="fas fa-dragon text-2xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Creature
                            </dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">
                                    {{ \App\Models\Creature::count() }}
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('admin.creature.index') }}" class="font-medium text-green-600 hover:text-green-500">
                        Gestisci bestiario <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Forum Posts -->
        <div class="bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow duration-200">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-yellow-500 text-white">
                            <i class="fas fa-comments text-2xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Topic Forum
                            </dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">
                                    {{ \App\Models\ForumTopic::count() }}
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('admin.forum.index') }}" class="font-medium text-yellow-600 hover:text-yellow-500">
                        Modera forum <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Houses Ranking -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-magic font-bold text-gray-900">
                <i class="fas fa-trophy mr-2 text-yellow-500"></i>
                Classifica Casate
            </h3>
        </div>
        <div class="px-6 py-4">
            @php
                $houses = \App\Models\Team::orderBy('point', 'desc')->get();
                $houseColors = [
                    1 => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'border' => 'border-red-500'],
                    2 => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'border' => 'border-green-600'],
                    3 => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'border' => 'border-blue-600'],
                    4 => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'border' => 'border-yellow-500'],
                ];
            @endphp

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($houses as $index => $house)
                <div class="relative {{ $houseColors[$house->id]['bg'] }} rounded-lg p-4 border-l-4 {{ $houseColors[$house->id]['border'] }} hover:shadow-md transition-shadow">
                    @if($index == 0)
                    <div class="absolute -top-2 -right-2">
                        <i class="fas fa-crown text-yellow-500 text-2xl"></i>
                    </div>
                    @endif
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium {{ $houseColors[$house->id]['text'] }} opacity-75">
                                {{ $index + 1 }}° Posto
                            </p>
                            <p class="text-xl font-magic font-bold {{ $houseColors[$house->id]['text'] }} mt-1">
                                {{ $house->name }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-3xl font-bold {{ $houseColors[$house->id]['text'] }}">
                                {{ $house->point }}
                            </p>
                            <p class="text-xs {{ $houseColors[$house->id]['text'] }} opacity-75">
                                punti
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Activity and Charts Row -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

        <!-- Recent Users -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-magic font-bold text-gray-900">
                    <i class="fas fa-user-plus mr-2 text-blue-500"></i>
                    Ultimi Utenti Registrati
                </h3>
            </div>
            <div class="divide-y divide-gray-200">
                @foreach(\App\Models\User::orderBy('created_at', 'desc')->take(5)->get() as $user)
                <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <img class="h-10 w-10 rounded-full object-cover border-2 border-gray-300"
                                 src="{{ url('upload/user/'.$user->avatar()) }}"
                                 alt="{{ $user->username }}">
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $user->username }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    @if($user->team)
                                        @php
                                            $teamData = \App\Models\Team::find($user->team);
                                        @endphp
                                        @if($teamData)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $houseColors[$user->team]['bg'] }} {{ $houseColors[$user->team]['text'] }}">
                                                {{ $teamData->name }}
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-gray-400">Nessuna casata</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-500">
                                {{ $user->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Forum Activity -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-magic font-bold text-gray-900">
                    <i class="fas fa-comment-dots mr-2 text-purple-500"></i>
                    Attività Forum Recente
                </h3>
            </div>
            <div class="divide-y divide-gray-200">
                @foreach(\App\Models\ForumTopic::orderBy('updated_at', 'desc')->take(5)->get() as $topic)
                <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">
                                {{ Str::limit($topic->name, 40) }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-user mr-1"></i>
                                {{ $topic->user->username }}
                                <span class="mx-2">•</span>
                                <i class="fas fa-eye mr-1"></i>
                                {{ $topic->click }} visualizzazioni
                            </p>
                        </div>
                        <div class="ml-4 flex-shrink-0">
                            <p class="text-xs text-gray-500">
                                {{ $topic->updated_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-magic font-bold text-gray-900">
                <i class="fas fa-bolt mr-2 text-yellow-500"></i>
                Azioni Rapide
            </h3>
        </div>
        <div class="px-6 py-6">
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
                <a href="{{ route('admin.user.create') }}"
                   class="flex flex-col items-center justify-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors group">
                    <i class="fas fa-user-plus text-3xl text-blue-600 mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="text-xs text-center text-blue-900 font-medium">Nuovo Utente</span>
                </a>

                <a href="{{ route('admin.objects.create') }}"
                   class="flex flex-col items-center justify-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors group">
                    <i class="fas fa-box text-3xl text-green-600 mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="text-xs text-center text-green-900 font-medium">Nuovo Oggetto</span>
                </a>

                <a href="{{ route('admin.creature.create') }}"
                   class="flex flex-col items-center justify-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors group">
                    <i class="fas fa-dragon text-3xl text-purple-600 mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="text-xs text-center text-purple-900 font-medium">Nuova Creatura</span>
                </a>

                <a href="{{ route('admin.quest.create') }}"
                   class="flex flex-col items-center justify-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors group">
                    <i class="fas fa-scroll text-3xl text-yellow-600 mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="text-xs text-center text-yellow-900 font-medium">Nuova Quest</span>
                </a>

                <a href="{{ route('admin.shop.create') }}"
                   class="flex flex-col items-center justify-center p-4 bg-red-50 rounded-lg hover:bg-red-100 transition-colors group">
                    <i class="fas fa-store text-3xl text-red-600 mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="text-xs text-center text-red-900 font-medium">Nuovo Negozio</span>
                </a>

                <a href="{{ route('admin.chat.create') }}"
                   class="flex flex-col items-center justify-center p-4 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors group">
                    <i class="fas fa-map-marked-alt text-3xl text-indigo-600 mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="text-xs text-center text-indigo-900 font-medium">Nuova Mappa</span>
                </a>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    // Auto-refresh stats every 30 seconds
    setInterval(function() {
        // Potresti implementare un refresh AJAX qui
        console.log('Stats could be refreshed here');
    }, 30000);
</script>
@endpush
