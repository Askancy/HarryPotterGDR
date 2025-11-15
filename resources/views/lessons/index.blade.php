@extends('front.layouts.app')

@section('title', 'Lezioni di Hogwarts')

@section('styles')
<style>
    .lesson-card {
        border-left: 5px solid;
        transition: all 0.3s;
        margin-bottom: 1rem;
    }
    .lesson-card:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .morning { border-color: #f0ad4e; }
    .afternoon { border-color: #5bc0de; }
    .streak-badge {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1rem;
        border-radius: 10px;
    }
    .subject-progress {
        border-left: 4px solid #28a745;
        padding-left: 1rem;
    }
</style>
@endsection

@section('content')
<div class="container my-5">
    <!-- Header with Stats -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="fas fa-graduation-cap"></i> Lezioni di Hogwarts</h2>
            <p class="text-muted">Partecipa alle lezioni per guadagnare esperienza e migliorare le tue abilità!</p>
        </div>
        <div class="col-md-4">
            <div class="streak-badge text-center">
                <h4 class="mb-0">
                    <i class="fas fa-fire"></i> {{ Auth::user()->lesson_streak }} giorni
                </h4>
                <small>Streak Consecutivo</small>
                @if(Auth::user()->lesson_streak >= 7)
                    <div class="mt-2">
                        <span class="badge badge-warning">+1 Punto Talento ogni 7 giorni!</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Today's Lessons -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="fas fa-calendar-day"></i> Lezioni di Oggi</h4>
        </div>
        <div class="card-body">
            @if($todayLessons->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Nessuna lezione programmata per oggi. Goditi il tuo giorno libero!
                </div>
            @else
                @foreach($todayLessons as $lesson)
                    <div class="lesson-card card {{ $lesson->slot }}">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-1 text-center">
                                    <i class="fas {{ $lesson->subject->icon ?? 'fa-book' }} fa-3x"
                                       style="color: {{ $lesson->subject->color }}"></i>
                                </div>
                                <div class="col-md-7">
                                    <h5 class="mb-1">{{ $lesson->subject->name }}</h5>
                                    <p class="mb-1 text-muted small">
                                        <i class="fas fa-user-tie"></i> {{ $lesson->subject->professor_name }}
                                        | <i class="fas fa-door-open"></i> {{ $lesson->subject->classroom }}
                                        | <i class="fas fa-clock"></i> {{ $lesson->slotLabel }}
                                    </p>
                                    <div class="mt-2">
                                        <span class="badge badge-info">
                                            <i class="fas fa-star"></i> {{ $lesson->subject->base_exp }} EXP
                                        </span>
                                        <span class="badge badge-success">
                                            <i class="fas fa-trophy"></i> {{ $lesson->subject->base_house_points }} Punti Casa
                                        </span>
                                        @if($lesson->special_bonus)
                                            <span class="badge badge-warning">
                                                <i class="fas fa-gift"></i> Bonus Speciale!
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-2 text-center">
                                    <small class="text-muted">Partecipanti</small>
                                    <h4 class="mb-0">{{ $lesson->current_participants }}/{{ $lesson->max_participants }}</h4>
                                </div>
                                <div class="col-md-2 text-right">
                                    @if($lesson->hasUserAttended(Auth::user()))
                                        <button class="btn btn-success" disabled>
                                            <i class="fas fa-check"></i> Completata
                                        </button>
                                    @elseif($lesson->isFull())
                                        <button class="btn btn-secondary" disabled>
                                            <i class="fas fa-lock"></i> Al completo
                                        </button>
                                    @else
                                        <a href="{{ route('lessons.quiz', $lesson->id) }}" class="btn btn-primary">
                                            <i class="fas fa-play"></i> Partecipa
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <!-- User Progress -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-line"></i> I Tuoi Progressi</h5>
                </div>
                <div class="card-body">
                    @if($userProgress->isEmpty())
                        <p class="text-muted">Non hai ancora partecipato a nessuna lezione.</p>
                    @else
                        @foreach($userProgress->sortByDesc('level') as $progress)
                            <div class="subject-progress mb-3">
                                <div class="d-flex justify-content-between">
                                    <h6>{{ $progress->subject->name }}</h6>
                                    <span class="badge badge-{{ $progress->gradeColor }}">
                                        {{ $progress->gradeLabel }}
                                    </span>
                                </div>
                                <div class="progress mb-2" style="height: 25px;">
                                    <div class="progress-bar bg-success"
                                         style="width: {{ ($progress->experience / $progress->required_exp) * 100 }}%">
                                        Livello {{ $progress->level }}
                                    </div>
                                </div>
                                <small class="text-muted">
                                    {{ $progress->experience }}/{{ $progress->required_exp }} EXP |
                                    {{ $progress->total_lessons_attended }} lezioni |
                                    {{ $progress->accuracy }}% precisione
                                </small>
                            </div>
                        @endforeach
                    @endif
                    <a href="{{ route('lessons.progress') }}" class="btn btn-sm btn-outline-primary mt-3">
                        Vedi Dettagli <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Upcoming Lessons -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt"></i> Prossime Lezioni</h5>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    @if($upcomingLessons->isEmpty())
                        <p class="text-muted">Nessuna lezione programmata nei prossimi giorni.</p>
                    @else
                        @foreach($upcomingLessons as $date => $lessons)
                            <h6 class="mt-3"><i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</h6>
                            @foreach($lessons as $lesson)
                                <div class="border-left border-primary pl-3 mb-2">
                                    <strong>{{ $lesson->subject->name }}</strong>
                                    <span class="badge badge-sm badge-secondary">{{ $lesson->slotLabel }}</span>
                                    <br>
                                    <small class="text-muted">{{ $lesson->subject->professor_name }}</small>
                                </div>
                            @endforeach
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
