@extends('admin.layouts.modern')

@section('page-title', 'Moderazione Forum')

@section('content')
<div x-data="{ activeTab: 'topics', search: '' }">

    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-2xl font-magic font-bold text-gray-900">Moderazione Forum</h2>
        <p class="mt-1 text-sm text-gray-500">Gestisci topic, post e segnalazioni</p>
    </div>

    <!-- Tabs -->
    <div class="mb-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
                <button @click="activeTab = 'topics'"
                        :class="activeTab === 'topics' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    <i class="fas fa-list mr-2"></i>
                    Topic Recenti
                </button>
                <button @click="activeTab = 'reports'"
                        :class="activeTab === 'reports' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    <i class="fas fa-flag mr-2"></i>
                    Segnalazioni
                    @php
                        $reportCount = \App\Models\ForumReport::where('status', 0)->count();
                    @endphp
                    @if($reportCount > 0)
                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        {{ $reportCount }}
                    </span>
                    @endif
                </button>
                <button @click="activeTab = 'stats'"
                        :class="activeTab === 'stats' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    <i class="fas fa-chart-bar mr-2"></i>
                    Statistiche
                </button>
            </nav>
        </div>
    </div>

    <!-- Topics Tab -->
    <div x-show="activeTab === 'topics'" x-cloak>
        <!-- Search -->
        <div class="mb-6 bg-white shadow-md rounded-lg p-4">
            <input type="text"
                   x-model="search"
                   placeholder="Cerca topic..."
                   class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
        </div>

        <!-- Topics List -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Topic
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Sezione
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Autore
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Statistiche
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Stato
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Azioni
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($topics as $topic)
                        <tr class="hover:bg-gray-50 transition-colors"
                            x-show="search === '' || '{{ strtolower($topic->name) }}'.includes(search.toLowerCase())">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($topic->important == 1)
                                    <i class="fas fa-thumbtack text-yellow-500 mr-2"></i>
                                    @endif
                                    @if($topic->lock == 1)
                                    <i class="fas fa-lock text-gray-500 mr-2"></i>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ Str::limit($topic->name, 50) }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $topic->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $section = \App\Models\ForumSection::find($topic->id_section);
                                @endphp
                                @if($section)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $section->name }}
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <img class="h-8 w-8 rounded-full object-cover mr-2"
                                         src="{{ url('upload/user/'.$topic->user->avatar()) }}"
                                         alt="{{ $topic->user->username }}">
                                    <span class="text-sm text-gray-900">{{ $topic->user->username }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex items-center space-x-3">
                                    <span title="Visualizzazioni">
                                        <i class="fas fa-eye text-gray-400 mr-1"></i>
                                        {{ $topic->click }}
                                    </span>
                                    <span title="Risposte">
                                        <i class="fas fa-comments text-gray-400 mr-1"></i>
                                        {{ \App\Models\ForumPost::where('id_topic', $topic->id)->count() }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col space-y-1">
                                    @if($topic->important == 1)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-star mr-1"></i> In evidenza
                                    </span>
                                    @endif
                                    @if($topic->lock == 1)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-lock mr-1"></i> Bloccato
                                    </span>
                                    @endif
                                    @if($topic->delete == 1)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        <i class="fas fa-trash mr-1"></i> Eliminato
                                    </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('forum.topic', $topic->id) }}"
                                       target="_blank"
                                       class="text-blue-600 hover:text-blue-900"
                                       title="Visualizza">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>

                                    @if($topic->important == 0)
                                    <form action="{{ route('admin.forum.pin', $topic->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="text-yellow-600 hover:text-yellow-900"
                                                title="Fissa in alto">
                                            <i class="fas fa-thumbtack"></i>
                                        </button>
                                    </form>
                                    @else
                                    <form action="{{ route('admin.forum.unpin', $topic->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="text-gray-600 hover:text-gray-900"
                                                title="Rimuovi fissaggio">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                    @endif

                                    @if($topic->lock == 0)
                                    <form action="{{ route('admin.forum.lock', $topic->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="text-red-600 hover:text-red-900"
                                                title="Blocca topic">
                                            <i class="fas fa-lock"></i>
                                        </button>
                                    </form>
                                    @else
                                    <form action="{{ route('admin.forum.unlock', $topic->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="text-green-600 hover:text-green-900"
                                                title="Sblocca topic">
                                            <i class="fas fa-unlock"></i>
                                        </button>
                                    </form>
                                    @endif

                                    <form action="{{ route('admin.forum.delete', $topic->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                onclick="return confirm('Eliminare questo topic?')"
                                                class="text-red-600 hover:text-red-900"
                                                title="Elimina">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <i class="fas fa-comments text-4xl mb-4"></i>
                                <p class="text-lg font-medium">Nessun topic trovato</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Reports Tab -->
    <div x-show="activeTab === 'reports'" x-cloak>
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200">
                <h3 class="text-lg font-magic font-bold text-gray-900">
                    <i class="fas fa-exclamation-triangle mr-2 text-red-500"></i>
                    Segnalazioni Pendenti
                </h3>
            </div>

            <div class="divide-y divide-gray-200">
                @forelse($reports as $report)
                <div class="px-6 py-5 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
                                <img class="h-8 w-8 rounded-full object-cover mr-2"
                                     src="{{ url('upload/user/'.$report->user->avatar()) }}"
                                     alt="{{ $report->user->username }}">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $report->user->username }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $report->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>

                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-3">
                                <p class="text-sm text-gray-700">
                                    {{ $report->content }}
                                </p>
                            </div>

                            @php
                                $topic = \App\Models\ForumTopic::find($report->id_topic);
                            @endphp
                            @if($topic)
                            <p class="text-sm text-gray-500">
                                <i class="fas fa-link mr-1"></i>
                                Topic segnalato:
                                <a href="{{ route('forum.topic', $topic->id) }}"
                                   target="_blank"
                                   class="text-indigo-600 hover:text-indigo-800 font-medium">
                                    {{ $topic->name }}
                                </a>
                            </p>
                            @endif
                        </div>

                        <div class="ml-4 flex flex-col space-y-2">
                            <form action="{{ route('admin.forum.report.resolve', $report->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                    <i class="fas fa-check mr-1"></i>
                                    Risolvi
                                </button>
                            </form>

                            <form action="{{ route('admin.forum.report.delete', $report->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('Eliminare questa segnalazione?')"
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    <i class="fas fa-times mr-1"></i>
                                    Ignora
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center text-gray-400">
                    <i class="fas fa-check-circle text-4xl mb-4 text-green-400"></i>
                    <p class="text-lg font-medium text-gray-700">Nessuna segnalazione pendente</p>
                    <p class="text-sm text-gray-500 mt-1">Ottimo lavoro!</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Stats Tab -->
    <div x-show="activeTab === 'stats'" x-cloak>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <div class="bg-white shadow-lg rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                        <i class="fas fa-comments text-2xl text-white"></i>
                    </div>
                    <div class="ml-5">
                        <p class="text-sm font-medium text-gray-500">Topic Totali</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ \App\Models\ForumTopic::count() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-lg rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                        <i class="fas fa-comment text-2xl text-white"></i>
                    </div>
                    <div class="ml-5">
                        <p class="text-sm font-medium text-gray-500">Post Totali</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ \App\Models\ForumPost::count() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-lg rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                        <i class="fas fa-thumbtack text-2xl text-white"></i>
                    </div>
                    <div class="ml-5">
                        <p class="text-sm font-medium text-gray-500">Topic Fissati</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ \App\Models\ForumTopic::where('important', 1)->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-lg rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                        <i class="fas fa-flag text-2xl text-white"></i>
                    </div>
                    <div class="ml-5">
                        <p class="text-sm font-medium text-gray-500">Segnalazioni</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ \App\Models\ForumReport::where('status', 0)->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
