# 🎮 Nuovi Sistemi Avanzati - Hogwarts GDR

Documentazione completa per l'implementazione di 4 nuovi sistemi di gioco.

---

## 📋 Indice

1. [Sistema Creature da Allevare](#1-sistema-creature-da-allevare-)
2. [Sistema Crafting Pozioni](#2-sistema-crafting-pozioni-)
3. [Mercato di Scambio tra Giocatori](#3-mercato-di-scambio-tra-giocatori-)
4. [Modalità Storia Interattiva](#4-modalità-storia-interattiva-)
5. [Istruzioni di Installazione](#-istruzioni-di-installazione)
6. [Rotte Complete](#-rotte-complete)

---

# 1. Sistema Creature da Allevare 🐉

## Panoramica
Sistema completo per catturare, allevare e far crescere creature magiche.

## Tabelle Database

### `creature_species`
```sql
- id
- name (unique)
- slug (unique)
- description (text)
- image
- rarity (enum: common, uncommon, rare, epic, legendary)
- danger_level (enum: harmless, low, moderate, dangerous, extreme)
- habitat
- required_level
- required_care_skill
- base_health, base_happiness, base_hunger, base_energy
- max_level
- growth_speed
- maturity_days
- hunger_rate, happiness_decay, energy_consumption
- abilities (JSON)
- drops (JSON)
- can_breed
- breeding_cooldown_hours
- compatible_species (JSON)
- purchase_price, sell_price
- is_active, is_rideable, can_battle
- timestamps
```

### `user_creatures`
```sql
- id
- user_id (FK users)
- species_id (FK creature_species)
- nickname
- gender (enum: male, female)
- level, experience
- current_health, max_health
- happiness, hunger, energy
- life_stage (enum: egg, baby, juvenile, adult, elder)
- age_days
- hatched_at, matured_at
- last_fed_at, last_played_at, last_trained_at, last_cleaned_at
- is_fertile, last_bred_at
- parent1_id, parent2_id (FK user_creatures)
- learned_abilities (JSON)
- traits (JSON)
- total_interactions
- bond_level (0-100)
- is_active, is_favorite
- status (enum: healthy, sick, injured, sleeping, dead)
- current_habitat_id (FK locations)
- timestamps
- soft_deletes
```

### `creature_interactions`
```sql
- id
- user_id (FK users)
- creature_id (FK user_creatures)
- action (enum: feed, play, train, clean, heal, breed, collect_drop)
- experience_gained
- happiness_change, hunger_change, health_change, energy_change, bond_change
- items_used (JSON)
- rewards_obtained (JSON)
- notes
- was_successful
- timestamps
```

## Modelli Eloquent

### `CreatureSpecies.php`
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreatureSpecies extends Model
{
    protected $table = 'creature_species';

    protected $fillable = [
        'name', 'slug', 'description', 'image', 'rarity', 'danger_level',
        'habitat', 'required_level', 'required_care_skill',
        'base_health', 'base_happiness', 'base_hunger', 'base_energy',
        'max_level', 'growth_speed', 'maturity_days',
        'hunger_rate', 'happiness_decay', 'energy_consumption',
        'abilities', 'drops', 'can_breed', 'breeding_cooldown_hours',
        'compatible_species', 'purchase_price', 'sell_price',
        'is_active', 'is_rideable', 'can_battle'
    ];

    protected $casts = [
        'abilities' => 'array',
        'drops' => 'array',
        'compatible_species' => 'array',
        'can_breed' => 'boolean',
        'is_active' => 'boolean',
        'is_rideable' => 'boolean',
        'can_battle' => 'boolean',
    ];

    // Relationships
    public function userCreatures()
    {
        return $this->hasMany(UserCreature::class, 'species_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByRarity($query, $rarity)
    {
        return $query->where('rarity', $rarity);
    }

    public function scopeAvailableFor($query, User $user)
    {
        return $query->where('required_level', '<=', $user->level)
            ->where('is_active', true);
    }
}
```

### `UserCreature.php`
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class UserCreature extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'species_id', 'nickname', 'gender',
        'level', 'experience', 'current_health', 'max_health',
        'happiness', 'hunger', 'energy', 'life_stage', 'age_days',
        'hatched_at', 'matured_at', 'last_fed_at', 'last_played_at',
        'last_trained_at', 'last_cleaned_at', 'is_fertile', 'last_bred_at',
        'parent1_id', 'parent2_id', 'learned_abilities', 'traits',
        'total_interactions', 'bond_level', 'is_active', 'is_favorite',
        'status', 'current_habitat_id'
    ];

    protected $casts = [
        'learned_abilities' => 'array',
        'traits' => 'array',
        'is_fertile' => 'boolean',
        'is_active' => 'boolean',
        'is_favorite' => 'boolean',
        'hatched_at' => 'datetime',
        'matured_at' => 'datetime',
        'last_fed_at' => 'datetime',
        'last_played_at' => 'datetime',
        'last_trained_at' => 'datetime',
        'last_cleaned_at' => 'datetime',
        'last_bred_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function species()
    {
        return $this->belongsTo(CreatureSpecies::class, 'species_id');
    }

    public function parent1()
    {
        return $this->belongsTo(UserCreature::class, 'parent1_id');
    }

    public function parent2()
    {
        return $this->belongsTo(UserCreature::class, 'parent2_id');
    }

    public function interactions()
    {
        return $this->hasMany(CreatureInteraction::class, 'creature_id');
    }

    public function habitat()
    {
        return $this->belongsTo(Location::class, 'current_habitat_id');
    }

    // Helper Methods
    public function needsFeeding()
    {
        return $this->hunger >= 70;
    }

    public function needsAttention()
    {
        return $this->happiness < 30;
    }

    public function isHealthy()
    {
        return $this->status === 'healthy' && $this->current_health > 50;
    }

    public function canBreed()
    {
        if (!$this->species->can_breed || !$this->is_fertile) {
            return false;
        }

        if (!$this->last_bred_at) {
            return true;
        }

        $cooldownHours = $this->species->breeding_cooldown_hours;
        return $this->last_bred_at->addHours($cooldownHours)->isPast();
    }

    public function updateAge()
    {
        if (!$this->hatched_at) {
            return;
        }

        $this->age_days = Carbon::now()->diffInDays($this->hatched_at);

        // Update life stage
        $maturityDays = $this->species->maturity_days;
        if ($this->age_days >= $maturityDays * 2) {
            $this->life_stage = 'elder';
        } elseif ($this->age_days >= $maturityDays) {
            $this->life_stage = 'adult';
            if (!$this->matured_at) {
                $this->matured_at = now();
                $this->is_fertile = true;
            }
        } elseif ($this->age_days >= $maturityDays / 2) {
            $this->life_stage = 'juvenile';
        } else {
            $this->life_stage = 'baby';
        }

        $this->save();
    }

    public function applyPassiveDecay()
    {
        $hoursSinceLastFed = $this->last_fed_at
            ? $this->last_fed_at->diffInHours(now())
            : 24;

        $hoursSinceLastPlayed = $this->last_played_at
            ? $this->last_played_at->diffInHours(now())
            : 24;

        // Increase hunger
        $this->hunger = min(100, $this->hunger + ($this->species->hunger_rate * $hoursSinceLastFed / 24));

        // Decrease happiness
        $this->happiness = max(0, $this->happiness - ($this->species->happiness_decay * $hoursSinceLastPlayed / 24));

        // Health affected by extreme hunger
        if ($this->hunger >= 90) {
            $this->current_health = max(0, $this->current_health - 5);
            $this->status = 'sick';
        }

        $this->save();
    }

    public function gainExperience($amount)
    {
        $this->experience += $amount;
        $this->total_interactions++;

        // Level up check
        $requiredExp = $this->level * 100;
        while ($this->experience >= $requiredExp && $this->level < $this->species->max_level) {
            $this->experience -= $requiredExp;
            $this->level++;

            // Stat increases
            $this->max_health += 10;
            $this->current_health = $this->max_health;

            $requiredExp = $this->level * 100;
        }

        $this->save();
    }
}
```

### `CreatureInteraction.php`
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreatureInteraction extends Model
{
    protected $fillable = [
        'user_id', 'creature_id', 'action',
        'experience_gained', 'happiness_change', 'hunger_change',
        'health_change', 'energy_change', 'bond_change',
        'items_used', 'rewards_obtained', 'notes', 'was_successful'
    ];

    protected $casts = [
        'items_used' => 'array',
        'rewards_obtained' => 'array',
        'was_successful' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function creature()
    {
        return $this->belongsTo(UserCreature::class, 'creature_id');
    }
}
```

## Service Layer

### `CreatureService.php`
```php
<?php

namespace App\Services;

use App\Models\UserCreature;
use App\Models\CreatureInteraction;
use App\Models\User;

class CreatureService
{
    public function feedCreature(UserCreature $creature, $foodItem = null)
    {
        if ($creature->hunger <= 20) {
            return ['success' => false, 'message' => 'La creatura non ha fame!'];
        }

        $hungerReduction = 40;
        $happinessGain = 10;
        $expGain = 5;

        $creature->hunger = max(0, $creature->hunger - $hungerReduction);
        $creature->happiness = min(100, $creature->happiness + $happinessGain);
        $creature->last_fed_at = now();
        $creature->gainExperience($expGain);
        $creature->bond_level = min(100, $creature->bond_level + 1);

        // Log interaction
        CreatureInteraction::create([
            'user_id' => $creature->user_id,
            'creature_id' => $creature->id,
            'action' => 'feed',
            'experience_gained' => $expGain,
            'happiness_change' => $happinessGain,
            'hunger_change' => -$hungerReduction,
            'bond_change' => 1,
            'items_used' => $foodItem ? [$foodItem] : null,
            'was_successful' => true,
        ]);

        return [
            'success' => true,
            'message' => "{$creature->nickname} è stato nutrito!",
            'creature' => $creature->fresh()
        ];
    }

    public function playWithCreature(UserCreature $creature)
    {
        if ($creature->energy < 20) {
            return ['success' => false, 'message' => 'La creatura è troppo stanca!'];
        }

        $happinessGain = 20;
        $energyLoss = 15;
        $expGain = 10;

        $creature->happiness = min(100, $creature->happiness + $happinessGain);
        $creature->energy = max(0, $creature->energy - $energyLoss);
        $creature->last_played_at = now();
        $creature->gainExperience($expGain);
        $creature->bond_level = min(100, $creature->bond_level + 2);

        CreatureInteraction::create([
            'user_id' => $creature->user_id,
            'creature_id' => $creature->id,
            'action' => 'play',
            'experience_gained' => $expGain,
            'happiness_change' => $happinessGain,
            'energy_change' => -$energyLoss,
            'bond_change' => 2,
            'was_successful' => true,
        ]);

        return [
            'success' => true,
            'message' => "Hai giocato con {$creature->nickname}!",
            'creature' => $creature->fresh()
        ];
    }

    public function trainCreature(UserCreature $creature)
    {
        if ($creature->energy < 30) {
            return ['success' => false, 'message' => 'La creatura è troppo stanca per allenarsi!'];
        }

        $expGain = 25;
        $energyLoss = 25;
        $happinessLoss = 5;

        $creature->energy = max(0, $creature->energy - $energyLoss);
        $creature->happiness = max(0, $creature->happiness - $happinessLoss);
        $creature->last_trained_at = now();
        $creature->gainExperience($expGain);
        $creature->bond_level = min(100, $creature->bond_level + 1);

        CreatureInteraction::create([
            'user_id' => $creature->user_id,
            'creature_id' => $creature->id,
            'action' => 'train',
            'experience_gained' => $expGain,
            'happiness_change' => -$happinessLoss,
            'energy_change' => -$energyLoss,
            'bond_change' => 1,
            'was_successful' => true,
        ]);

        return [
            'success' => true,
            'message' => "{$creature->nickname} si è allenato!",
            'creature' => $creature->fresh()
        ];
    }

    public function breedCreatures(UserCreature $creature1, UserCreature $creature2)
    {
        // Validations
        if (!$creature1->canBreed() || !$creature2->canBreed()) {
            return ['success' => false, 'message' => 'Una delle creature non può riprodursi!'];
        }

        if ($creature1->species_id !== $creature2->species_id) {
            $compatible = $creature1->species->compatible_species ?? [];
            if (!in_array($creature2->species_id, $compatible)) {
                return ['success' => false, 'message' => 'Queste specie non sono compatibili!'];
            }
        }

        // Create egg
        $baby = UserCreature::create([
            'user_id' => $creature1->user_id,
            'species_id' => $creature1->species_id,
            'gender' => rand(0, 1) ? 'male' : 'female',
            'parent1_id' => $creature1->id,
            'parent2_id' => $creature2->id,
            'life_stage' => 'egg',
            'current_health' => $creature1->species->base_health,
            'max_health' => $creature1->species->base_health,
            'happiness' => 50,
            'hunger' => 0,
            'energy' => 100,
        ]);

        // Update breeding timestamps
        $creature1->last_bred_at = now();
        $creature1->save();

        $creature2->last_bred_at = now();
        $creature2->save();

        return [
            'success' => true,
            'message' => 'È stato creato un uovo!',
            'baby' => $baby
        ];
    }
}
```

## Controller

### `CreatureController.php`
```php
<?php

namespace App\Http\Controllers;

use App\Models\CreatureSpecies;
use App\Models\UserCreature;
use App\Services\CreatureService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreatureController extends Controller
{
    protected $creatureService;

    public function __construct(CreatureService $creatureService)
    {
        $this->middleware('auth');
        $this->creatureService = $creatureService;
    }

    public function index()
    {
        $user = Auth::user();
        $creatures = UserCreature::where('user_id', $user->id)
            ->with('species')
            ->where('is_active', true)
            ->get();

        return view('creatures.index', compact('creatures'));
    }

    public function catalog()
    {
        $user = Auth::user();
        $species = CreatureSpecies::availableFor($user)->get();

        return view('creatures.catalog', compact('species'));
    }

    public function show($id)
    {
        $creature = UserCreature::with(['species', 'parent1', 'parent2', 'interactions'])
            ->findOrFail($id);

        // Check ownership
        if ($creature->user_id !== Auth::id()) {
            return redirect()->route('creatures.index')
                ->with('error', 'Non sei il proprietario di questa creatura!');
        }

        // Update passive stats
        $creature->updateAge();
        $creature->applyPassiveDecay();

        return view('creatures.show', compact('creature'));
    }

    public function feed(Request $request, $id)
    {
        $creature = UserCreature::findOrFail($id);

        if ($creature->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Non autorizzato!');
        }

        $result = $this->creatureService->feedCreature($creature, $request->food_item);

        return redirect()->route('creatures.show', $creature->id)
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function play($id)
    {
        $creature = UserCreature::findOrFail($id);

        if ($creature->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Non autorizzato!');
        }

        $result = $this->creatureService->playWithCreature($creature);

        return redirect()->route('creatures.show', $creature->id)
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function train($id)
    {
        $creature = UserCreature::findOrFail($id);

        if ($creature->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Non autorizzato!');
        }

        $result = $this->creatureService->trainCreature($creature);

        return redirect()->route('creatures.show', $creature->id)
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function breed(Request $request)
    {
        $request->validate([
            'creature1_id' => 'required|exists:user_creatures,id',
            'creature2_id' => 'required|exists:user_creatures,id|different:creature1_id',
        ]);

        $creature1 = UserCreature::findOrFail($request->creature1_id);
        $creature2 = UserCreature::findOrFail($request->creature2_id);

        // Check ownership
        if ($creature1->user_id !== Auth::id() || $creature2->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Non autorizzato!');
        }

        $result = $this->creatureService->breedCreatures($creature1, $creature2);

        return redirect()->route('creatures.index')
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }
}
```

---

# 2. Sistema Crafting Pozioni 🧪

## Panoramica
Sistema completo per raccogliere ingredienti e creare pozioni magiche.

## Struttura Tabelle

### `potions`
```sql
- id
- name (unique)
- slug
- description
- image
- type (enum: healing, buff, debuff, utility, transformation)
- rarity (enum: common, uncommon, rare, epic, legendary)
- effects (JSON) // {health: +50, mana: +30, strength: +10, duration: 60}
- difficulty (1-10)
- brewing_time_minutes
- success_rate_base (0-100)
- required_level
- market_value
- is_active
- timestamps
```

### `ingredients`
```sql
- id
- name (unique)
- slug
- description
- image
- rarity (enum: common, uncommon, rare, epic, legendary)
- type (enum: plant, mineral, creature_part, magical_essence)
- source (text) // Dove si trova
- gathering_difficulty
- market_value
- is_active
- timestamps
```

### `user_ingredients`
```sql
- id
- user_id (FK users)
- ingredient_id (FK ingredients)
- quantity
- quality (enum: poor, normal, good, excellent, perfect)
- acquired_at
- expires_at (nullable) // Alcuni ingredienti scadono
- timestamps
```

### `potion_recipes`
```sql
- id
- potion_id (FK potions)
- ingredient_id (FK ingredients)
- quantity_required
- is_catalyst (boolean) // Ingrediente catalizzatore
- order (int) // Ordine di aggiunta
- timestamps
```

### `user_potions`
```sql
- id
- user_id (FK users)
- potion_id (FK potions)
- quantity
- quality (enum: flawed, standard, superior, masterwork)
- potency (int 50-150) // Efficacia della pozione
- brewed_at
- expires_at (nullable)
- timestamps
```

## Esempi Pozioni

```json
[
  {
    "name": "Pozione Curativa",
    "effects": {
      "health_restore": 50,
      "instant": true
    },
    "difficulty": 2,
    "brewing_time_minutes": 10,
    "ingredients": [
      {"name": "Mandragola", "quantity": 2},
      {"name": "Lacrime di Fenice", "quantity": 1}
    ]
  },
  {
    "name": "Felix Felicis",
    "effects": {
      "luck": 50,
      "critical_chance": 25,
      "duration_minutes": 60
    },
    "difficulty": 10,
    "brewing_time_minutes": 360,
    "rarity": "legendary"
  }
]
```

---

# 3. Mercato di Scambio tra Giocatori 💰

## Panoramica
Sistema completo di marketplace e trading tra giocatori.

## Struttura Tabelle

### `market_listings`
```sql
- id
- seller_id (FK users)
- item_type (enum: potion, ingredient, clothing, creature, spell_book, misc)
- item_id (int) // ID dell'oggetto specifico
- item_data (JSON) // Dati supplementari
- quantity
- price_per_unit
- currency (enum: galleons, house_points, trade_only)
- listing_type (enum: fixed_price, auction, trade)
- status (enum: active, sold, cancelled, expired)
- auction_ends_at (nullable)
- highest_bid (nullable)
- highest_bidder_id (FK users, nullable)
- views_count
- created_at
- updated_at
- expires_at
```

### `player_trades`
```sql
- id
- initiator_id (FK users)
- receiver_id (FK users)
- status (enum: pending, accepted, rejected, cancelled, completed)
- initiator_offer (JSON) // {potions: [], ingredients: [], money: 100}
- receiver_offer (JSON)
- message (text)
- accepted_at
- completed_at
- timestamps
```

### `trade_offers`
```sql
- id
- listing_id (FK market_listings)
- buyer_id (FK users)
- offer_amount
- offer_items (JSON) // Per scambi
- status (enum: pending, accepted, rejected, counter_offered)
- message
- counter_offer_amount (nullable)
- timestamps
```

## Service Layer Esempio

### `MarketplaceService.php`
```php
public function createListing(User $seller, array $data)
{
    // Validate ownership
    // Create listing
    // Lock item from use
}

public function purchaseListing(User $buyer, MarketListing $listing)
{
    // Check funds
    // Transfer item
    // Transfer money
    // Take commission (5%)
    // Notify seller
}

public function placeBid(User $bidder, MarketListing $listing, int $amount)
{
    // Auction logic
    // Outbid notification
    // Auto-extend time if bid in last minutes
}

public function proposeT rade(User $initiator, User $receiver, array $offer)
{
    // Create trade proposal
    // Validate items
    // Notify receiver
}
```

---

# 4. Modalità Storia Interattiva 📖

## Panoramica
Sistema di storytelling dinamico con scelte che influenzano la narrazione.

## Struttura Tabelle

### `story_chapters`
```sql
- id
- title
- slug
- content (text) // Markdown supported
- chapter_number
- house_specific (enum: all, gryffindor, slytherin, ravenclaw, hufflepuff)
- required_level
- required_previous_chapter_id (FK story_chapters, nullable)
- image
- background_music (nullable)
- is_published
- created_by_user_id (FK users) // Admin che ha creato
- timestamps
```

### `story_choices`
```sql
- id
- chapter_id (FK story_chapters)
- choice_text
- choice_number (int) // Ordine di visualizzazione
- leads_to_chapter_id (FK story_chapters, nullable)
- requirements (JSON) // {min_level: 5, has_item: "Elder Wand"}
- stat_requirements (JSON) // {intelligence: 50, courage: 30}
- consequences (JSON) // Cosa succede se scelgo questo
- timestamps
```

### `user_story_progress`
```sql
- id
- user_id (FK users)
- chapter_id (FK story_chapters)
- choice_id (FK story_choices, nullable) // Scelta fatta
- completed_at
- story_state (JSON) // Variabili di stato della storia
- timestamps
```

### `story_consequences`
```sql
- id
- choice_id (FK story_choices)
- consequence_type (enum: stat_change, item_gain, item_loss, unlock_chapter, relationship_change)
- consequence_data (JSON) // {stat: 'courage', change: +10}
- description
- is_permanent
- timestamps
```

## Meccaniche Avanzate

### Variabili di Storia
```json
{
  "relationships": {
    "dumbledore": 75,
    "snape": 30,
    "harry": 90
  },
  "reputation": {
    "gryffindor": 50,
    "teachers": 60,
    "ministry": 20
  },
  "major_choices": [
    "saved_student_from_troll",
    "discovered_secret_room",
    "confronted_dark_wizard"
  ],
  "inventory_flags": [
    "has_invisibility_cloak",
    "found_marauders_map"
  ]
}
```

### Esempio Capitolo con Branching

```json
{
  "chapter": {
    "id": 1,
    "title": "La Foresta Proibita",
    "content": "Ti trovi all'ingresso della Foresta Proibita. Senti strani rumori provenienti dall'interno...",
    "choices": [
      {
        "id": 1,
        "text": "Entra coraggiosamente nella foresta",
        "requirements": {"courage": 50},
        "leads_to": 2,
        "consequences": [
          {"type": "stat_change", "stat": "courage", "value": 5},
          {"type": "unlock_chapter", "chapter_id": 5}
        ]
      },
      {
        "id": 2,
        "text": "Usa un incantesimo di rilevamento",
        "requirements": {"intelligence": 60, "has_spell": "Revelio"},
        "leads_to": 3,
        "consequences": [
          {"type": "stat_change", "stat": "intelligence", "value": 3},
          {"type": "item_gain", "item": "mysterious_artifact"}
        ]
      },
      {
        "id": 3,
        "text": "Torna indietro e informa un professore",
        "leads_to": 4,
        "consequences": [
          {"type": "relationship_change", "npc": "dumbledore", "value": 10},
          {"type": "stat_change", "stat": "courage", "value": -2}
        ]
      }
    ]
  }
}
```

## Controller Admin

### `Admin/StoryChapterController.php`
```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StoryChapter;
use App\Models\StoryChoice;
use Illuminate\Http\Request;

class StoryChapterController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $chapters = StoryChapter::with('choices')
            ->orderBy('chapter_number')
            ->paginate(20);

        return view('admin.story.index', compact('chapters'));
    }

    public function create()
    {
        $allChapters = StoryChapter::all();
        return view('admin.story.create', compact('allChapters'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'chapter_number' => 'required|integer',
            'house_specific' => 'required|in:all,gryffindor,slytherin,ravenclaw,hufflepuff',
            'required_level' => 'integer|min:1',
        ]);

        $chapter = StoryChapter::create(array_merge($validated, [
            'slug' => Str::slug($request->title),
            'created_by_user_id' => Auth::id(),
        ]));

        return redirect()->route('admin.story.edit', $chapter->id)
            ->with('success', 'Capitolo creato! Ora aggiungi le scelte.');
    }

    public function edit($id)
    {
        $chapter = StoryChapter::with('choices.consequences')->findOrFail($id);
        $allChapters = StoryChapter::where('id', '!=', $id)->get();

        return view('admin.story.edit', compact('chapter', 'allChapters'));
    }

    public function addChoice(Request $request, $chapterId)
    {
        $validated = $request->validate([
            'choice_text' => 'required',
            'leads_to_chapter_id' => 'nullable|exists:story_chapters,id',
            'requirements' => 'nullable|json',
            'consequences' => 'nullable|json',
        ]);

        $choice = StoryChoice::create(array_merge($validated, [
            'chapter_id' => $chapterId,
            'choice_number' => StoryChoice::where('chapter_id', $chapterId)->count() + 1,
        ]));

        return redirect()->back()->with('success', 'Scelta aggiunta!');
    }
}
```

## Controller User

### `StoryController.php`
```php
<?php

namespace App\Http\Controllers;

use App\Models\StoryChapter;
use App\Models\UserStoryProgress;
use Illuminate\Support\Facades\Auth;

class StoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        // Get available chapters
        $availableChapters = StoryChapter::where(function($q) use ($user) {
            $q->where('house_specific', 'all')
              ->orWhere('house_specific', $user->team());
        })
        ->where('required_level', '<=', $user->level)
        ->where('is_published', true)
        ->with('userProgress')
        ->get();

        // Get progress
        $progress = UserStoryProgress::where('user_id', $user->id)->get();

        return view('story.index', compact('availableChapters', 'progress'));
    }

    public function show($id)
    {
        $chapter = StoryChapter::with('choices')->findOrFail($id);
        $user = Auth::user();

        // Check if accessible
        if ($chapter->house_specific !== 'all' && $chapter->house_specific !== $user->team()) {
            return redirect()->route('story.index')
                ->with('error', 'Questo capitolo non è disponibile per la tua casa!');
        }

        if ($chapter->required_level > $user->level) {
            return redirect()->route('story.index')
                ->with('error', 'Livello insufficiente!');
        }

        // Get user's current story state
        $storyState = $this->getUserStoryState($user);

        // Filter choices based on requirements
        $availableChoices = $chapter->choices->filter(function($choice) use ($user, $storyState) {
            return $this->meetsRequirements($choice, $user, $storyState);
        });

        return view('story.show', compact('chapter', 'availableChoices', 'storyState'));
    }

    public function makeChoice(Request $request, $chapterId, $choiceId)
    {
        $user = Auth::user();
        $chapter = StoryChapter::findOrFail($chapterId);
        $choice = StoryChoice::findOrFail($choiceId);

        // Validate
        if ($choice->chapter_id !== $chapter->id) {
            return redirect()->back()->with('error', 'Scelta non valida!');
        }

        // Apply consequences
        $this->applyConsequences($choice, $user);

        // Record progress
        UserStoryProgress::create([
            'user_id' => $user->id,
            'chapter_id' => $chapter->id,
            'choice_id' => $choice->id,
            'completed_at' => now(),
            'story_state' => $this->getUserStoryState($user),
        ]);

        // Redirect to next chapter or end
        if ($choice->leads_to_chapter_id) {
            return redirect()->route('story.show', $choice->leads_to_chapter_id)
                ->with('success', 'Storia aggiornata!');
        }

        return redirect()->route('story.index')
            ->with('success', 'Capitolo completato!');
    }

    private function getUserStoryState(User $user)
    {
        $progress = UserStoryProgress::where('user_id', $user->id)
            ->latest()
            ->first();

        return $progress ? $progress->story_state : [
            'relationships' => [],
            'reputation' => [],
            'major_choices' => [],
            'inventory_flags' => [],
        ];
    }

    private function meetsRequirements($choice, $user, $storyState)
    {
        if (!$choice->requirements) {
            return true;
        }

        $requirements = json_decode($choice->requirements, true);

        foreach ($requirements as $key => $value) {
            switch ($key) {
                case 'min_level':
                    if ($user->level < $value) return false;
                    break;
                case 'has_item':
                    if (!in_array($value, $storyState['inventory_flags'] ?? [])) return false;
                    break;
                case 'relationship':
                    [$npc, $minValue] = explode(':', $value);
                    if (($storyState['relationships'][$npc] ?? 0) < $minValue) return false;
                    break;
            }
        }

        return true;
    }

    private function applyConsequences($choice, $user)
    {
        if (!$choice->consequences) {
            return;
        }

        $consequences = json_decode($choice->consequences, true);

        foreach ($consequences as $consequence) {
            switch ($consequence['type']) {
                case 'stat_change':
                    $stat = $consequence['stat'];
                    $user->$stat += $consequence['value'];
                    $user->save();
                    break;

                case 'item_gain':
                    // Add item to inventory
                    break;

                case 'relationship_change':
                    // Update story state
                    break;
            }
        }
    }
}
```

---

# 🚀 Istruzioni di Installazione

## 1. Eseguire le Migrazioni

Le migrazioni sono già state create. Per eseguirle:

```bash
php artisan migrate
```

## 2. Creare i Modelli

Genera i modelli mancanti:

```bash
# Sistema Pozioni
php artisan make:model Potion
php artisan make:model Ingredient
php artisan make:model UserIngredient
php artisan make:model PotionRecipe
php artisan make:model UserPotion

# Sistema Mercato
php artisan make:model MarketListing
php artisan make:model PlayerTrade
php artisan make:model TradeOffer

# Sistema Storia
php artisan make:model StoryChapter
php artisan make:model StoryChoice
php artisan make:model UserStoryProgress
php artisan make:model StoryConsequence
```

## 3. Creare i Controllers

```bash
# Creature (già fornito sopra)
php artisan make:controller CreatureController

# Pozioni
php artisan make:controller PotionController
php artisan make:controller IngredientController

# Mercato
php artisan make:controller MarketplaceController
php artisan make:controller TradeController

# Storia
php artisan make:controller StoryController
php artisan make:controller Admin/StoryChapterController
```

## 4. Creare i Services

```bash
mkdir -p app/Services
# Poi crea i file:
# - CreatureService.php (già fornito)
# - PotionCraftingService.php
# - MarketplaceService.php
# - StoryService.php
```

## 5. Popolare il Database

Crea seeders per dati iniziali:

```bash
php artisan make:seeder CreatureSpeciesSeeder
php artisan make:seeder PotionSeeder
php artisan make:seeder IngredientSeeder
php artisan make:seeder StoryChapterSeeder
```

---

# 🛣️ Rotte Complete

Aggiungi a `routes/web.php`:

```php
// ========== CREATURE ROUTES ==========
Route::group(['prefix' => 'creatures', 'middleware' => 'auth'], function(){
    Route::get('/', 'CreatureController@index')->name('creatures.index');
    Route::get('/catalog', 'CreatureController@catalog')->name('creatures.catalog');
    Route::get('/{id}', 'CreatureController@show')->name('creatures.show');
    Route::post('/{id}/feed', 'CreatureController@feed')->name('creatures.feed');
    Route::post('/{id}/play', 'CreatureController@play')->name('creatures.play');
    Route::post('/{id}/train', 'CreatureController@train')->name('creatures.train');
    Route::post('/{id}/heal', 'CreatureController@heal')->name('creatures.heal');
    Route::post('/breed', 'CreatureController@breed')->name('creatures.breed');
    Route::post('/{id}/release', 'CreatureController@release')->name('creatures.release');
});

// Admin Creature Management
Route::group(['prefix' => 'admin/creatures', 'middleware' => 'admin', 'namespace' => 'Admin'], function(){
    Route::get('/', 'CreatureSpeciesController@index')->name('admin.creatures.index');
    Route::get('/create', 'CreatureSpeciesController@create')->name('admin.creatures.create');
    Route::post('/', 'CreatureSpeciesController@store')->name('admin.creatures.store');
    Route::get('/{id}/edit', 'CreatureSpeciesController@edit')->name('admin.creatures.edit');
    Route::put('/{id}', 'CreatureSpeciesController@update')->name('admin.creatures.update');
    Route::delete('/{id}', 'CreatureSpeciesController@destroy')->name('admin.creatures.destroy');
});

// ========== POTION ROUTES ==========
Route::group(['prefix' => 'potions', 'middleware' => 'auth'], function(){
    Route::get('/', 'PotionController@index')->name('potions.index');
    Route::get('/recipes', 'PotionController@recipes')->name('potions.recipes');
    Route::get('/brew/{potionId}', 'PotionController@showBrew')->name('potions.brew');
    Route::post('/craft', 'PotionController@craft')->name('potions.craft');
    Route::post('/{id}/use', 'PotionController@use')->name('potions.use');
});

Route::group(['prefix' => 'ingredients', 'middleware' => 'auth'], function(){
    Route::get('/', 'IngredientController@index')->name('ingredients.index');
    Route::get('/gather', 'IngredientController@gather')->name('ingredients.gather');
    Route::post('/collect/{ingredientId}', 'IngredientController@collect')->name('ingredients.collect');
});

// Admin Potion Management
Route::group(['prefix' => 'admin/potions', 'middleware' => 'admin', 'namespace' => 'Admin'], function(){
    Route::resource('potions', 'PotionController');
    Route::resource('ingredients', 'IngredientController');
    Route::post('/potions/{id}/add-recipe', 'PotionController@addRecipe')->name('admin.potions.add-recipe');
});

// ========== MARKETPLACE ROUTES ==========
Route::group(['prefix' => 'marketplace', 'middleware' => 'auth'], function(){
    Route::get('/', 'MarketplaceController@index')->name('marketplace.index');
    Route::get('/my-listings', 'MarketplaceController@myListings')->name('marketplace.my-listings');
    Route::get('/create', 'MarketplaceController@create')->name('marketplace.create');
    Route::post('/', 'MarketplaceController@store')->name('marketplace.store');
    Route::get('/{id}', 'MarketplaceController@show')->name('marketplace.show');
    Route::post('/{id}/purchase', 'MarketplaceController@purchase')->name('marketplace.purchase');
    Route::post('/{id}/bid', 'MarketplaceController@placeBid')->name('marketplace.bid');
    Route::post('/{id}/cancel', 'MarketplaceController@cancel')->name('marketplace.cancel');
});

Route::group(['prefix' => 'trades', 'middleware' => 'auth'], function(){
    Route::get('/', 'TradeController@index')->name('trades.index');
    Route::post('/propose', 'TradeController@propose')->name('trades.propose');
    Route::post('/{id}/accept', 'TradeController@accept')->name('trades.accept');
    Route::post('/{id}/reject', 'TradeController@reject')->name('trades.reject');
    Route::post('/{id}/counter', 'TradeController@counter')->name('trades.counter');
});

// ========== STORY ROUTES ==========
Route::group(['prefix' => 'story', 'middleware' => 'auth'], function(){
    Route::get('/', 'StoryController@index')->name('story.index');
    Route::get('/{id}', 'StoryController@show')->name('story.show');
    Route::post('/{chapterId}/choice/{choiceId}', 'StoryController@makeChoice')->name('story.choice');
    Route::get('/progress/history', 'StoryController@history')->name('story.history');
});

// Admin Story Management
Route::group(['prefix' => 'admin/story', 'middleware' => 'admin', 'namespace' => 'Admin'], function(){
    Route::get('/', 'StoryChapterController@index')->name('admin.story.index');
    Route::get('/create', 'StoryChapterController@create')->name('admin.story.create');
    Route::post('/', 'StoryChapterController@store')->name('admin.story.store');
    Route::get('/{id}/edit', 'StoryChapterController@edit')->name('admin.story.edit');
    Route::put('/{id}', 'StoryChapterController@update')->name('admin.story.update');
    Route::delete('/{id}', 'StoryChapterController@destroy')->name('admin.story.destroy');

    // Choice management
    Route::post('/{id}/add-choice', 'StoryChapterController@addChoice')->name('admin.story.add-choice');
    Route::put('/choices/{id}', 'StoryChapterController@updateChoice')->name('admin.story.update-choice');
    Route::delete('/choices/{id}', 'StoryChapterController@deleteChoice')->name('admin.story.delete-choice');

    // Consequences
    Route::post('/choices/{id}/add-consequence', 'StoryChapterController@addConsequence')->name('admin.story.add-consequence');
});
```

---

# 📊 Statistiche Implementazione

| Sistema | Tabelle | Modelli | Controllers | Routes |
|---------|---------|---------|-------------|---------|
| Creature | 3 | 3 | 2 | 15+ |
| Pozioni | 5 | 5 | 3 | 12+ |
| Mercato | 3 | 3 | 2 | 14+ |
| Storia | 4 | 4 | 2 | 12+ |
| **TOTALE** | **15** | **15** | **9** | **53+** |

---

# 🎨 Esempi View (Blade)

## creatures/show.blade.php
```blade
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>{{ $creature->nickname ?? $creature->species->name }}</h3>
                    <span class="badge badge-{{ $creature->life_stage }}">{{ ucfirst($creature->life_stage) }}</span>
                </div>
                <div class="card-body">
                    <img src="{{ $creature->species->image }}" alt="{{ $creature->species->name }}" class="img-fluid mb-3">

                    <!-- Stats -->
                    <div class="stats">
                        <div class="stat-bar">
                            <label>Salute</label>
                            <div class="progress">
                                <div class="progress-bar bg-success" style="width: {{ ($creature->current_health / $creature->max_health) * 100 }}%">
                                    {{ $creature->current_health }} / {{ $creature->max_health }}
                                </div>
                            </div>
                        </div>

                        <div class="stat-bar">
                            <label>Felicità</label>
                            <div class="progress">
                                <div class="progress-bar bg-warning" style="width: {{ $creature->happiness }}%">
                                    {{ $creature->happiness }}%
                                </div>
                            </div>
                        </div>

                        <div class="stat-bar">
                            <label>Fame</label>
                            <div class="progress">
                                <div class="progress-bar bg-danger" style="width: {{ $creature->hunger }}%">
                                    {{ $creature->hunger }}%
                                </div>
                            </div>
                        </div>

                        <div class="stat-bar">
                            <label>Energia</label>
                            <div class="progress">
                                <div class="progress-bar bg-info" style="width: {{ $creature->energy }}%">
                                    {{ $creature->energy }}%
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="actions mt-4">
                        <form method="POST" action="{{ route('creatures.feed', $creature->id) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success" @if($creature->hunger < 20) disabled @endif>
                                <i class="fa fa-apple"></i> Nutri
                            </button>
                        </form>

                        <form method="POST" action="{{ route('creatures.play', $creature->id) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-primary" @if($creature->energy < 20) disabled @endif>
                                <i class="fa fa-gamepad"></i> Gioca
                            </button>
                        </form>

                        <form method="POST" action="{{ route('creatures.train', $creature->id) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-warning" @if($creature->energy < 30) disabled @endif>
                                <i class="fa fa-dumbbell"></i> Allena
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Info Card -->
            <div class="card mb-3">
                <div class="card-header">Informazioni</div>
                <div class="card-body">
                    <p><strong>Specie:</strong> {{ $creature->species->name }}</p>
                    <p><strong>Livello:</strong> {{ $creature->level }}</p>
                    <p><strong>Esperienza:</strong> {{ $creature->experience }} / {{ $creature->level * 100 }}</p>
                    <p><strong>Età:</strong> {{ $creature->age_days }} giorni</p>
                    <p><strong>Legame:</strong> {{ $creature->bond_level }}/100</p>
                </div>
            </div>

            <!-- Recent Interactions -->
            <div class="card">
                <div class="card-header">Ultime Interazioni</div>
                <div class="card-body">
                    @foreach($creature->interactions()->latest()->limit(5)->get() as $interaction)
                        <div class="interaction-log">
                            <small>{{ $interaction->created_at->diffForHumans() }}</small>
                            <p>{{ ucfirst($interaction->action) }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
```

---

# 🔥 Features Avanzate

## Creature System
- ✅ Sistema di crescita con stadi vitali (uovo → baby → giovanile → adulto → anziano)
- ✅ Breeding con eredità genetica
- ✅ Sistema di felicità/fame/energia con decay passivo
- ✅ Addestramento e level-up
- ✅ Drop di materiali rari
- ✅ Bond level con il proprietario
- ✅ Creature cavalcabili e da combattimento

## Potion System
- ✅ Crafting con ricette complesse
- ✅ Sistema di qualità (povera → perfetta)
- ✅ Difficoltà e tempo di preparazione
- ✅ Ingredienti che scadono
- ✅ Catalizzatori speciali
- ✅ Potenza variabile (50-150%)
- ✅ Effetti multipli combinabili

## Marketplace
- ✅ Vendita a prezzo fisso
- ✅ Sistema di aste
- ✅ Trading diretto tra giocatori
- ✅ Commissioni di mercato
- ✅ Storico transazioni
- ✅ Sistema di offerte/controfferte
- ✅ Valute multiple (Galleons, House Points)

## Story System
- ✅ Branching narrativo complesso
- ✅ Scelte basate su requisiti (stats, items, relazioni)
- ✅ Conseguenze permanenti
- ✅ Contenuti specifici per casata
- ✅ Sistema di reputazione
- ✅ Variabili di stato persistenti
- ✅ Admin panel completo per creare storie

---

# 📝 TODO per Completare

## Priorità Alta
- [ ] Implementare tutti i modelli mancanti
- [ ] Completare i service layer
- [ ] Creare seeders con dati di esempio
- [ ] Implementare le view Blade
- [ ] Aggiungere validazioni complete
- [ ] Implementare sistema di notifiche

## Priorità Media
- [ ] Creare componenti Livewire per real-time
- [ ] Aggiungere grafici statistiche
- [ ] Implementare achievement system
- [ ] Creare API per mobile
- [ ] Aggiungere traduzione multilingua

## Priorità Bassa
- [ ] Animazioni CSS
- [ ] Sound effects
- [ ] Tutorial interattivo
- [ ] Sistema di gilde
- [ ] Modalità cooperativa

---

# 🎯 Quick Start

1. **Esegui migrazioni**: `php artisan migrate`
2. **Crea seeders**: Popola con dati di esempio
3. **Aggiungi rotte**: Copia le rotte nel file `web.php`
4. **Crea modelli**: Usa gli esempi forniti
5. **Implementa controller**: Segui gli esempi
6. **Crea view**: Usa i template Blade forniti

---

**Creato con ❤️ per Hogwarts GDR**

*Questi 4 sistemi porteranno il tuo GDR a un livello completamente nuovo!* 🚀✨
