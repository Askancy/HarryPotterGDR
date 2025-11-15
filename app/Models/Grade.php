<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'enrollment_id',
        'user_id',
        'school_class_id',
        'type',
        'grade_letter',
        'grade_numeric',
        'weight',
        'feedback',
        'graded_by',
        'assignment_date',
        'graded_date',
    ];

    protected $casts = [
        'assignment_date' => 'date',
        'graded_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relazione con l'iscrizione
     */
    public function enrollment()
    {
        return $this->belongsTo(ClassEnrollment::class);
    }

    /**
     * Relazione con lo studente
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relazione con la classe
     */
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }

    /**
     * Relazione con chi ha dato il voto
     */
    public function gradedBy()
    {
        return $this->belongsTo(User::class, 'graded_by');
    }

    /**
     * Converti voto numerico in letterale
     */
    public static function numericToLetter(int $numeric): string
    {
        return match(true) {
            $numeric >= 90 => 'O',  // Outstanding
            $numeric >= 75 => 'E',  // Exceeds Expectations
            $numeric >= 60 => 'A',  // Acceptable
            $numeric >= 40 => 'P',  // Poor
            $numeric >= 20 => 'D',  // Dreadful
            default => 'T',         // Troll
        };
    }

    /**
     * Converti voto letterale in numerico
     */
    public static function letterToNumeric(string $letter): int
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
     * Ottieni il nome completo del voto
     */
    public function getGradeFullName(): string
    {
        return match($this->grade_letter) {
            'O' => 'Outstanding (Eccezionale)',
            'E' => 'Exceeds Expectations (Supera le Aspettative)',
            'A' => 'Acceptable (Accettabile)',
            'P' => 'Poor (Scarso)',
            'D' => 'Dreadful (Deludente)',
            'T' => 'Troll (Terribile)',
            default => 'Unknown',
        };
    }

    /**
     * Verifica se il voto è sufficiente
     */
    public function isPassing(): bool
    {
        return in_array($this->grade_letter, ['O', 'E', 'A']);
    }

    /**
     * Boot method per auto-conversione
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($grade) {
            // Se viene fornito numeric ma non letter, calcola letter
            if ($grade->grade_numeric !== null && empty($grade->grade_letter)) {
                $grade->grade_letter = static::numericToLetter($grade->grade_numeric);
            }
            // Se viene fornito letter ma non numeric, calcola numeric
            elseif (!empty($grade->grade_letter) && $grade->grade_numeric === null) {
                $grade->grade_numeric = static::letterToNumeric($grade->grade_letter);
            }
        });
    }
}
