<?php

namespace App\Http\Controllers;

use App\Models\DailyLesson;
use App\Models\Subject;
use App\Models\LessonAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LessonController extends Controller
{
    /**
     * Show lessons calendar.
     */
    public function index()
    {
        $todayLessons = DailyLesson::with('subject')
            ->today()
            ->orderBy('slot')
            ->get();

        $upcomingLessons = DailyLesson::with('subject')
            ->where('date', '>', Carbon::today())
            ->where('date', '<=', Carbon::today()->addDays(7))
            ->orderBy('date')
            ->orderBy('slot')
            ->get()
            ->groupBy(function($lesson) {
                return $lesson->date->format('Y-m-d');
            });

        $user = Auth::user();
        $userProgress = $user->subjectProgress()->with('subject')->get();

        return view('lessons.index', compact('todayLessons', 'upcomingLessons', 'userProgress'));
    }

    /**
     * Show specific lesson details.
     */
    public function show($id)
    {
        $lesson = DailyLesson::with(['subject', 'attendances.user'])->findOrFail($id);
        $user = Auth::user();

        $hasAttended = $lesson->hasUserAttended($user);
        $canAttend = !$hasAttended && $lesson->isAvailable() && !$lesson->isFull();

        $userProgress = $user->getSubjectProgress($lesson->subject);

        return view('lessons.show', compact('lesson', 'hasAttended', 'canAttend', 'userProgress'));
    }

    /**
     * Attend a lesson (with quiz).
     */
    public function attend(Request $request, $id)
    {
        $lesson = DailyLesson::with('subject')->findOrFail($id);
        $user = Auth::user();

        // Validate answers
        $validated = $request->validate([
            'answers' => 'required|array|min:5|max:10',
            'answers.*' => 'required|string',
        ]);

        // Check if can attend
        if ($lesson->hasUserAttended($user)) {
            return redirect()->back()->with('error', 'Hai già partecipato a questa lezione!');
        }

        if (!$lesson->isAvailable()) {
            return redirect()->back()->with('error', 'Questa lezione non è più disponibile!');
        }

        if ($lesson->isFull()) {
            return redirect()->back()->with('error', 'Questa lezione è al completo!');
        }

        // Generate quiz questions based on subject
        $questions = $this->generateQuiz($lesson->subject);

        // Evaluate answers
        $correctAnswers = 0;
        foreach ($validated['answers'] as $index => $answer) {
            if (isset($questions[$index]) && $questions[$index]['correct'] == $answer) {
                $correctAnswers++;
            }
        }

        $totalQuestions = count($validated['answers']);
        $accuracy = ($correctAnswers / $totalQuestions) * 100;

        // Determine performance
        $performance = match(true) {
            $accuracy < 40 => 'poor',
            $accuracy < 60 => 'acceptable',
            $accuracy < 75 => 'good',
            $accuracy < 90 => 'excellent',
            default => 'outstanding'
        };

        // Attend lesson
        $attendance = $user->attendLesson($lesson, $performance, $correctAnswers, $totalQuestions);

        if ($attendance) {
            return redirect()->route('lessons.index')->with('message', "Lezione completata! Performance: {$attendance->performanceLabel}. Hai guadagnato {$attendance->exp_earned} EXP e {$attendance->house_points_earned} punti casa!");
        }

        return redirect()->back()->with('error', 'Errore durante la partecipazione alla lezione.');
    }

    /**
     * Show quiz for a lesson.
     */
    public function quiz($id)
    {
        $lesson = DailyLesson::with('subject')->findOrFail($id);
        $user = Auth::user();

        if ($lesson->hasUserAttended($user)) {
            return redirect()->route('lessons.show', $id)->with('error', 'Hai già completato questa lezione!');
        }

        if (!$lesson->isAvailable()) {
            return redirect()->route('lessons.index')->with('error', 'Questa lezione non è disponibile!');
        }

        // Generate quiz
        $questions = $this->generateQuiz($lesson->subject);

        return view('lessons.quiz', compact('lesson', 'questions'));
    }

    /**
     * Generate quiz questions for a subject.
     */
    protected function generateQuiz(Subject $subject)
    {
        // This is a simple implementation. You can expand this with a database of questions
        $quizData = [
            'pozioni' => [
                ['question' => 'Quale ingrediente è essenziale per la Pozione Polisucco?', 'options' => ['Lacrime di fenice', 'Corno di bicorno', 'Sangue di drago', 'Radice di mandragola'], 'correct' => 1],
                ['question' => 'Qual è l\'effetto della Pozione Felix Felicis?', 'options' => ['Invisibilità', 'Fortuna', 'Volo', 'Forza'], 'correct' => 1],
                ['question' => 'Chi è il maestro delle Pozioni a Hogwarts?', 'options' => ['Piton', 'Lumacorno', 'Silente', 'McGranitt'], 'correct' => 0],
                ['question' => 'La Veritaserum è una pozione che...', 'options' => ['Guarisce', 'Fa dire la verità', 'Trasforma', 'Addormenta'], 'correct' => 1],
                ['question' => 'Quale pozione permette di respirare sott\'acqua?', 'options' => ['Branchiospina', 'Polisucco', 'Amortensia', 'Felix Felicis'], 'correct' => 0],
            ],
            'difesa-contro-le-arti-oscure' => [
                ['question' => 'Qual è l\'incantesimo per scacciare i Mollicci?', 'options' => ['Expecto Patronum', 'Riddikulus', 'Expelliarmus', 'Stupefy'], 'correct' => 1],
                ['question' => 'Un Patronus serve per difendersi da...', 'options' => ['Vampiri', 'Dissennatori', 'Lupi mannari', 'Troll'], 'correct' => 1],
                ['question' => 'L\'incantesimo Expelliarmus serve a...', 'options' => ['Disarmare', 'Stordire', 'Proteggere', 'Attaccare'], 'correct' => 0],
                ['question' => 'Quale creatura teme il basilisco?', 'options' => ['Grifone', 'Ragno', 'Drago', 'Fenice'], 'correct' => 1],
                ['question' => 'Protego è un incantesimo di...', 'options' => ['Attacco', 'Protezione', 'Trasformazione', 'Illusione'], 'correct' => 1],
            ],
            // Aggiungi altri subject...
        ];

        $subjectKey = $subject->slug;
        $questions = $quizData[$subjectKey] ?? $quizData['pozioni']; // Default a pozioni

        // Shuffle and return 5 random questions
        shuffle($questions);
        return array_slice($questions, 0, 5);
    }

    /**
     * Show user's academic progress.
     */
    public function progress()
    {
        $user = Auth::user();
        $progress = $user->subjectProgress()->with('subject')->get();
        $attendances = $user->lessonAttendances()
            ->with(['subject', 'dailyLesson'])
            ->orderBy('attended_at', 'desc')
            ->paginate(20);

        return view('lessons.progress', compact('progress', 'attendances'));
    }
}
