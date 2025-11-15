<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonAttendance extends Model
{
    protected $fillable = [
        'user_id',
        'daily_lesson_id',
        'subject_id',
        'performance',
        'exp_earned',
        'house_points_earned',
        'questions_answered',
        'correct_answers',
        'attended_at',
    ];

    protected $casts = [
        'attended_at' => 'datetime',
    ];

    /**
     * Get the user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the daily lesson.
     */
    public function dailyLesson()
    {
        return $this->belongsTo(DailyLesson::class);
    }

    /**
     * Get the subject.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get performance label.
     */
    public function getPerformanceLabelAttribute()
    {
        return match($this->performance) {
            'poor' => 'Scarso',
            'acceptable' => 'Accettabile',
            'good' => 'Buono',
            'excellent' => 'Eccellente',
            'outstanding' => 'Eccezionale',
            default => 'Accettabile'
        };
    }

    /**
     * Get performance color.
     */
    public function getPerformanceColorAttribute()
    {
        return match($this->performance) {
            'poor' => 'danger',
            'acceptable' => 'warning',
            'good' => 'info',
            'excellent' => 'success',
            'outstanding' => 'purple',
            default => 'secondary'
        };
    }

    /**
     * Get accuracy percentage.
     */
    public function getAccuracyAttribute()
    {
        if ($this->questions_answered == 0) {
            return 0;
        }
        return round(($this->correct_answers / $this->questions_answered) * 100, 2);
    }
}
