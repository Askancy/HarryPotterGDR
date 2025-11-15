<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quest;
use App\Models\User;
use App\Models\Creature;
use App\Models\Objects;
use App\Models\Chat;
use App\Models\Team;

class QuestGeneratorController extends Controller
{
    /**
     * Quest templates con placeholder dinamici
     */
    private $questTemplates = [
        'collection' => [
            'names' => [
                'Raccolta di Ingredienti per {professor}',
                'La Ricerca di {item_plural}',
                'Missione di Raccolta: {item_plural}',
                '{professor} ha Bisogno di Te',
            ],
            'descriptions' => [
                '{professor} ha bisogno di {quantity} {item_plural} per {reason}. Esplora {location} e trova gli oggetti richiesti.',
                'Il Professor {professor} ti ha assegnato un compito importante: raccogliere {quantity} {item_plural} da {location}. Questi oggetti sono essenziali per {reason}.',
                'Una richiesta urgente da {professor}: trova {quantity} {item_plural} nelle vicinanze di {location}. Saranno usati per {reason}.',
            ],
            'objectives' => [
                'Raccogli {quantity} {item_plural}',
                'Consegna gli oggetti al Professor {professor}',
            ],
        ],
        'combat' => [
            'names' => [
                'Difesa contro {creature_plural}',
                'Caccia alle {creature_plural}',
                'La Minaccia delle {creature_plural}',
                'Proteggere {location} dalle {creature_plural}',
            ],
            'descriptions' => [
                'Un gruppo di {creature_plural} ha invaso {location}! Sconfiggine {quantity} per proteggere gli studenti.',
                '{creature_plural} pericolose sono state avvistate vicino a {location}. Il Preside ti chiede di sconfiggerne {quantity} prima che facciano danni.',
                'Gli abitanti di {location} sono terrorizzati dalle {creature_plural}. Elimina {quantity} di queste creature per riportare la pace.',
            ],
            'objectives' => [
                'Sconfiggi {quantity} {creature_plural}',
                'Torna al castello per la ricompensa',
            ],
        ],
        'exploration' => [
            'names' => [
                'Esplorazione di {location}',
                'I Segreti di {location}',
                'Missione di Ricognizione: {location}',
                'Alla Scoperta di {location}',
            ],
            'descriptions' => [
                'Dumbledore ti ha chiesto di esplorare {location} e scoprire i suoi segreti. Fai attenzione, potrebbero esserci pericoli nascosti.',
                'Una mappa antica indica che {location} nasconde qualcosa di prezioso. Esplora l\'area e riferisci le tue scoperte.',
                '{location} è sempre stato un luogo misterioso. È tempo di scoprire cosa si cela al suo interno.',
            ],
            'objectives' => [
                'Esplora completamente {location}',
                'Trova {quantity} indizi nascosti',
                'Riferisci le tue scoperte al Preside',
            ],
        ],
        'delivery' => [
            'names' => [
                'Consegna Urgente per {professor}',
                'Il Pacco Misterioso',
                'Messaggero di Hogwarts',
                'Consegna a {location}',
            ],
            'descriptions' => [
                '{professor} ti ha affidato un pacco importante che deve essere consegnato a {location}. Non aprirlo e consegnalo in fretta!',
                'Un messaggio urgente deve raggiungere {location}. {professor} conta su di te per questa delicata missione.',
                'Porta questo pacco sigillato a {location}. Il Professor {professor} dice che è della massima importanza.',
            ],
            'objectives' => [
                'Prendi il pacco dal Professor {professor}',
                'Consegna il pacco a {location}',
                'Torna per ricevere la ricompensa',
            ],
        ],
        'investigation' => [
            'names' => [
                'Il Mistero di {location}',
                'Indagine: {event}',
                'Scopri la Verità su {event}',
                'Caso Aperto: {event}',
            ],
            'descriptions' => [
                'Strani eventi stanno accadendo a {location}. Indaga e scopri cosa sta succedendo. Raccogli {quantity} prove.',
                '{event} ha scosso il castello. Il Professor {professor} ti chiede di investigare e trovare {quantity} indizi.',
                'C\'è qualcosa di strano a {location}. Conduci un\'indagine approfondita e raccogli almeno {quantity} prove.',
            ],
            'objectives' => [
                'Raccogli {quantity} indizi a {location}',
                'Interroga {quantity} testimoni',
                'Riferisci i risultati al Professor {professor}',
            ],
        ],
        'house_pride' => [
            'names' => [
                'Onore di {house}',
                'Per la Gloria di {house}',
                'Missione della Casa {house}',
                '{house} ha Bisogno di Te',
            ],
            'descriptions' => [
                'La tua casata, {house}, ha bisogno del tuo aiuto! Completa questa missione per portare onore e punti alla tua casa.',
                'Il Capocasa di {house} ti ha scelto per una missione speciale. Il successo porterà grande prestigio alla tua casata.',
                'Rappresenta {house} con orgoglio in questa importante missione. I tuoi compagni di casa contano su di te!',
            ],
            'objectives' => [
                'Completa la sfida di {house}',
                'Ottieni {points} punti per la tua casata',
                'Dimostra il valore di {house}',
            ],
        ],
    ];

