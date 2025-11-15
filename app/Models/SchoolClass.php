<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'school_year_id',
        'professor_id',
        'grade_level',
        'section',
        'max_students',
        'classroom',
        'schedule',
        'is_active',
    ];

    protected $casts = [
        'schedule' => 'array',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relazione con la materia
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Relazione con l'anno scolastico
     */
    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    /**
     * Relazione con il professore
     */
    public function professor()
    {
        return $this->belongsTo(User::class, 'professor_id');
    }

    /**
     * Relazione con le iscrizioni
     */
    public function enrollments()
    {
        return $this->hasMany(ClassEnrollment::class);
    }

    /**
     * Studenti iscritti
     */
    public function students()
    {
        return $this->belongsToMany(User::class, 'class_enrollments')
            ->withPivot('status', 'enrollment_date', 'attendance_count', 'absence_count')
            ->withTimestamps();
    }

    /**
     * Relazione con i voti
     */
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    /**
     * Verifica se la classe è piena
     */
    public function isFull(): bool
    {
        return $this->enrollments()->where('status', 'enrolled')->count() >= $this->max_students;
    }

    /**
     * Ottieni il numero di studenti iscritti
     */
    public function getEnrolledCount(): int
    {
        return $this->enrollments()->where('status', 'enrolled')->count();
    }

    /**
     * Iscrivi uno studente
     */
    public function enrollStudent(User $user): ?ClassEnrollment
    {
        if ($this->isFull()) {
            return null;
        }

        // Verifica che lo studente sia dell'anno giusto
        if ($user->school_grade !== $this->grade_level) {
            return null;
        }

        return ClassEnrollment::create([
            'user_id' => $user->id,
            'school_class_id' => $this->id,
            'school_year_id' => $this->school_year_id,
            'status' => 'enrolled',
            'enrollment_date' => now(),
        ]);
    }

    /**
     * Genera classi per un anno scolastico
     */
    public static function generateForYear(SchoolYear $schoolYear): void
    {
        // Per ogni anno (1-7) crea le classi obbligatorie
        for ($grade = 1; $grade <= 7; $grade++) {
            $subjects = Subject::getForGrade($grade);

            foreach ($subjects as $subject) {
                // Crea una sezione per ogni materia obbligatoria
                if ($subject->is_core || $grade >= 3) {
                    static::create([
                        'subject_id' => $subject->id,
                        'school_year_id' => $schoolYear->id,
                        'grade_level' => $grade,
                        'section' => 'A',
                        'max_students' => 30,
                        'is_active' => true,
                    ]);
                }
            }
        }
    }
}
