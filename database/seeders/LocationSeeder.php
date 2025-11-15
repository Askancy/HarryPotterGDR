<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationSeeder extends Seeder
{
    public function run()
    {
        $locations = [
            [
                'name' => 'Diagon Alley',
                'slug' => 'diagon-alley',
                'description' => 'La famosa via dello shopping del mondo magico, dove i maghi e le streghe possono trovare tutto ciò di cui hanno bisogno.',
                'type' => 'city',
                'required_level' => 1,
                'is_active' => true,
                'can_have_events' => true
            ],
            [
                'name' => 'Hogsmeade',
                'slug' => 'hogsmeade',
                'description' => 'L\'unico villaggio completamente magico in Gran Bretagna, famoso per le sue locande accoglienti e i suoi negozi incantevoli.',
                'type' => 'village',
                'required_level' => 3,
                'is_active' => true,
                'can_have_events' => true
            ],
            [
                'name' => 'Knockturn Alley',
                'slug' => 'knockturn-alley',
                'description' => 'Una strada laterale oscura e pericolosa di Diagon Alley, nota per i suoi negozi di arti oscure.',
                'type' => 'city',
                'required_level' => 10,
                'is_active' => true,
                'can_have_events' => true
            ],
            [
                'name' => 'Godric\'s Hollow',
                'slug' => 'godrics-hollow',
                'description' => 'Un pittoresco villaggio nel West Country, ricco di storia magica.',
                'type' => 'village',
                'required_level' => 5,
                'is_active' => true,
                'can_have_events' => true
            ],
            [
                'name' => 'The Forbidden Forest',
                'slug' => 'forbidden-forest',
                'description' => 'Una foresta oscura e pericolosa ai confini di Hogwarts, casa di creature magiche.',
                'type' => 'landmark',
                'required_level' => 15,
                'is_active' => true,
                'can_have_events' => true
            ]
        ];

        foreach ($locations as $location) {
            Location::create($location);
        }
    }
}
