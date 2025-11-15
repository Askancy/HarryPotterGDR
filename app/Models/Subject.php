<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'min_grade',
        'max_grade',
        'is_core',
        'is_active',
        'icon',
    ];

    protected $casts = [
        'is_core' => 'boolean',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Boot method per generare slug automaticamente
     */
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
     * Relazione con le classi
     */
    public function classes()
    {
        return $this->hasMany(SchoolClass::class);
    }

    /**
     * Ottieni tutte le materie obbligatorie
     */
    public static function getCoreSubjects()
    {
        return static::where('is_core', true)
            ->where('is_active', true)
            ->get();
    }

    /**
     * Ottieni materie per anno
     */
    public static function getForGrade(int $grade)
    {
        return static::where('is_active', true)
            ->where('min_grade', '<=', $grade)
            ->where('max_grade', '>=', $grade)
            ->get();
    }

    /**
     * Seed delle materie base di Hogwarts
     */
    public static function seedHogwartsSubjects(): void
    {
        $subjects = [
            // Materie obbligatorie (anni 1-5)
            [
                'name' => 'Difesa contro le Arti Oscure',
                'description' => 'Insegnamento delle difese contro le creature oscure e la magia nera',
                'min_grade' => 1,
                'max_grade' => 7,
                'is_core' => true,
                'icon' => '🛡️',
            ],
            [
                'name' => 'Trasfigurazione',
                'description' => 'Arte di trasformare un oggetto in un altro',
                'min_grade' => 1,
                'max_grade' => 7,
                'is_core' => true,
                'icon' => '✨',
            ],
            [
                'name' => 'Pozioni',
                'description' => 'Preparazione e utilizzo di pozioni magiche',
                'min_grade' => 1,
                'max_grade' => 7,
                'is_core' => true,
                'icon' => '🧪',
            ],
            [
                'name' => 'Incantesimi',
                'description' => 'Studio e pratica degli incantesimi',
                'min_grade' => 1,
                'max_grade' => 7,
                'is_core' => true,
                'icon' => '🪄',
            ],
            [
                'name' => 'Erbologia',
                'description' => 'Studio delle piante magiche e delle loro proprietà',
                'min_grade' => 1,
                'max_grade' => 7,
                'is_core' => true,
                'icon' => '🌿',
            ],
            [
                'name' => 'Storia della Magia',
                'description' => 'Storia del mondo magico e dei maghi famosi',
                'min_grade' => 1,
                'max_grade' => 7,
                'is_core' => true,
                'icon' => '📚',
            ],
            [
                'name' => 'Astronomia',
                'description' => 'Studio dei corpi celesti e della loro influenza sulla magia',
                'min_grade' => 1,
                'max_grade' => 7,
                'is_core' => true,
                'icon' => '🔭',
            ],
            // Materie opzionali (anni 3+)
            [
                'name' => 'Divinazione',
                'description' => 'Arte di predire il futuro',
                'min_grade' => 3,
                'max_grade' => 7,
                'is_core' => false,
                'icon' => '🔮',
            ],
            [
                'name' => 'Cura delle Creature Magiche',
                'description' => 'Studio e cura delle creature magiche',
                'min_grade' => 3,
                'max_grade' => 7,
                'is_core' => false,
                'icon' => '🐉',
            ],
            [
                'name' => 'Aritmanzia',
                'description' => 'Studio delle proprietà magiche dei numeri',
                'min_grade' => 3,
                'max_grade' => 7,
                'is_core' => false,
                'icon' => '🔢',
            ],
            [
                'name' => 'Studio delle Rune',
                'description' => 'Studio delle rune antiche e del loro potere',
                'min_grade' => 3,
                'max_grade' => 7,
                'is_core' => false,
                'icon' => 'ᚱ',
            ],
        ];

        foreach ($subjects as $subject) {
            static::firstOrCreate(['slug' => Str::slug($subject['name'])], $subject);
        }
    }
}
