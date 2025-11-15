<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSubjectProgress extends Model
{
    protected $table = 'user_subject_progress';

    protected $fillable = [
        'user_id',
        'subject_id',
        'level',
        'experience',
        'required_exp',
        'total_lessons_attended',
        'total_questions_answered',
        'total_correct_answers',
        'average_score',
        'grade',
        'last_attended',
    ];

    protected $casts = [
        'average_score' => 'float',
        'last_attended' => 'datetime',
    ];

    /**
     * Get the user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the subject.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Add experience and level up if needed.
     */
    public function addExperience($amount)
    {
        $this->experience += $amount;
        $leveledUp = false;

        while ($this->experience >= $this->required_exp) {
            $this->experience -= $this->required_exp;
            $this->level++;
            $leveledUp = true;

            // Increase required exp for next level
            $this->required_exp = (int) (100 * pow(1.3, $this->level - 1));
        }

        $this->save();

        return $leveledUp;
    }

    /**
     * Update grade based on average score.
     */
    public function updateGrade()
    {
        $score = $this->average_score;

        if ($score < 30) {
            $this->grade = 'T'; // Troll
        } elseif ($score < 50) {
            $this->grade = 'D'; // Desolante
        } elseif ($score < 60) {
            $this->grade = 'P'; // Penoso
        } elseif ($score < 70) {
            $this->grade = 'A'; // Accettabile
        } elseif ($score < 90) {
            $this->grade = 'E'; // Eccezionale
        } else {
            $this->grade = 'O'; // Oltre ogni previsione
        }

        $this->save();
    }

    /**
     * Get grade label.
     */
    public function getGradeLabelAttribute()
    {
        return match($this->grade) {
            'T' => 'Troll',
            'D' => 'Desolante',
            'P' => 'Penoso',
            'A' => 'Accettabile',
            'E' => 'Eccezionale',
            'O' => 'Oltre ogni previsione',
            default => 'N/A'
        };
    }

    /**
     * Get grade color.
     */
    public function getGradeColorAttribute()
    {
        return match($this->grade) {
            'T' => 'danger',
            'D' => 'danger',
            'P' => 'warning',
            'A' => 'info',
            'E' => 'success',
            'O' => 'purple',
            default => 'secondary'
        };
    }

    /**
     * Get accuracy percentage.
     */
    public function getAccuracyAttribute()
    {
        if ($this->total_questions_answered == 0) {
            return 0;
        }
        return round(($this->total_correct_answers / $this->total_questions_answered) * 100, 2);
    }
}
