@extends('front.layouts.app')

@section('title', 'Progressi Accademici')

@section('content')
<div class="container my-5">
    <h2 class="mb-4"><i class="fas fa-chart-line"></i> I Tuoi Progressi Accademici</h2>

    <!-- Overall Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3>{{ Auth::user()->total_lessons_attended }}</h3>
                    <p class="text-muted mb-0">Lezioni Completate</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3>{{ Auth::user()->lesson_streak }}</h3>
                    <p class="text-muted mb-0">Giorni Consecutivi</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3>{{ number_format(Auth::user()->academic_average, 1) }}%</h3>
                    <p class="text-muted mb-0">Media Generale</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3>{{ $progress->count() }}</h3>
                    <p class="text-muted mb-0">Materie Studiate</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Subject Progress -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-book"></i> Progressi per Materia</h5>
        </div>
        <div class="card-body">
            @if($progress->isEmpty())
                <p class="text-muted">Non hai ancora partecipato a nessuna lezione.</p>
            @else
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Materia</th>
                                <th>Livello</th>
                                <th>Esperienza</th>
                                <th>Voto</th>
                                <th>Lezioni</th>
                                <th>Precisione</th>
                                <th>Ultima Lezione</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($progress->sortByDesc('level') as $subj)
                                <tr>
                                    <td><strong>{{ $subj->subject->name }}</strong></td>
                                    <td>
                                        <span class="badge badge-info">Lv. {{ $subj->level }}</span>
                                    </td>
                                    <td>
                                        <div class="progress" style="width: 150px; height: 20px;">
                                            <div class="progress-bar"
                                                 style="width: {{ ($subj->experience / $subj->required_exp) * 100 }}%">
                                                {{ $subj->experience }}/{{ $subj->required_exp }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $subj->gradeColor }}">
                                            {{ $subj->gradeLabel }}
                                        </span>
                                    </td>
                                    <td>{{ $subj->total_lessons_attended }}</td>
                                    <td>{{ $subj->accuracy }}%</td>
                                    <td>{{ $subj->last_attended ? $subj->last_attended->diffForHumans() : 'Mai' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Attendances -->
    <div class="card">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-history"></i> Storico Lezioni</h5>
        </div>
        <div class="card-body">
            @if($attendances->isEmpty())
                <p class="text-muted">Nessuna lezione completata ancora.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Materia</th>
                                <th>Performance</th>
                                <th>EXP Guadagnati</th>
                                <th>Punti Casa</th>
                                <th>Risposte Corrette</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendances as $att)
                                <tr>
                                    <td>{{ $att->attended_at->format('d/m/Y H:i') }}</td>
                                    <td><strong>{{ $att->subject->name }}</strong></td>
                                    <td>
                                        <span class="badge badge-{{ $att->performanceColor }}">
                                            {{ $att->performanceLabel }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-success">
                                            <i class="fas fa-star"></i> +{{ $att->exp_earned }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-warning">
                                            <i class="fas fa-trophy"></i> +{{ $att->house_points_earned }}
                                        </span>
                                    </td>
                                    <td>{{ $att->correct_answers }}/{{ $att->questions_answered }} ({{ $att->accuracy }}%)</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $attendances->links() }}
            @endif
        </div>
    </div>
</div>
@endsection
