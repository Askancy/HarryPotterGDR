<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'type',
        'base_payment',
        'min_level',
        'min_grade',
        'duration_minutes',
        'cooldown_hours',
        'requirements',
        'location_id',
        'is_active',
    ];

    protected $casts = [
        'base_payment' => 'decimal:2',
        'requirements' => 'array',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Boot method per generare slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($job) {
            if (empty($job->slug)) {
                $job->slug = Str::slug($job->name);
            }
        });
    }

    /**
     * Relazione con la location
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Relazione con i completamenti
     */
    public function completions()
    {
        return $this->hasMany(JobCompletion::class);
    }

    /**
     * Verifica se l'utente può svolgere il lavoro
     */
    public function canUserPerform(User $user): bool
    {
        // Verifica livello minimo
        if ($user->level < $this->min_level) {
            return false;
        }

        // Verifica anno scolastico minimo
        if ($user->school_grade < $this->min_grade) {
            return false;
        }

        // Verifica requirements (skills, ecc.)
        if ($this->requirements) {
            foreach ($this->requirements as $requirement => $value) {
                // Es: ['skill_potions' => 5, 'skill_herbology' => 3]
                if (str_starts_with($requirement, 'skill_')) {
                    $skillName = str_replace('skill_', '', $requirement);
                    $userSkillLevel = $user->getSkillLevel($skillName);
                    if ($userSkillLevel < $value) {
                        return false;
                    }
                }
            }
        }

        // Verifica cooldown
        if (!$this->isAvailableForUser($user)) {
            return false;
        }

        return true;
    }

    /**
     * Verifica se il lavoro è disponibile per l'utente (cooldown)
     */
    public function isAvailableForUser(User $user): bool
    {
        $lastCompletion = JobCompletion::where('user_id', $user->id)
            ->where('job_id', $this->id)
            ->orderBy('completed_at', 'desc')
            ->first();

        if (!$lastCompletion) {
            return true;
        }

        return now()->isAfter($lastCompletion->next_available_at);
    }

    /**
     * Calcola il pagamento basato sulla qualità
     */
    public function calculatePayment(?int $qualityScore = null): float
    {
        $payment = $this->base_payment;

        if ($qualityScore !== null) {
            // Bonus/malus basato sulla qualità (0-100)
            $multiplier = 0.5 + ($qualityScore / 100); // da 0.5x a 1.5x
            $payment *= $multiplier;
        }

        return round($payment, 2);
    }

    /**
     * Seed lavori base
     */
    public static function seedDefaultJobs(): void
    {
        $jobs = [
            [
                'name' => 'Assistente Bibliotecario',
                'description' => 'Aiuta Madama Pince a riordinare i libri nella Biblioteca di Hogwarts',
                'type' => 'daily',
                'base_payment' => 5.0,
                'min_level' => 1,
                'min_grade' => 1,
                'duration_minutes' => 60,
                'cooldown_hours' => 24,
                'is_active' => true,
            ],
            [
                'name' => 'Raccolta Ingredienti',
                'description' => 'Raccogli ingredienti per pozioni nelle serre di Hogwarts',
                'type' => 'repeatable',
                'base_payment' => 8.0,
                'min_level' => 3,
                'min_grade' => 1,
                'duration_minutes' => 90,
                'cooldown_hours' => 12,
                'requirements' => ['skill_herbology' => 2],
                'is_active' => true,
            ],
            [
                'name' => 'Tutoraggio Studenti',
                'description' => 'Aiuta studenti più giovani con i compiti',
                'type' => 'repeatable',
                'base_payment' => 12.0,
                'min_level' => 5,
                'min_grade' => 3,
                'duration_minutes' => 120,
                'cooldown_hours' => 24,
                'is_active' => true,
            ],
            [
                'name' => 'Assistente di Pozioni',
                'description' => 'Assisti il professore di Pozioni durante le lezioni',
                'type' => 'daily',
                'base_payment' => 15.0,
                'min_level' => 7,
                'min_grade' => 4,
                'duration_minutes' => 90,
                'cooldown_hours' => 24,
                'requirements' => ['skill_potions' => 5],
                'is_active' => true,
            ],
            [
                'name' => 'Custode delle Creature',
                'description' => 'Aiuta Hagrid a prendersi cura delle creature magiche',
                'type' => 'repeatable',
                'base_payment' => 10.0,
                'min_level' => 4,
                'min_grade' => 3,
                'duration_minutes' => 120,
                'cooldown_hours' => 24,
                'is_active' => true,
            ],
        ];

        foreach ($jobs as $job) {
            static::firstOrCreate(['slug' => Str::slug($job['name'])], $job);
        }
    }
}
