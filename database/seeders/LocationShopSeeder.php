<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Location;
use App\Models\LocationShop;

class LocationShopSeeder extends Seeder
{
    public function run()
    {
        $diagonAlley = Location::where('slug', 'diagon-alley')->first();
        $hogsmeade = Location::where('slug', 'hogsmeade')->first();
        $knockturn = Location::where('slug', 'knockturn-alley')->first();

        $shops = [
            // Diagon Alley
            [
                'location_id' => $diagonAlley->id,
                'name' => 'Olivander',
                'slug' => 'olivander',
                'description' => 'Il negozio di bacchette più famoso del mondo magico. "La bacchetta sceglie il mago."',
                'type' => 'wands',
                'owner_name' => 'Mr. Olivander',
                'required_level' => 1,
                'is_purchasable' => false,
                'is_active' => true
            ],
            [
                'location_id' => $diagonAlley->id,
                'name' => 'MondoMago',
                'slug' => 'mondomago',
                'description' => 'Un negozio che vende oggetti magici vari, giochi e scherzi.',
                'type' => 'general',
                'owner_name' => 'Proprietario Sconosciuto',
                'required_level' => 1,
                'is_purchasable' => true,
                'purchase_price' => 50000,
                'is_active' => true,
                'profit_percentage' => 15
            ],
            [
                'location_id' => $diagonAlley->id,
                'name' => 'Weasley Wizard Wheezes',
                'slug' => 'weasley-wizard-wheezes',
                'description' => 'Il negozio di scherzi dei gemelli Weasley, pieno di articoli divertenti e innovativi.',
                'type' => 'general',
                'owner_name' => 'Fred e George Weasley',
                'required_level' => 1,
                'is_purchasable' => false,
                'is_active' => true
            ],
            [
                'location_id' => $diagonAlley->id,
                'name' => 'Slug & Jiggers Apothecary',
                'slug' => 'slug-jiggers',
                'description' => 'La farmacia dove si possono trovare tutti gli ingredienti per pozioni.',
                'type' => 'potions',
                'owner_name' => 'Mr. Slug',
                'required_level' => 2,
                'is_purchasable' => true,
                'purchase_price' => 75000,
                'is_active' => true,
                'profit_percentage' => 20
            ],
            [
                'location_id' => $diagonAlley->id,
                'name' => 'Flourish and Blotts',
                'slug' => 'flourish-blotts',
                'description' => 'La libreria principale del mondo magico, con migliaia di libri su ogni argomento magico.',
                'type' => 'books',
                'owner_name' => 'Manager',
                'required_level' => 1,
                'is_purchasable' => true,
                'purchase_price' => 60000,
                'is_active' => true,
                'profit_percentage' => 12
            ],

            // Knockturn Alley
            [
                'location_id' => $knockturn->id,
                'name' => 'Borgin and Burkes',
                'slug' => 'borgin-burkes',
                'description' => 'Un negozio che vende oggetti legati alle Arti Oscure.',
                'type' => 'general',
                'owner_name' => 'Mr. Borgin',
                'required_level' => 10,
                'is_purchasable' => false,
                'is_active' => true
            ],

            // Hogsmeade
            [
                'location_id' => $hogsmeade->id,
                'name' => 'The Three Broomsticks',
                'slug' => 'three-broomsticks',
                'description' => 'Una locanda accogliente e popolare, famosa per la sua burrobirra.',
                'type' => 'inn',
                'owner_name' => 'Madam Rosmerta',
                'required_level' => 3,
                'is_purchasable' => true,
                'purchase_price' => 100000,
                'is_active' => true,
                'profit_percentage' => 25
            ],
            [
                'location_id' => $hogsmeade->id,
                'name' => 'Hog\'s Head',
                'slug' => 'hogs-head',
                'description' => 'Una locanda più oscura e discreta, frequentata da personaggi loschi.',
                'type' => 'inn',
                'owner_name' => 'Aberforth Dumbledore',
                'required_level' => 5,
                'is_purchasable' => false,
                'is_active' => true
            ],
            [
                'location_id' => $hogsmeade->id,
                'name' => 'Honeydukes',
                'slug' => 'honeydukes',
                'description' => 'Il negozio di dolciumi più famoso del mondo magico.',
                'type' => 'general',
                'owner_name' => 'Mr. Flume',
                'required_level' => 3,
                'is_purchasable' => true,
                'purchase_price' => 80000,
                'is_active' => true,
                'profit_percentage' => 18
            ],
            [
                'location_id' => $hogsmeade->id,
                'name' => 'Zonko\'s Joke Shop',
                'slug' => 'zonkos',
                'description' => 'Un negozio specializzato in scherzi e trucchi magici.',
                'type' => 'general',
                'owner_name' => 'Mr. Zonko',
                'required_level' => 3,
                'is_purchasable' => true,
                'purchase_price' => 55000,
                'is_active' => true,
                'profit_percentage' => 15
            ]
        ];

        foreach ($shops as $shop) {
            LocationShop::create($shop);
        }
    }
}
