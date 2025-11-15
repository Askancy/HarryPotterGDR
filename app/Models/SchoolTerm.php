<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SchoolTerm extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_year_id',
        'name',
        'type',
        'start_date',
        'end_date',
        'order',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relazione con l'anno scolastico
     */
    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    /**
     * Relazione con gli eventi
     */
    public function events()
    {
        return $this->hasMany(SchoolEvent::class);
    }

    /**
     * Verifica se il termine è attivo ora
     */
    public function isActive(): bool
    {
        $now = Carbon::now();
        return $now->between($this->start_date, $this->end_date);
    }

    /**
     * Verifica se è un periodo di vacanza
     */
    public function isHoliday(): bool
    {
        return $this->type === 'holiday';
    }

    /**
     * Verifica se è un periodo di esami
     */
    public function isExamPeriod(): bool
    {
        return $this->type === 'exam_period';
    }

    /**
     * Ottieni il termine corrente
     */
    public static function getCurrentTerm()
    {
        $now = Carbon::now();
        return static::where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->first();
    }
}
