<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'category',
        'max_level',
        'icon',
        'bonuses'
    ];

    protected $casts = [
        'bonuses' => 'array',
        'max_level' => 'integer'
    ];

    /**
     * Get the users that have this skill.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_skills')
            ->withPivot('level', 'experience')
            ->withTimestamps();
    }

    /**
     * Get user skills.
     */
    public function userSkills()
    {
        return $this->hasMany(UserSkill::class);
    }
}
