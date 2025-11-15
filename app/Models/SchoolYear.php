<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SchoolYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'year_number',
        'start_date',
        'end_date',
        'is_active',
        'theme',
        'description',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relazione con i termini scolastici
     */
    public function terms()
    {
        return $this->hasMany(SchoolTerm::class);
    }

    /**
     * Relazione con gli eventi scolastici
     */
    public function events()
    {
        return $this->hasMany(SchoolEvent::class);
    }

    /**
     * Relazione con le classi
     */
    public function classes()
    {
        return $this->hasMany(SchoolClass::class);
    }

    /**
     * Relazione con le performance annuali
     */
    public function performances()
    {
        return $this->hasMany(YearlyPerformance::class);
    }

    /**
     * Studenti iscritti in questo anno
     */
    public function students()
    {
        return $this->hasMany(User::class, 'current_school_year_id');
    }

    /**
     * Ottieni l'anno scolastico attivo
     */
    public static function getActive()
    {
        return static::where('is_active', true)->first();
    }

    /**
     * Genera proceduralmente un nuovo anno scolastico
     */
    public static function generateYear(int $yearNumber, ?string $theme = null): self
    {
        // L'anno scolastico inizia il 1 settembre e finisce il 30 giugno
        $currentYear = Carbon::now()->year;
        $startDate = Carbon::create($currentYear, 9, 1);
        $endDate = Carbon::create($currentYear + 1, 6, 30);

        $schoolYear = static::create([
            'year_number' => $yearNumber,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'is_active' => false,
            'theme' => $theme,
            'description' => "Anno Scolastico {$yearNumber}",
        ]);

        // Genera automaticamente i termini
        $schoolYear->generateTerms();

        return $schoolYear;
    }

    /**
     * Genera proceduralmente i termini scolastici
     */
    public function generateTerms(): void
    {
        $startDate = Carbon::parse($this->start_date);

        // Primo Trimestre (1 settembre - 20 dicembre)
        SchoolTerm::create([
            'school_year_id' => $this->id,
            'name' => 'Primo Trimestre',
            'type' => 'term',
            'start_date' => $startDate->copy(),
            'end_date' => $startDate->copy()->month(12)->day(20),
            'order' => 1,
        ]);

        // Vacanze di Natale (21 dicembre - 6 gennaio)
        SchoolTerm::create([
            'school_year_id' => $this->id,
            'name' => 'Vacanze di Natale',
            'type' => 'holiday',
            'start_date' => $startDate->copy()->month(12)->day(21),
            'end_date' => $startDate->copy()->addYear()->month(1)->day(6),
            'order' => 2,
        ]);

        // Secondo Trimestre (7 gennaio - 31 marzo)
        SchoolTerm::create([
            'school_year_id' => $this->id,
            'name' => 'Secondo Trimestre',
            'type' => 'term',
            'start_date' => $startDate->copy()->addYear()->month(1)->day(7),
            'end_date' => $startDate->copy()->addYear()->month(3)->day(31),
            'order' => 3,
        ]);

        // Vacanze di Pasqua (1 aprile - 15 aprile)
        SchoolTerm::create([
            'school_year_id' => $this->id,
            'name' => 'Vacanze di Pasqua',
            'type' => 'holiday',
            'start_date' => $startDate->copy()->addYear()->month(4)->day(1),
            'end_date' => $startDate->copy()->addYear()->month(4)->day(15),
            'order' => 4,
        ]);

        // Terzo Trimestre (16 aprile - 15 giugno)
        SchoolTerm::create([
            'school_year_id' => $this->id,
            'name' => 'Terzo Trimestre',
            'type' => 'term',
            'start_date' => $startDate->copy()->addYear()->month(4)->day(16),
            'end_date' => $startDate->copy()->addYear()->month(6)->day(15),
            'order' => 5,
        ]);

        // Esami Finali (16 giugno - 30 giugno)
        SchoolTerm::create([
            'school_year_id' => $this->id,
            'name' => 'Esami Finali',
            'type' => 'exam_period',
            'start_date' => $startDate->copy()->addYear()->month(6)->day(16),
            'end_date' => $startDate->copy()->addYear()->month(6)->day(30),
            'order' => 6,
        ]);
    }

    /**
     * Attiva questo anno scolastico e disattiva gli altri
     */
    public function activate(): void
    {
        static::where('is_active', true)->update(['is_active' => false]);
        $this->update(['is_active' => true]);
    }

    /**
     * Verifica se l'anno è in corso
     */
    public function isInProgress(): bool
    {
        $now = Carbon::now();
        return $now->between($this->start_date, $this->end_date);
    }

    /**
     * Verifica se l'anno è terminato
     */
    public function isFinished(): bool
    {
        return Carbon::now()->isAfter($this->end_date);
    }
}
