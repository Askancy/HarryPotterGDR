<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSkill extends Model
{
    protected $fillable = [
        'user_id',
        'skill_id',
        'level',
        'experience'
    ];

    protected $casts = [
        'level' => 'integer',
        'experience' => 'integer'
    ];

    /**
     * Get the user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the skill.
     */
    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }

    /**
     * Add experience to the skill.
     */
    public function addExperience($amount)
    {
        $this->experience += $amount;

        // Check if level up (example: 100 exp per level)
        $expNeeded = $this->level * 100;

        while ($this->experience >= $expNeeded && $this->level < $this->skill->max_level) {
            $this->experience -= $expNeeded;
            $this->level++;
            $expNeeded = $this->level * 100;
        }

        $this->save();

        return $this;
    }
}
