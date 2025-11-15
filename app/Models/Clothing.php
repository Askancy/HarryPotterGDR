<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Clothing extends Model
{
    protected $table = 'clothing';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'type',
        'rarity',
        'image',
        'strength_bonus',
        'intelligence_bonus',
        'dexterity_bonus',
        'charisma_bonus',
        'defense_bonus',
        'magic_bonus',
        'required_level',
        'required_house',
        'price',
        'is_available',
        'is_tradeable',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'is_tradeable' => 'boolean',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($clothing) {
            if (empty($clothing->slug)) {
                $clothing->slug = Str::slug($clothing->name);
            }
        });
    }

    /**
     * Get users who own this clothing.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_clothing')
            ->withPivot('quantity', 'acquired_at')
            ->withTimestamps();
    }

    /**
     * Get rarity color class.
     */
    public function getRarityColorAttribute()
    {
        return match($this->rarity) {
            'common' => 'text-gray-500',
            'rare' => 'text-blue-500',
            'epic' => 'text-purple-500',
            'legendary' => 'text-yellow-500',
            default => 'text-gray-500'
        };
    }

    /**
     * Get rarity label.
     */
    public function getRarityLabelAttribute()
    {
        return match($this->rarity) {
            'common' => 'Comune',
            'rare' => 'Raro',
            'epic' => 'Epico',
            'legendary' => 'Leggendario',
            default => 'Comune'
        };
    }

    /**
     * Get type label.
     */
    public function getTypeLabelAttribute()
    {
        return match($this->type) {
            'hat' => 'Cappello',
            'robe' => 'Veste',
            'shirt' => 'Camicia',
            'pants' => 'Pantaloni',
            'shoes' => 'Scarpe',
            'accessory' => 'Accessorio',
            'cloak' => 'Mantello',
            default => $this->type
        };
    }

    /**
     * Get total bonus.
     */
    public function getTotalBonusAttribute()
    {
        return $this->strength_bonus +
               $this->intelligence_bonus +
               $this->dexterity_bonus +
               $this->charisma_bonus +
               $this->defense_bonus +
               $this->magic_bonus;
    }

    /**
     * Check if user can wear this clothing.
     */
    public function canBeWornBy(User $user)
    {
        // Check level requirement
        if ($user->level < $this->required_level) {
            return false;
        }

        // Check house requirement
        if ($this->required_house && $user->team != $this->required_house) {
            return false;
        }

        return true;
    }

    /**
     * Get image URL.
     */
    public function getImageUrlAttribute()
    {
        if ($this->image && file_exists(public_path('upload/clothing/' . $this->image))) {
            return asset('upload/clothing/' . $this->image);
        }
        return asset('upload/clothing/default.png');
    }
}
