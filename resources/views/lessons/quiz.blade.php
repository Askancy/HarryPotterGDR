@extends('front.layouts.app')

@section('title', 'Quiz - ' . $lesson->subject->name)

@section('styles')
<style>
    .quiz-container {
        max-width: 800px;
        margin: 0 auto;
    }
    .question-card {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: white;
    }
    .option-button {
        width: 100%;
        text-align: left;
        padding: 1rem;
        margin-bottom: 0.5rem;
        border: 2px solid #dee2e6;
        background: white;
        transition: all 0.3s;
    }
    .option-button:hover {
        border-color: #007bff;
        background: #e7f3ff;
    }
    .option-button.selected {
        border-color: #28a745;
        background: #d4edda;
    }
</style>
@endsection

@section('content')
<div class="container my-5">
    <div class="quiz-container">
        <div class="text-center mb-4">
            <h2><i class="fas fa-graduation-cap"></i> {{ $lesson->subject->name }}</h2>
            <p class="lead">{{ $lesson->subject->professor_name }} - {{ $lesson->subject->classroom }}</p>
            <p class="text-muted">Rispondi alle domande per completare la lezione</p>
        </div>

        <form action="{{ route('lessons.attend', $lesson->id) }}" method="POST" id="quizForm">
            @csrf

            @foreach($questions as $index => $question)
                <div class="question-card">
                    <h5 class="mb-3">
                        <span class="badge badge-primary">Domanda {{ $index + 1 }}</span>
                    </h5>
                    <h6 class="mb-4">{{ $question['question'] }}</h6>

                    <div class="options">
                        @foreach($question['options'] as $optIndex => $option)
                            <button type="button"
                                    class="btn option-button"
                                    data-question="{{ $index }}"
                                    data-answer="{{ $optIndex }}"
                                    onclick="selectOption(this)">
                                <i class="fas fa-circle-notch option-icon"></i>
                                {{ $option }}
                            </button>
                        @endforeach
                    </div>

                    <input type="hidden" name="answers[{{ $index }}]" id="answer_{{ $index }}" required>
                </div>
            @endforeach

            <div class="text-center">
                <button type="submit" class="btn btn-success btn-lg" id="submitBtn" disabled>
                    <i class="fas fa-check"></i> Completa Lezione
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let selectedAnswers = {};
const totalQuestions = {{ count($questions) }};

function selectOption(button) {
    const questionIndex = button.dataset.question;
    const answer = button.dataset.answer;

    // Deselect all options for this question
    document.querySelectorAll(`[data-question="${questionIndex}"]`).forEach(btn => {
        btn.classList.remove('selected');
        btn.querySelector('.option-icon').className = 'fas fa-circle-notch option-icon';
    });

    // Select this option
    button.classList.add('selected');
    button.querySelector('.option-icon').className = 'fas fa-check-circle option-icon';

    // Store answer
    selectedAnswers[questionIndex] = answer;
    document.getElementById(`answer_${questionIndex}`).value = answer;

    // Check if all questions are answered
    if (Object.keys(selectedAnswers).length === totalQuestions) {
        document.getElementById('submitBtn').disabled = false;
    }
}

// Prevent form submission if not all questions answered
document.getElementById('quizForm').addEventListener('submit', function(e) {
    if (Object.keys(selectedAnswers).length < totalQuestions) {
        e.preventDefault();
        alert('Per favore rispondi a tutte le domande prima di completare la lezione!');
    }
});
</script>
@endsection
