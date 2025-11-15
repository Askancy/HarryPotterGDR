<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LevelReward;

class LevelRewardSeeder extends Seeder
{
    public function run()
    {
        $levels = [
            ['level' => 1, 'money_reward' => 0, 'skill_points' => 3, 'title' => 'Studente Novizio'],
            ['level' => 2, 'money_reward' => 100, 'skill_points' => 1, 'title' => 'Apprendista'],
            ['level' => 3, 'money_reward' => 150, 'skill_points' => 1, 'title' => 'Studente'],
            ['level' => 4, 'money_reward' => 200, 'skill_points' => 1, 'title' => null],
            ['level' => 5, 'money_reward' => 300, 'skill_points' => 2, 'title' => 'Mago Promettente'],
            ['level' => 6, 'money_reward' => 400, 'skill_points' => 1, 'title' => null],
            ['level' => 7, 'money_reward' => 500, 'skill_points' => 1, 'title' => null],
            ['level' => 8, 'money_reward' => 600, 'skill_points' => 1, 'title' => null],
            ['level' => 9, 'money_reward' => 700, 'skill_points' => 1, 'title' => null],
            ['level' => 10, 'money_reward' => 1000, 'skill_points' => 2, 'title' => 'Mago Esperto'],
            ['level' => 11, 'money_reward' => 1200, 'skill_points' => 1, 'title' => null],
            ['level' => 12, 'money_reward' => 1400, 'skill_points' => 1, 'title' => null],
            ['level' => 13, 'money_reward' => 1600, 'skill_points' => 1, 'title' => null],
            ['level' => 14, 'money_reward' => 1800, 'skill_points' => 1, 'title' => null],
            ['level' => 15, 'money_reward' => 2500, 'skill_points' => 3, 'title' => 'Stregone Abile'],
            ['level' => 16, 'money_reward' => 2800, 'skill_points' => 1, 'title' => null],
            ['level' => 17, 'money_reward' => 3100, 'skill_points' => 1, 'title' => null],
            ['level' => 18, 'money_reward' => 3400, 'skill_points' => 1, 'title' => null],
            ['level' => 19, 'money_reward' => 3700, 'skill_points' => 1, 'title' => null],
            ['level' => 20, 'money_reward' => 5000, 'skill_points' => 3, 'title' => 'Maestro della Magia'],
        ];

        foreach ($levels as $level) {
            LevelReward::create($level);
        }
    }
}
