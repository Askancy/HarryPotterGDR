<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DailyLesson extends Model
{
    protected $fillable = [
        'date',
        'subject_id',
        'slot',
        'max_participants',
        'current_participants',
        'exp_multiplier',
        'points_multiplier',
        'special_bonus',
    ];

    protected $casts = [
        'date' => 'date',
        'exp_multiplier' => 'float',
        'points_multiplier' => 'float',
    ];

    /**
     * Get the subject.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get attendances for this lesson.
     */
    public function attendances()
    {
        return $this->hasMany(LessonAttendance::class);
    }

    /**
     * Get users who attended this lesson.
     */
    public function attendees()
    {
        return $this->belongsToMany(User::class, 'lesson_attendances')
            ->withPivot('performance', 'exp_earned', 'house_points_earned', 'attended_at')
            ->withTimestamps();
    }

    /**
     * Check if lesson is full.
     */
    public function isFull()
    {
        return $this->current_participants >= $this->max_participants;
    }

    /**
     * Check if user has attended this lesson.
     */
    public function hasUserAttended(User $user)
    {
        return $this->attendances()->where('user_id', $user->id)->exists();
    }

    /**
     * Get slot label.
     */
    public function getSlotLabelAttribute()
    {
        return $this->slot === 'morning' ? 'Mattina' : 'Pomeriggio';
    }

    /**
     * Check if lesson is available (today or future).
     */
    public function isAvailable()
    {
        return $this->date->isToday() || $this->date->isFuture();
    }

    /**
     * Scope for today's lessons.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('date', Carbon::today());
    }

    /**
     * Scope for upcoming lessons.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', Carbon::today());
    }
}
