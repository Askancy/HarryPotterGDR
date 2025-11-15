<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RandomEvent;

class RandomEventSeeder extends Seeder
{
    public function run()
    {
        $events = [
            // Location events
            [
                'name' => 'Incontro Misterioso',
                'description' => 'Incontri uno strano mago che ti offre un indovinello. Se rispondi correttamente, ricevi una ricompensa.',
                'type' => 'location',
                'rarity' => 'common',
                'required_level' => 1,
                'rewards' => ['exp' => 50, 'money' => 100],
                'choices' => [
                    ['text' => 'Accetta la sfida', 'success_rate' => 70],
                    ['text' => 'Declina educatamente', 'success_rate' => 100]
                ],
                'duration_minutes' => 30,
                'is_active' => true
            ],
            [
                'name' => 'Creatura Magica Ferita',
                'description' => 'Trovi una creatura magica ferita. Puoi curarla o lasciarla.',
                'type' => 'location',
                'rarity' => 'uncommon',
                'required_level' => 3,
                'rewards' => ['exp' => 100, 'money' => 200],
                'choices' => [
                    ['text' => 'Cura la creatura', 'success_rate' => 60],
                    ['text' => 'Lascia stare', 'success_rate' => 100]
                ],
                'duration_minutes' => 45,
                'is_active' => true
            ],
            [
                'name' => 'Tesoro Nascosto',
                'description' => 'Scopri una mappa del tesoro! Segui le indicazioni per trovare il tesoro nascosto.',
                'type' => 'treasure',
                'rarity' => 'rare',
                'required_level' => 5,
                'rewards' => ['exp' => 200, 'money' => 500],
                'choices' => [
                    ['text' => 'Segui la mappa', 'success_rate' => 50],
                    ['text' => 'Vendi la mappa', 'success_rate' => 100]
                ],
                'duration_minutes' => 60,
                'is_active' => true
            ],

            // Inn events
            [
                'name' => 'Sfida a Scacchi Magici',
                'description' => 'Un avventore della locanda ti sfida a una partita di scacchi magici.',
                'type' => 'inn',
                'rarity' => 'common',
                'required_level' => 1,
                'rewards' => ['exp' => 30, 'money' => 50],
                'choices' => [
                    ['text' => 'Accetta la sfida', 'success_rate' => 60],
                    ['text' => 'Declina', 'success_rate' => 100]
                ],
                'duration_minutes' => 20,
                'is_active' => true
            ],
            [
                'name' => 'Storia del Vecchio Mago',
                'description' => 'Un vecchio mago inizia a raccontare una storia antica. Ascolti attentamente?',
                'type' => 'inn',
                'rarity' => 'uncommon',
                'required_level' => 2,
                'rewards' => ['exp' => 80, 'money' => 150],
                'choices' => [
                    ['text' => 'Ascolta attentamente', 'success_rate' => 80],
                    ['text' => 'Ignora il mago', 'success_rate' => 100]
                ],
                'duration_minutes' => 30,
                'is_active' => true
            ],
            [
                'name' => 'Duello Amichevole',
                'description' => 'Alcuni maghi stanno organizzando un duello amichevole nella locanda. Vuoi partecipare?',
                'type' => 'inn',
                'rarity' => 'rare',
                'required_level' => 5,
                'rewards' => ['exp' => 150, 'money' => 300],
                'choices' => [
                    ['text' => 'Partecipa al duello', 'success_rate' => 40],
                    ['text' => 'Osserva da spettatore', 'success_rate' => 100]
                ],
                'duration_minutes' => 45,
                'is_active' => true
            ],

            // Mystery events
            [
                'name' => 'Oggetto Misterioso',
                'description' => 'Trovi un oggetto misterioso che emana una strana energia magica.',
                'type' => 'mystery',
                'rarity' => 'epic',
                'required_level' => 10,
                'rewards' => ['exp' => 300, 'money' => 800],
                'choices' => [
                    ['text' => 'Esamina l\'oggetto', 'success_rate' => 30],
                    ['text' => 'Lascia stare', 'success_rate' => 100]
                ],
                'duration_minutes' => 90,
                'is_active' => true
            ],
            [
                'name' => 'Portale Dimensionale',
                'description' => 'Un portale dimensionale si apre davanti a te. Attraversarlo potrebbe portare grandi ricompense... o grandi pericoli.',
                'type' => 'mystery',
                'rarity' => 'legendary',
                'required_level' => 15,
                'rewards' => ['exp' => 500, 'money' => 1500],
                'choices' => [
                    ['text' => 'Attraversa il portale', 'success_rate' => 20],
                    ['text' => 'Studia il portale da lontano', 'success_rate' => 100]
                ],
                'duration_minutes' => 120,
                'is_active' => true
            ]
        ];

        foreach ($events as $event) {
            RandomEvent::create($event);
        }
    }
}
