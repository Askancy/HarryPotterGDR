<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolYear;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\ClassEnrollment;
use App\Models\YearlyPerformance;
use Illuminate\Support\Facades\Auth;

class SchoolController extends Controller
{
    /**
     * Display school dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        $schoolYear = SchoolYear::getActive();

        if (!$schoolYear) {
            return view('school.no-active-year');
        }

        $enrollments = $user->classEnrollments()
            ->where('school_year_id', $schoolYear->id)
            ->where('status', 'enrolled')
            ->with(['schoolClass.subject', 'schoolClass.professor'])
            ->get();

        $performance = $user->currentYearPerformance;

        return view('school.index', compact('user', 'schoolYear', 'enrollments', 'performance'));
    }

    /**
     * Display available classes for enrollment.
     */
    public function classes()
    {
        $user = Auth::user();
        $schoolYear = SchoolYear::getActive();

        if (!$schoolYear) {
            return redirect()->route('school.index')
                ->with('error', 'Nessun anno scolastico attivo');
        }

        $availableClasses = SchoolClass::where('school_year_id', $schoolYear->id)
            ->where('grade_level', $user->school_grade)
            ->where('is_active', true)
            ->with(['subject', 'professor'])
            ->get()
            ->map(function ($class) use ($user) {
                $class->is_enrolled = $user->classEnrollments()
                    ->where('school_class_id', $class->id)
                    ->where('status', 'enrolled')
                    ->exists();

                $class->can_enroll = $user->canEnrollInClass($class);
                $class->enrolled_count = $class->getEnrolledCount();

                return $class;
            });

        return view('school.classes', compact('availableClasses', 'schoolYear'));
    }

    /**
     * Enroll in a class.
     */
    public function enroll(Request $request, SchoolClass $class)
    {
        $user = Auth::user();

        if (!$user->canEnrollInClass($class)) {
            return back()->with('error', 'Non puoi iscriverti a questa classe');
        }

        $enrollment = $class->enrollStudent($user);

        if (!$enrollment) {
            return back()->with('error', 'Impossibile iscriversi alla classe');
        }

        $user->notify(
            'class_enrollment',
            'Iscrizione Confermata',
            "Ti sei iscritto a {$class->subject->name} - Anno {$class->grade_level}",
            '📚',
            '/school/classes'
        );

        return back()->with('success', "Ti sei iscritto a {$class->subject->name}!");
    }

    /**
     * Display class details.
     */
    public function classShow(SchoolClass $class)
    {
        $user = Auth::user();

        $enrollment = $user->classEnrollments()
            ->where('school_class_id', $class->id)
            ->first();

        if (!$enrollment) {
            abort(403, 'Non sei iscritto a questa classe');
        }

        $grades = $enrollment->grades()->orderBy('graded_date', 'desc')->get();
        $averageGrade = $enrollment->getAverageGrade();

        $classmates = $class->students()
            ->where('users.id', '!=', $user->id)
            ->limit(10)
            ->get();

        return view('school.class-show', compact('class', 'enrollment', 'grades', 'averageGrade', 'classmates'));
    }

    /**
     * Display yearly performance.
     */
    public function performance()
    {
        $user = Auth::user();

        $currentPerformance = $user->currentYearPerformance;
        $pastPerformances = $user->yearlyPerformances()
            ->where('status', '!=', 'in_progress')
            ->orderBy('school_year_id', 'desc')
            ->with('schoolYear')
            ->get();

        return view('school.performance', compact('currentPerformance', 'pastPerformances'));
    }

    /**
     * Display school calendar.
     */
    public function calendar()
    {
        $schoolYear = SchoolYear::getActive();

        if (!$schoolYear) {
            return view('school.no-active-year');
        }

        $terms = $schoolYear->terms()->orderBy('order')->get();
        $upcomingEvents = $schoolYear->events()
            ->where('event_date', '>', now())
            ->orderBy('event_date')
            ->limit(10)
            ->get();

        $currentTerm = $terms->first(function ($term) {
            return $term->isActive();
        });

        return view('school.calendar', compact('schoolYear', 'terms', 'upcomingEvents', 'currentTerm'));
    }

    /**
     * Auto-enroll in core subjects.
     */
    public function autoEnroll()
    {
        $user = Auth::user();
        $schoolYear = SchoolYear::getActive();

        if (!$schoolYear) {
            return back()->with('error', 'Nessun anno scolastico attivo');
        }

        $user->enrollInYear($schoolYear);

        return redirect()->route('school.index')
            ->with('success', 'Iscrizione automatica completata!');
    }
}