    /**
     * Dati di contesto per generare le quest
     */
    private $contextData = [
        'professors' => [
            'Silente', 'McGranitt', 'Piton', 'Vitious', 'Hagrid',
            'Lumacorno', 'Vector', 'Sinistra', 'Sprite'
        ],
        'reasons' => [
            'una pozione complessa',
            'un esperimento importante',
            'curare un\'infermeria piena',
            'una lezione speciale',
            'preservare ingredienti rari',
            'proteggere il castello',
            'un rituale antico',
            'aiutare un collega professore'
        ],
        'events' => [
            'la sparizione di oggetti',
            'rumori strani di notte',
            'la comparsa di creature insolite',
            'un incantesimo che non funziona',
            'studenti che si comportano stranamente',
            'luci misteriose',
            'un messaggio cifrato',
        ],
    ];

    /**
     * Genera una nuova quest basata sul livello e preferenze del giocatore
     */
    public function generateQuest(User $user)
    {
        $questType = $this->selectQuestType($user);
        $template = $this->questTemplates[$questType];

        $questData = $this->fillTemplate($template, $user, $questType);

        return $questData;
    }

    /**
     * Seleziona il tipo di quest basato sul livello del giocatore
     */
    private function selectQuestType(User $user)
    {
        $level = $user->level ?? 1;

        // Quest più semplici per livelli bassi
        if ($level < 5) {
            $types = ['collection', 'delivery', 'exploration'];
        } elseif ($level < 10) {
            $types = ['collection', 'delivery', 'exploration', 'combat', 'investigation'];
        } else {
            $types = array_keys($this->questTemplates);
        }

        return $types[array_rand($types)];
    }

    /**
     * Riempie il template con dati dinamici
     */
    private function fillTemplate($template, User $user, $questType)
    {
        $level = $user->level ?? 1;
        $house = Team::find($user->team);

        // Seleziona componenti casuali
        $name = $template['names'][array_rand($template['names'])];
        $description = $template['descriptions'][array_rand($template['descriptions'])];
        $objectives = $template['objectives'];

        // Genera parametri dinamici
        $replacements = [
            '{professor}' => $this->contextData['professors'][array_rand($this->contextData['professors'])],
            '{reason}' => $this->contextData['reasons'][array_rand($this->contextData['reasons'])],
            '{event}' => $this->contextData['events'][array_rand($this->contextData['events'])],
            '{quantity}' => $this->getQuantityByLevel($level, $questType),
            '{location}' => $this->getRandomLocation(),
            '{item_plural}' => $this->getRandomItem(true),
            '{item_singular}' => $this->getRandomItem(false),
            '{creature_plural}' => $this->getRandomCreature(true),
            '{creature_singular}' => $this->getRandomCreature(false),
            '{house}' => $house ? $house->name : 'la tua casata',
            '{points}' => rand(10, 30),
        ];

        // Sostituisci placeholder
        foreach ($replacements as $placeholder => $value) {
            $name = str_replace($placeholder, $value, $name);
            $description = str_replace($placeholder, $value, $description);
            foreach ($objectives as &$objective) {
                $objective = str_replace($placeholder, $value, $objective);
            }
        }

        // Calcola ricompense
        $rewards = $this->calculateRewards($level, $questType);

        return [
            'name' => $name,
            'description' => $description,
            'objectives' => $objectives,
            'type' => $questType,
            'difficulty' => $this->getDifficulty($level),
            'exp_reward' => $rewards['exp'],
            'money_reward' => $rewards['money'],
            'item_reward' => $rewards['item'],
            'recommended_level' => $level,
            'time_limit' => $this->getTimeLimit($questType),
        ];
    }

    /**
     * Calcola la quantità richiesta basata sul livello
     */
    private function getQuantityByLevel($level, $questType)
    {
        $base = [
            'collection' => 3,
            'combat' => 5,
            'exploration' => 3,
            'delivery' => 1,
            'investigation' => 3,
            'house_pride' => 1,
        ];

        $baseQty = $base[$questType] ?? 3;
        $multiplier = 1 + floor($level / 5) * 0.5;

        return max(1, round($baseQty * $multiplier));
    }

