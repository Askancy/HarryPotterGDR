<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Subject extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'professor_name',
        'classroom',
        'difficulty',
        'min_level',
        'icon',
        'color',
        'base_exp',
        'base_house_points',
        'primary_skill',
        'secondary_skill',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($subject) {
            if (empty($subject->slug)) {
                $subject->slug = Str::slug($subject->name);
            }
        });
    }

    /**
     * Get daily lessons for this subject.
     */
    public function dailyLessons()
    {
        return $this->hasMany(DailyLesson::class);
    }

    /**
     * Get user progress for this subject.
     */
    public function userProgress()
    {
        return $this->hasMany(UserSubjectProgress::class);
    }

    /**
     * Get difficulty label.
     */
    public function getDifficultyLabelAttribute()
    {
        return match($this->difficulty) {
            'beginner' => 'Principiante',
            'intermediate' => 'Intermedio',
            'advanced' => 'Avanzato',
            'expert' => 'Esperto',
            default => 'Principiante'
        };
    }

    /**
     * Check if user can attend this subject.
     */
    public function canBeAttendedBy(User $user)
    {
        return $user->level >= $this->min_level;
    }
}
