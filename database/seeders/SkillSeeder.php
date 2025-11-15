<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Skill;

class SkillSeeder extends Seeder
{
    public function run()
    {
        $skills = [
            // Combat
            [
                'name' => 'Duello',
                'slug' => 'duello',
                'description' => 'Abilità nel combattimento magico e nei duelli.',
                'category' => 'combat',
                'max_level' => 10,
                'icon' => 'fa-wand-magic-sparkles',
                'bonuses' => ['damage' => 5]
            ],
            [
                'name' => 'Precisione',
                'slug' => 'precisione',
                'description' => 'Migliora la precisione degli incantesimi in combattimento.',
                'category' => 'combat',
                'max_level' => 10,
                'icon' => 'fa-bullseye',
                'bonuses' => ['accuracy' => 3]
            ],

            // Magic
            [
                'name' => 'Potenza Magica',
                'slug' => 'potenza-magica',
                'description' => 'Aumenta la potenza generale dei tuoi incantesimi.',
                'category' => 'magic',
                'max_level' => 10,
                'icon' => 'fa-bolt',
                'bonuses' => ['power' => 8]
            ],
            [
                'name' => 'Efficienza Magica',
                'slug' => 'efficienza-magica',
                'description' => 'Riduce il costo di mana degli incantesimi.',
                'category' => 'magic',
                'max_level' => 10,
                'icon' => 'fa-battery-three-quarters',
                'bonuses' => ['mana_cost_reduction' => 5]
            ],

            // Defense
            [
                'name' => 'Difesa contro le Arti Oscure',
                'slug' => 'difesa-arti-oscure',
                'description' => 'Resistenza agli incantesimi oscuri e malefici.',
                'category' => 'defense',
                'max_level' => 10,
                'icon' => 'fa-shield-halved',
                'bonuses' => ['dark_resistance' => 10]
            ],
            [
                'name' => 'Protego',
                'slug' => 'protego',
                'description' => 'Abilità negli incantesimi di protezione.',
                'category' => 'defense',
                'max_level' => 10,
                'icon' => 'fa-shield',
                'bonuses' => ['shield_power' => 15]
            ],

            // Herbology
            [
                'name' => 'Erbologia',
                'slug' => 'erbologia',
                'description' => 'Conoscenza delle piante magiche e delle loro proprietà.',
                'category' => 'herbology',
                'max_level' => 10,
                'icon' => 'fa-leaf',
                'bonuses' => ['herb_gathering' => 20]
            ],

            // Potions
            [
                'name' => 'Preparazione Pozioni',
                'slug' => 'preparazione-pozioni',
                'description' => 'Abilità nella preparazione di pozioni magiche.',
                'category' => 'potions',
                'max_level' => 10,
                'icon' => 'fa-flask',
                'bonuses' => ['potion_quality' => 15]
            ],
            [
                'name' => 'Alchimia',
                'slug' => 'alchimia',
                'description' => 'Conoscenza avanzata delle trasformazioni alchemiche.',
                'category' => 'potions',
                'max_level' => 10,
                'icon' => 'fa-vial',
                'bonuses' => ['potion_efficiency' => 10]
            ],

            // Charms
            [
                'name' => 'Incantesimi',
                'slug' => 'incantesimi',
                'description' => 'Abilità nell\'eseguire incantesimi di vario tipo.',
                'category' => 'charms',
                'max_level' => 10,
                'icon' => 'fa-sparkles',
                'bonuses' => ['charm_duration' => 20]
            ],

            // Transfiguration
            [
                'name' => 'Trasfigurazione',
                'slug' => 'trasfigurazione',
                'description' => 'Abilità nel trasformare oggetti e creature.',
                'category' => 'transfiguration',
                'max_level' => 10,
                'icon' => 'fa-shuffle',
                'bonuses' => ['transformation_power' => 12]
            ],

            // Divination
            [
                'name' => 'Divinazione',
                'slug' => 'divinazione',
                'description' => 'Capacità di prevedere il futuro e leggere i segni.',
                'category' => 'divination',
                'max_level' => 10,
                'icon' => 'fa-crystal-ball',
                'bonuses' => ['luck' => 5]
            ]
        ];

        foreach ($skills as $skill) {
            Skill::create($skill);
        }
    }
}
