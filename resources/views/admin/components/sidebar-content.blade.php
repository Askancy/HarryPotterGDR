@php
    $user = Auth::user();
@endphp

<!-- Logo -->
<div class="flex items-center flex-shrink-0 px-4">
    <div class="text-white w-full">
        <h1 class="text-2xl font-magic font-bold text-center">
            <i class="fas fa-hat-wizard mr-2"></i>
            Hogwarts GDR
        </h1>
        <p class="text-center text-xs text-gray-300 mt-1">Pannello Amministrazione</p>
    </div>
</div>

<!-- User info -->
<div class="mt-5 px-4">
    <div class="flex items-center space-x-3 p-3 rounded-lg bg-white bg-opacity-10 backdrop-blur">
        <img class="h-12 w-12 rounded-full object-cover border-2 border-white"
             src="{{ url('upload/user/'.$user->avatar()) }}"
             alt="{{ $user->username }}">
        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-white truncate">
                {{ $user->username }}
            </p>
            <p class="text-xs text-gray-300">
                @if($user->group == 2)
                    <i class="fas fa-crown mr-1"></i> Amministratore
                @elseif($user->group == 1)
                    <i class="fas fa-shield-alt mr-1"></i> Moderatore
                @endif
            </p>
        </div>
    </div>
</div>

