@extends('front.layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-900 via-indigo-900 to-blue-900 py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-5xl font-magic font-bold text-white mb-4">
                <i class="fas fa-scroll-old mr-3"></i>
                Generatore di Quest
            </h1>
            <p class="text-xl text-indigo-200">
                Crea avventure personalizzate basate sul tuo livello e abilità
            </p>
        </div>

        <!-- Main Generator Card -->
        <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-2xl shadow-2xl overflow-hidden mb-8">
            <div class="p-8">

                <!-- Info Banner -->
                <div class="bg-yellow-500 bg-opacity-20 border border-yellow-400 rounded-lg p-6 mb-8">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-yellow-400 text-2xl mr-4 mt-1"></i>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-yellow-100 mb-2">Come Funziona</h3>
                            <p class="text-yellow-200 text-sm">
                                Il generatore crea quest uniche basate sul tuo livello, casata e progressione.
                                Ogni quest è personalizzata e offre ricompense adeguate alla tua esperienza.
                                Puoi generare fino a <strong>3 quest al giorno</strong>.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- User Stats -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-white bg-opacity-10 rounded-lg p-4 text-center">
                        <i class="fas fa-star text-3xl text-yellow-400 mb-2"></i>
                        <p class="text-sm text-gray-300">Livello</p>
                        <p class="text-2xl font-bold text-white">{{ Auth::user()->level ?? 1 }}</p>
                    </div>

                    <div class="bg-white bg-opacity-10 rounded-lg p-4 text-center">
                        <i class="fas fa-flag text-3xl text-blue-400 mb-2"></i>
                        <p class="text-sm text-gray-300">Casata</p>
                        @php
                            $house = \App\Models\Team::find(Auth::user()->team);
                        @endphp
                        <p class="text-xl font-bold text-white">{{ $house->name ?? 'Nessuna' }}</p>
                    </div>

                    <div class="bg-white bg-opacity-10 rounded-lg p-4 text-center">
                        <i class="fas fa-scroll text-3xl text-purple-400 mb-2"></i>
                        <p class="text-sm text-gray-300">Quest Completate</p>
                        @php
                            $completedQuests = \App\Models\Pivot_Quest::where('id_user', Auth::id())->where('status', 1)->count();
                        @endphp
                        <p class="text-2xl font-bold text-white">{{ $completedQuests }}</p>
                    </div>

                    <div class="bg-white bg-opacity-10 rounded-lg p-4 text-center">
                        <i class="fas fa-clock text-3xl text-green-400 mb-2"></i>
                        <p class="text-sm text-gray-300">Quest Oggi</p>
                        @php
                            $todayQuests = \App\Models\Quest::where('created_by', Auth::id())->whereDate('created_at', today())->count();
                        @endphp
                        <p class="text-2xl font-bold text-white">{{ $todayQuests }} / 3</p>
                    </div>
                </div>

                <!-- Quest Types Preview -->
                <div class="mb-8">
                    <h3 class="text-2xl font-magic font-bold text-white mb-6 text-center">
                        Tipologie di Quest Disponibili
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Collection -->
                        <div class="bg-gradient-to-br from-green-500 to-green-700 rounded-lg p-6 transform hover:scale-105 transition-transform duration-200">
                            <div class="flex items-start">
                                <i class="fas fa-boxes text-4xl text-white opacity-80 mr-4"></i>
                                <div>
                                    <h4 class="text-lg font-bold text-white mb-2">Raccolta</h4>
                                    <p class="text-sm text-green-100">
                                        Raccogli ingredienti e oggetti magici per i professori
                                    </p>
                                    <div class="mt-3 flex items-center text-yellow-300">
                                        <i class="fas fa-star"></i>
                                        <span class="text-xs ml-1">Difficoltà: Media</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Combat -->
                        <div class="bg-gradient-to-br from-red-500 to-red-700 rounded-lg p-6 transform hover:scale-105 transition-transform duration-200">
                            <div class="flex items-start">
                                <i class="fas fa-dragon text-4xl text-white opacity-80 mr-4"></i>
                                <div>
                                    <h4 class="text-lg font-bold text-white mb-2">Combattimento</h4>
                                    <p class="text-sm text-red-100">
                                        Affronta creature magiche pericolose
                                    </p>
                                    <div class="mt-3 flex items-center text-yellow-300">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <span class="text-xs ml-1">Difficoltà: Alta</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Exploration -->
                        <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-lg p-6 transform hover:scale-105 transition-transform duration-200">
                            <div class="flex items-start">
                                <i class="fas fa-map-marked-alt text-4xl text-white opacity-80 mr-4"></i>
                                <div>
                                    <h4 class="text-lg font-bold text-white mb-2">Esplorazione</h4>
                                    <p class="text-sm text-blue-100">
                                        Scopri luoghi segreti e misteri nascosti
                                    </p>
                                    <div class="mt-3 flex items-center text-yellow-300">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <span class="text-xs ml-1">Difficoltà: Media</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Delivery -->
                        <div class="bg-gradient-to-br from-yellow-500 to-yellow-700 rounded-lg p-6 transform hover:scale-105 transition-transform duration-200">
                            <div class="flex items-start">
                                <i class="fas fa-shipping-fast text-4xl text-white opacity-80 mr-4"></i>
                                <div>
                                    <h4 class="text-lg font-bold text-white mb-2">Consegna</h4>
                                    <p class="text-sm text-yellow-100">
                                        Consegna pacchi e messaggi importanti
                                    </p>
                                    <div class="mt-3 flex items-center text-yellow-300">
                                        <i class="fas fa-star"></i>
                                        <span class="text-xs ml-1">Difficoltà: Bassa</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Investigation -->
                        <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-lg p-6 transform hover:scale-105 transition-transform duration-200">
                            <div class="flex items-start">
                                <i class="fas fa-search text-4xl text-white opacity-80 mr-4"></i>
                                <div>
                                    <h4 class="text-lg font-bold text-white mb-2">Investigazione</h4>
                                    <p class="text-sm text-purple-100">
                                        Risolvi misteri e indaga su eventi strani
                                    </p>
                                    <div class="mt-3 flex items-center text-yellow-300">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <span class="text-xs ml-1">Difficoltà: Media</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- House Pride -->
                        <div class="bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-lg p-6 transform hover:scale-105 transition-transform duration-200">
                            <div class="flex items-start">
                                <i class="fas fa-trophy text-4xl text-white opacity-80 mr-4"></i>
                                <div>
                                    <h4 class="text-lg font-bold text-white mb-2">Onore della Casata</h4>
                                    <p class="text-sm text-indigo-100">
                                        Porta gloria alla tua casata
                                    </p>
                                    <div class="mt-3 flex items-center text-yellow-300">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <span class="text-xs ml-1">Difficoltà: Alta</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Generate Button -->
                <div class="text-center">
                    @if($todayQuests < 3)
                        <form action="{{ route('quest-generator.generate') }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-yellow-400 to-yellow-600 hover:from-yellow-500 hover:to-yellow-700 text-gray-900 font-bold text-lg rounded-full shadow-2xl transform hover:scale-105 transition-all duration-200">
                                <i class="fas fa-magic mr-3 text-2xl"></i>
                                Genera Nuova Quest
                                <i class="fas fa-sparkles ml-3 text-2xl"></i>
                            </button>
                        </form>
                        <p class="text-sm text-gray-300 mt-4">
                            <i class="fas fa-info-circle mr-1"></i>
                            Ti rimangono {{ 3 - $todayQuests }} quest da generare oggi
                        </p>
                    @else
                        <div class="bg-red-500 bg-opacity-20 border border-red-400 rounded-lg p-6">
                            <i class="fas fa-exclamation-triangle text-red-400 text-4xl mb-3"></i>
                            <p class="text-lg font-bold text-red-200">Limite Giornaliero Raggiunto</p>
                            <p class="text-sm text-red-300 mt-2">
                                Hai già generato 3 quest oggi. Torna domani per crearne altre!
                            </p>
                        </div>
                    @endif
                </div>

            </div>
        </div>

        <!-- Recent Generated Quests -->
        @php
            $recentQuests = \App\Models\Quest::where('created_by', Auth::id())
                ->latest()
                ->take(5)
                ->get();
        @endphp

        @if($recentQuests->count() > 0)
        <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-2xl shadow-2xl overflow-hidden">
            <div class="px-8 py-6 border-b border-white border-opacity-20">
                <h2 class="text-2xl font-magic font-bold text-white">
                    <i class="fas fa-history mr-2"></i>
                    Le Tue Quest Recenti
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($recentQuests as $quest)
                    <a href="{{ route('quest.show', $quest->id) }}"
                       class="block bg-white bg-opacity-10 rounded-lg p-5 hover:bg-opacity-20 transition-all duration-200 transform hover:scale-105">
                        <div class="flex items-start justify-between mb-3">
                            <h3 class="text-lg font-bold text-white flex-1">
                                {{ Str::limit($quest->name, 40) }}
                            </h3>
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $quest->difficulty)
                                    <i class="fas fa-star text-yellow-400 text-xs"></i>
                                @else
                                    <i class="far fa-star text-gray-500 text-xs"></i>
                                @endif
                            @endfor
                        </div>

                        <p class="text-sm text-gray-300 mb-4 line-clamp-2">
                            {{ Str::limit($quest->description, 100) }}
                        </p>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3 text-xs">
                                <span class="text-blue-300">
                                    <i class="fas fa-star mr-1"></i>
                                    {{ $quest->exp_reward }} XP
                                </span>
                                <span class="text-yellow-300">
                                    <i class="fas fa-coins mr-1"></i>
                                    {{ $quest->money_reward }} G
                                </span>
                            </div>
                            <span class="text-xs text-gray-400">
                                {{ $quest->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