    /**
     * Ottiene una location casuale dal database
     */
    private function getRandomLocation()
    {
        $locations = Chat::pluck('name')->toArray();

        if (empty($locations)) {
            $locations = [
                'la Foresta Proibita',
                'il Lago Nero',
                'le Serre',
                'la Torre di Astronomia',
                'i Sotterranei',
                'la Biblioteca',
                'il Cortile',
                'Hogsmeade',
            ];
        }

        return $locations[array_rand($locations)];
    }

    /**
     * Ottiene un oggetto casuale
     */
    private function getRandomItem($plural = false)
    {
        $items = [
            'radici di mandragora',
            'piume di ippogrifo',
            'occhi di rospo',
            'code di ratto',
            'erbe aromatiche',
            'cristalli magici',
            'pergamene antiche',
            'pozioni curative',
        ];

        return $items[array_rand($items)];
    }

    /**
     * Ottiene una creatura casuale
     */
    private function getRandomCreature($plural = false)
    {
        $creatures = Creature::pluck('name')->toArray();

        if (empty($creatures)) {
            $creatures = $plural ? [
                'Folletti',
                'Pixie',
                'Gnomi',
                'Ragni Giganti',
                'Acromantule',
                'Imp',
                'Grindylow',
            ] : [
                'Folletto',
                'Pixie',
                'Gnomo',
                'Ragno Gigante',
                'Acromantula',
                'Imp',
                'Grindylow',
            ];
        } else {
            // Pluralizza i nomi delle creature
            $creatures = $plural ? array_map(function($name) {
                return $name . (substr($name, -1) === 'a' ? 'e' : 'i');
            }, $creatures) : $creatures;
        }

        return $creatures[array_rand($creatures)];
    }

    /**
     * Calcola le ricompense
     */
    private function calculateRewards($level, $questType)
    {
        $difficultyMultiplier = [
            'delivery' => 0.8,
            'collection' => 1.0,
            'exploration' => 1.2,
            'investigation' => 1.3,
            'combat' => 1.5,
            'house_pride' => 1.4,
        ];

        $multiplier = $difficultyMultiplier[$questType] ?? 1.0;

        $baseExp = 50;
        $baseMoney = 20;

        return [
            'exp' => round(($baseExp + ($level * 10)) * $multiplier),
            'money' => round(($baseMoney + ($level * 5)) * $multiplier),
            'item' => rand(1, 100) > 70 ? $this->getRandomRewardItem($level) : null,
        ];
    }

    /**
     * Ottiene un oggetto ricompensa casuale
     */
    private function getRandomRewardItem($level)
    {
        $items = Objects::where('price', '<=', $level * 50)->get();

        if ($items->isEmpty()) {
            return null;
        }

        return $items->random()->id;
    }

    /**
     * Calcola la difficoltà (1-5 stelle)
     */
    private function getDifficulty($level)
    {
        if ($level < 5) return 1;
        if ($level < 10) return 2;
        if ($level < 15) return 3;
        if ($level < 20) return 4;
        return 5;
    }

    /**
     * Determina il limite di tempo in ore
     */
    private function getTimeLimit($questType)
    {
        $limits = [
            'delivery' => 2,
            'collection' => 6,
            'exploration' => 12,
            'investigation' => 24,
            'combat' => 8,
            'house_pride' => 48,
        ];

        return $limits[$questType] ?? 24;
    }

    /**
     * View per il generatore di quest
     */
    public function index()
    {
        return view('front.quest-generator.index');
    }

    /**
     * Genera e salva una nuova quest
     */
    public function generate(Request $request)
    {
        $user = auth()->user();

        // Controlla se l'utente può generare una nuova quest
        $dailyLimit = 3;
        $todayQuests = Quest::where('created_by', $user->id)
            ->whereDate('created_at', today())
            ->count();

        if ($todayQuests >= $dailyLimit) {
            return back()->with('error', 'Hai raggiunto il limite giornaliero di quest generate!');
        }

        $questData = $this->generateQuest($user);

        // Salva la quest nel database
        $quest = Quest::create([
            'name' => $questData['name'],
            'description' => $questData['description'],
            'type' => $questData['type'],
            'difficulty' => $questData['difficulty'],
            'exp_reward' => $questData['exp_reward'],
            'money_reward' => $questData['money_reward'],
            'item_reward' => $questData['item_reward'],
            'status' => 1,
            'privacy' => 0, // Privata per l'utente
            'created_by' => $user->id,
            'time_limit' => now()->addHours($questData['time_limit']),
        ]);

        return redirect()->route('quest.show', $quest->id)
            ->with('success', 'Nuova quest generata con successo!');
    }
}