<!-- Navigation -->
<nav class="mt-5 flex-1 px-2 space-y-1">

    <!-- Dashboard -->
    <a href="{{ route('admin.index') }}"
       class="{{ request()->routeIs('admin.index') ? 'bg-white bg-opacity-20 text-white' : 'text-gray-300 hover:bg-white hover:bg-opacity-10 hover:text-white' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors">
        <i class="fas fa-tachometer-alt mr-3 text-lg w-6"></i>
        Dashboard
    </a>

    <!-- Gestione Utenti -->
    <div x-data="{ open: {{ request()->is('admin/user*') ? 'true' : 'false' }} }">
        <button @click="open = !open"
                class="{{ request()->is('admin/user*') ? 'bg-white bg-opacity-20 text-white' : 'text-gray-300 hover:bg-white hover:bg-opacity-10 hover:text-white' }} group w-full flex items-center justify-between px-3 py-2 text-sm font-medium rounded-md transition-colors">
            <div class="flex items-center">
                <i class="fas fa-users mr-3 text-lg w-6"></i>
                Utenti
            </div>
            <i class="fas fa-chevron-down transition-transform" :class="open ? 'rotate-180' : ''"></i>
        </button>
        <div x-show="open" x-cloak class="ml-8 mt-1 space-y-1">
            <a href="{{ route('admin.user.index') }}"
               class="{{ request()->routeIs('admin.user.index') ? 'text-white bg-opacity-10' : 'text-gray-400 hover:text-white' }} block px-3 py-2 text-sm rounded-md">
                <i class="fas fa-list mr-2"></i> Tutti gli utenti
            </a>
            <a href="{{ route('admin.user.create') }}"
               class="{{ request()->routeIs('admin.user.create') ? 'text-white bg-opacity-10' : 'text-gray-400 hover:text-white' }} block px-3 py-2 text-sm rounded-md">
                <i class="fas fa-user-plus mr-2"></i> Nuovo utente
            </a>
        </div>
    </div>

    <!-- Gestione Casate -->
    <div x-data="{ open: {{ request()->is('admin/team*') || request()->is('admin/point*') || request()->is('admin/house-points*') ? 'true' : 'false' }} }">
        <button @click="open = !open"
                class="{{ request()->is('admin/team*') || request()->is('admin/point*') || request()->is('admin/house-points*') ? 'bg-white bg-opacity-20 text-white' : 'text-gray-300 hover:bg-white hover:bg-opacity-10 hover:text-white' }} group w-full flex items-center justify-between px-3 py-2 text-sm font-medium rounded-md transition-colors">
            <div class="flex items-center">
                <i class="fas fa-flag mr-3 text-lg w-6"></i>
                Casate
            </div>
            <i class="fas fa-chevron-down transition-transform" :class="open ? 'rotate-180' : ''"></i>
        </button>
        <div x-show="open" x-cloak class="ml-8 mt-1 space-y-1">
            <a href="{{ route('admin.house-points') }}"
               class="{{ request()->routeIs('admin.house-points*') ? 'text-white bg-opacity-10' : 'text-gray-400 hover:text-white' }} block px-3 py-2 text-sm rounded-md">
                <i class="fas fa-trophy mr-2"></i> Punti Case
            </a>
            <a href="{{ route('admin.point.index') }}"
               class="{{ request()->routeIs('admin.point.index') ? 'text-white bg-opacity-10' : 'text-gray-400 hover:text-white' }} block px-3 py-2 text-sm rounded-md">
                <i class="fas fa-star mr-2"></i> Gestione punti (Legacy)
            </a>
        </div>
    </div>

    <!-- Gestione Oggetti e Negozi -->
    <div x-data="{ open: {{ request()->is('admin/objects*') || request()->is('admin/shop*') ? 'true' : 'false' }} }">
        <button @click="open = !open"
                class="{{ request()->is('admin/objects*') || request()->is('admin/shop*') ? 'bg-white bg-opacity-20 text-white' : 'text-gray-300 hover:bg-white hover:bg-opacity-10 hover:text-white' }} group w-full flex items-center justify-between px-3 py-2 text-sm font-medium rounded-md transition-colors">
            <div class="flex items-center">
                <i class="fas fa-shopping-bag mr-3 text-lg w-6"></i>
                Economia
            </div>
            <i class="fas fa-chevron-down transition-transform" :class="open ? 'rotate-180' : ''"></i>
        </button>
        <div x-show="open" x-cloak class="ml-8 mt-1 space-y-1">
            <a href="{{ route('admin.objects.index') }}"
               class="{{ request()->routeIs('admin.objects.*') ? 'text-white bg-opacity-10' : 'text-gray-400 hover:text-white' }} block px-3 py-2 text-sm rounded-md">
                <i class="fas fa-box mr-2"></i> Oggetti
            </a>
            <a href="{{ route('admin.shop.index') }}"
               class="{{ request()->routeIs('admin.shop.*') ? 'text-white bg-opacity-10' : 'text-gray-400 hover:text-white' }} block px-3 py-2 text-sm rounded-md">
                <i class="fas fa-store mr-2"></i> Negozi
            </a>
        </div>
    </div>

    <!-- Gestione Creature -->
    <div x-data="{ open: {{ request()->is('admin/creature*') || request()->is('admin/genre*') ? 'true' : 'false' }} }">
        <button @click="open = !open"
                class="{{ request()->is('admin/creature*') || request()->is('admin/genre*') ? 'bg-white bg-opacity-20 text-white' : 'text-gray-300 hover:bg-white hover:bg-opacity-10 hover:text-white' }} group w-full flex items-center justify-between px-3 py-2 text-sm font-medium rounded-md transition-colors">
            <div class="flex items-center">
                <i class="fas fa-dragon mr-3 text-lg w-6"></i>
                Bestiario
            </div>
            <i class="fas fa-chevron-down transition-transform" :class="open ? 'rotate-180' : ''"></i>
        </button>
        <div x-show="open" x-cloak class="ml-8 mt-1 space-y-1">
            <a href="{{ route('admin.creature.index') }}"
               class="{{ request()->routeIs('admin.creature.*') ? 'text-white bg-opacity-10' : 'text-gray-400 hover:text-white' }} block px-3 py-2 text-sm rounded-md">
                <i class="fas fa-paw mr-2"></i> Creature
            </a>
            <a href="{{ route('admin.genre.index') }}"
               class="{{ request()->routeIs('admin.genre.*') ? 'text-white bg-opacity-10' : 'text-gray-400 hover:text-white' }} block px-3 py-2 text-sm rounded-md">
                <i class="fas fa-tags mr-2"></i> Categorie
            </a>
        </div>
    </div>

    <!-- Gestione Quest -->
    <a href="{{ route('admin.quest.index') }}"
       class="{{ request()->routeIs('admin.quest.*') ? 'bg-white bg-opacity-20 text-white' : 'text-gray-300 hover:bg-white hover:bg-opacity-10 hover:text-white' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors">
        <i class="fas fa-scroll mr-3 text-lg w-6"></i>
        Quest
    </a>

    <!-- Gestione Mappe/Chat -->
    <a href="{{ route('admin.chat.index') }}"
       class="{{ request()->routeIs('admin.chat.*') ? 'bg-white bg-opacity-20 text-white' : 'text-gray-300 hover:bg-white hover:bg-opacity-10 hover:text-white' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors">
        <i class="fas fa-map-marked-alt mr-3 text-lg w-6"></i>
        Mappe
    </a>

    <!-- Gestione Forum -->
    <a href="{{ route('admin.forum.index') }}"
       class="{{ request()->routeIs('admin.forum.*') ? 'bg-white bg-opacity-20 text-white' : 'text-gray-300 hover:bg-white hover:bg-opacity-10 hover:text-white' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors">
        <i class="fas fa-comments mr-3 text-lg w-6"></i>
        Forum
    </a>

    <!-- Divider -->
    <div class="border-t border-gray-600 my-4"></div>

    <!-- Statistiche -->
    <a href="#"
       class="text-gray-300 hover:bg-white hover:bg-opacity-10 hover:text-white group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors">
        <i class="fas fa-chart-line mr-3 text-lg w-6"></i>
        Statistiche
    </a>

    <!-- Impostazioni -->
    <a href="#"
       class="text-gray-300 hover:bg-white hover:bg-opacity-10 hover:text-white group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors">
        <i class="fas fa-cog mr-3 text-lg w-6"></i>
        Impostazioni
    </a>

    <!-- Logs -->
    <a href="#"
       class="text-gray-300 hover:bg-white hover:bg-opacity-10 hover:text-white group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors">
        <i class="fas fa-history mr-3 text-lg w-6"></i>
        Log attività
    </a>
</nav>

<!-- Footer -->
<div class="flex-shrink-0 flex border-t border-gray-600 p-4">
    <div class="w-full text-center">
        <p class="text-xs text-gray-400">
            <i class="fas fa-magic mr-1"></i>
            Powered by Magic
        </p>
        <p class="text-xs text-gray-500 mt-1">
            v1.0.0
        </p>
    </div>
</div>
