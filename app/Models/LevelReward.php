<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LevelReward extends Model
{
    protected $fillable = [
        'level',
        'money_reward',
        'skill_points',
        'title',
        'unlocks'
    ];

    protected $casts = [
        'level' => 'integer',
        'money_reward' => 'integer',
        'skill_points' => 'integer',
        'unlocks' => 'array'
    ];
}
