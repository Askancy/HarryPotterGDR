<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassEnrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'school_class_id',
        'school_year_id',
        'status',
        'enrollment_date',
        'completion_date',
        'attendance_count',
        'absence_count',
    ];

    protected $casts = [
        'enrollment_date' => 'date',
        'completion_date' => 'date',
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
     * Relazione con la classe
     */
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }

    /**
     * Relazione con l'anno scolastico
     */
    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    /**
     * Relazione con i voti
     */
    public function grades()
    {
        return $this->hasMany(Grade::class, 'enrollment_id');
    }

    /**
     * Calcola la media dei voti
     */
    public function getAverageGrade(): ?float
    {
        $grades = $this->grades;

        if ($grades->isEmpty()) {
            return null;
        }

        // Calcola media pesata
        $totalWeight = 0;
        $weightedSum = 0;

        foreach ($grades as $grade) {
            $numericValue = $this->gradeLetterToNumeric($grade->grade_letter);
            $weightedSum += $numericValue * $grade->weight;
            $totalWeight += $grade->weight;
        }

        return $totalWeight > 0 ? $weightedSum / $totalWeight : null;
    }

    /**
     * Converti voto letterale in numerico
     */
    protected function gradeLetterToNumeric(string $letter): int
    {
        return match($letter) {
            'O' => 100, // Outstanding
            'E' => 85,  // Exceeds Expectations
            'A' => 70,  // Acceptable
            'P' => 50,  // Poor
            'D' => 30,  // Dreadful
            'T' => 10,  // Troll
            default => 0,
        };
    }

    /**
     * Verifica se lo studente ha superato la classe
     */
    public function hasPassed(): bool
    {
        $average = $this->getAverageGrade();
        return $average !== null && $average >= 50; // Almeno Poor
    }

    /**
     * Segna presenze
     */
    public function markAttendance(): void
    {
        $this->increment('attendance_count');
    }

    /**
     * Segna assenze
     */
    public function markAbsence(): void
    {
        $this->increment('absence_count');
    }

    /**
     * Completa l'iscrizione
     */
    public function complete(): void
    {
        $this->update([
            'status' => $this->hasPassed() ? 'completed' : 'failed',
            'completion_date' => now(),
        ]);
    }

    /**
     * Ritira dallo studente
     */
    public function drop(): void
    {
        $this->update(['status' => 'dropped']);
    }
}
