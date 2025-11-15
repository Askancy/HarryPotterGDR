<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YearlyPerformance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'school_year_id',
        'grade_level',
        'average_grade',
        'total_classes',
        'passed_classes',
        'failed_classes',
        'total_absences',
        'status',
        'notes',
        'evaluation_date',
    ];

    protected $casts = [
        'average_grade' => 'decimal:2',
        'evaluation_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relazione con l'utente
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relazione con l'anno scolastico
     */
    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    /**
     * Calcola la performance dello studente
     */
    public function calculate(): void
    {
        $enrollments = ClassEnrollment::where('user_id', $this->user_id)
            ->where('school_year_id', $this->school_year_id)
            ->get();

        $totalClasses = $enrollments->count();
        $passedClasses = 0;
        $failedClasses = 0;
        $totalGrade = 0;
        $totalAbsences = 0;

        foreach ($enrollments as $enrollment) {
            $totalAbsences += $enrollment->absence_count;
            $average = $enrollment->getAverageGrade();

            if ($average !== null) {
                $totalGrade += $average;

                if ($enrollment->hasPassed()) {
                    $passedClasses++;
                } else {
                    $failedClasses++;
                }
            }
        }

        $averageGrade = $totalClasses > 0 ? $totalGrade / $totalClasses : 0;

        $this->update([
            'total_classes' => $totalClasses,
            'passed_classes' => $passedClasses,
            'failed_classes' => $failedClasses,
            'average_grade' => $averageGrade,
            'total_absences' => $totalAbsences,
        ]);
    }

    /**
     * Valuta se lo studente viene promosso o bocciato
     */
    public function evaluate(): string
    {
        $this->calculate();

        // Criteri di promozione:
        // 1. Media >= 60 (almeno Acceptable)
        // 2. Massimo 2 materie insufficienti
        // 3. Assenze < 30% delle lezioni totali

        $maxAllowedAbsences = $this->total_classes * 0.3;
        $isGraduating = $this->grade_level == 7;

        if ($this->average_grade >= 60
            && $this->failed_classes <= 2
            && $this->total_absences <= $maxAllowedAbsences) {

            $status = $isGraduating ? 'graduated' : 'promoted';
        } else {
            $status = 'retained'; // Bocciato
        }

        $this->update([
            'status' => $status,
            'evaluation_date' => now(),
        ]);

        return $status;
    }

    /**
     * Promuovi lo studente
     */
    public function promote(): void
    {
        $user = $this->user;

        if ($this->status === 'graduated') {
            // Studente diplomato
            $user->update(['school_grade' => 7]); // Resta al 7° anno ma è diplomato
        } else {
            // Promuovi all'anno successivo
            $user->increment('school_grade');
        }
    }

    /**
     * Genera performance per tutti gli studenti di un anno
     */
    public static function generateForYear(SchoolYear $schoolYear): void
    {
        $students = User::where('current_school_year_id', $schoolYear->id)->get();

        foreach ($students as $student) {
            $performance = static::firstOrCreate([
                'user_id' => $student->id,
                'school_year_id' => $schoolYear->id,
            ], [
                'grade_level' => $student->school_grade,
                'status' => 'in_progress',
            ]);

            $performance->calculate();
        }
    }

    /**
     * Valuta tutti gli studenti di un anno
     */
    public static function evaluateAll(SchoolYear $schoolYear): array
    {
        $performances = static::where('school_year_id', $schoolYear->id)
            ->where('status', 'in_progress')
            ->get();

        $results = [
            'promoted' => 0,
            'retained' => 0,
            'graduated' => 0,
        ];

        foreach ($performances as $performance) {
            $status = $performance->evaluate();
            $results[$status]++;

            // Promuovi se necessario
            if (in_array($status, ['promoted', 'graduated'])) {
                $performance->promote();
            }
        }

        return $results;
    }
}
