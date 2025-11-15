<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Perk extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'required_level',
        'required_perk_id',
        'required_skill',
        'required_subject',
        'perk_points_cost',
        'effects',
        'type',
        'max_rank',
        'icon',
        'is_active',
    ];

    protected $casts = [
        'effects' => 'array',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($perk) {
            if (empty($perk->slug)) {
                $perk->slug = Str::slug($perk->name);
            }
        });
    }

    /**
     * Get the category.
     */
    public function category()
    {
        return $this->belongsTo(PerkCategory::class, 'category_id');
    }

    /**
     * Get required perk.
     */
    public function requiredPerk()
    {
        return $this->belongsTo(Perk::class, 'required_perk_id');
    }

    /**
     * Get users who have this perk.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_perks')
            ->withPivot('rank', 'is_equipped', 'unlocked_at')
            ->withTimestamps();
    }

    /**
     * Check if user can unlock this perk.
     */
    public function canBeUnlockedBy(User $user)
    {
        // Check level
        if ($user->level < $this->required_level) {
            return false;
        }

        // Check perk points
        if ($user->perk_points < $this->perk_points_cost) {
            return false;
        }

        // Check required perk
        if ($this->required_perk_id) {
            $hasRequiredPerk = $user->perks()->where('perk_id', $this->required_perk_id)->exists();
            if (!$hasRequiredPerk) {
                return false;
            }
        }

        // Check required skill
        if ($this->required_skill) {
            [$skill, $value] = explode(':', $this->required_skill);
            if ($user->$skill < $value) {
                return false;
            }
        }

        // Check required subject
        if ($this->required_subject) {
            [$subjectSlug, $level] = explode(':', $this->required_subject);
            $subject = Subject::where('slug', $subjectSlug)->first();
            if ($subject) {
                $progress = $user->subjectProgress()->where('subject_id', $subject->id)->first();
                if (!$progress || $progress->level < $level) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Get type label.
     */
    public function getTypeLabelAttribute()
    {
        return match($this->type) {
            'passive' => 'Passivo',
            'active' => 'Attivo',
            'toggle' => 'Attivabile',
            default => 'Sconosciuto'
        };
    }
}
