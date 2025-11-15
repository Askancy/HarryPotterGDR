<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SchoolEvent extends Model
{
    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'type',
        'suspend_lessons',
        'bonuses',
        'icon',
        'color',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'suspend_lessons' => 'boolean',
        'bonuses' => 'array',
    ];

    /**
     * Check if event is currently active.
     */
    public function isActive()
    {
        $today = Carbon::today();
        return $today->between($this->start_date, $this->end_date ?? $this->start_date);
    }

    /**
     * Get type label.
     */
    public function getTypeLabelAttribute()
    {
        return match($this->type) {
            'holiday' => 'Festività',
            'exam' => 'Esame',
            'event' => 'Evento',
            'special' => 'Speciale',
            default => 'Evento'
        };
    }

    /**
     * Get active events for today.
     */
    public static function getActiveEvents()
    {
        $today = Carbon::today();
        return static::where('start_date', '<=', $today)
            ->where(function ($query) use ($today) {
                $query->where('end_date', '>=', $today)
                    ->orWhereNull('end_date');
            })
            ->get();
    }
}
