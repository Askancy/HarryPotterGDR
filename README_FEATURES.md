# 🧙‍♂️ Hogwarts GDR - Guida Completa Funzionalità

## 📋 Indice

1. [Sistema Inventario](#-sistema-inventario)
2. [Generatore Quest Generative](#-generatore-quest-generative)
3. [Sistema Incantesimi](#-sistema-incantesimi)
4. [Arena di Combattimento](#-arena-di-combattimento)
5. [Sistema Achievement](#-sistema-achievement)
6. [Integrazione e Setup](#-integrazione-e-setup)

---

## 🎒 Sistema Inventario

### Panoramica
Un sistema completo di gestione inventario con equipaggiamento, rarità oggetti e statistiche.

### Caratteristiche Principali

#### 📦 Gestione Oggetti
- **Filtri per Tipologia**: Bacchette, Libri, Pozioni, Equipaggiamento
- **Sistema di Rarità**:
  - Common (Comune) - Grigio
  - Uncommon (Non Comune) - Verde
  - Rare (Raro) - Blu
  - Epic (Epico) - Viola
  - Legendary (Leggendario) - Oro

#### ⚔️ Statistiche Oggetti
- **Attacco (ATK)**: Aumenta il danno inflitto
- **Difesa (DEF)**: Riduce i danni subiti
- **Capacità**: Limite slot inventario (default: 50)

#### 🎭 Equipaggiamento Attivo
Slot dedicati per:
- **Bacchetta**: Arma principale
- **Veste**: Protezione base
- **Accessori** (x2): Bonus aggiuntivi
- **Libro**: Conoscenze magiche
- **Scudo**: Difesa extra

#### 🔧 Funzionalità
- **Equipaggia**: Indossa oggetti per ottenere bonus
- **Usa**: Consuma pozioni e oggetti usa e getta
- **Vendi**: Vendi oggetti al 50% del prezzo originale
- **Elimina**: Rimuovi oggetti dall'inventario

### File Implementati
```
resources/views/front/inventory/index.blade.php
```

### Utilizzo
```php
Route::get('/inventory', 'InventoryController@index')->name('inventory.index');
```

### Controller da Implementare
```php
class InventoryController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $inventory = Inventory::where('id_user', $user->id)
            ->with('object')
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->object->name,
                    'description' => $item->object->description,
                    'type' => $item->object->type,
                    'attack' => $item->object->attack ?? 0,
                    'defense' => $item->object->defense ?? 0,
                    'price' => $item->object->price,
                    'rarity' => $item->object->rarity ?? 'common',
                    'quantity' => $item->quantity,
                    'image' => $item->object->image,
                ];
            });

        return view('front.inventory.index', [
            'inventory' => $inventory,
            'maxSlots' => 50
        ]);
    }
}
```

---

## 🎲 Generatore Quest Generative

### Panoramica
Sistema AI-like che genera automaticamente quest personalizzate basate su:
- Livello del giocatore
- Casata di appartenenza
- Quest completate
- Progressione generale

### Tipologie di Quest

#### 📦 Raccolta (Collection)
- Raccogli ingredienti per i professori
- Difficoltà: Media
- Esempio: *"Il Professor Piton ha bisogno di 5 radici di mandragora per una pozione complessa"*

#### ⚔️ Combattimento (Combat)
- Affronta creature magiche
- Difficoltà: Alta
- Esempio: *"Un gruppo di Acromantule ha invaso la Foresta Proibita! Sconfiggine 8"*

#### 🗺️ Esplorazione (Exploration)
- Scopri luoghi nascosti
- Difficoltà: Media
- Esempio: *"Dumbledore ti ha chiesto di esplorare la Torre di Astronomia e scoprire i suoi segreti"*

#### 📮 Consegna (Delivery)
- Trasporta pacchi e messaggi
- Difficoltà: Bassa
- Esempio: *"Consegna questo pacco sigillato a Hogsmeade per il Professor McGranitt"*

#### 🔍 Investigazione (Investigation)
- Risolvi misteri
- Difficoltà: Media-Alta
- Esempio: *"Strani rumori di notte terrorizzano gli studenti. Raccogli 5 indizi per scoprire la causa"*

#### 🏆 Onore della Casata (House Pride)
- Missioni speciali per la casata
- Difficoltà: Alta
- Esempio: *"Rappresenta Grifondoro in questa sfida e porta onore alla tua casa!"*

### Sistema di Ricompense

#### Calcolo Automatico
```javascript
baseExp = 50 + (livello * 10)
baseMoney = 20 + (livello * 5)

// Moltiplicatori per difficoltà:
delivery: x0.8
collection: x1.0
exploration: x1.2
investigation: x1.3
combat: x1.5
house_pride: x1.4
```

#### Ricompense Bonus
- 30% di probabilità di ottenere un oggetto casuale
- Oggetto proporzionato al livello del giocatore

### Limitazioni
- **3 quest al giorno** per utente
- Reset giornaliero automatico
- Previene farming eccessivo

### File Implementati
```
app/Http/Controllers/QuestGeneratorController.php
resources/views/front/quest-generator/index.blade.php
```

### Templates Quest
Il sistema usa oltre **20 template diversi** con placeholder dinamici:
- `{professor}`: Professore casuale (Silente, Piton, McGranitt, etc.)
- `{location}`: Location dal database o predefinite
- `{creature_plural}`: Creature dal database
- `{quantity}`: Calcolato sul livello
- `{item_plural}`: Oggetti casuali
- `{house}`: Casata del giocatore
- `{reason}`: Motivo casuale
- `{event}`: Evento misterioso

### Route
```php
Route::get('/quest-generator', 'QuestGeneratorController@index')->name('quest-generator.index');
Route::post('/quest-generator/generate', 'QuestGeneratorController@generate')->name('quest-generator.generate');
```

---

## ✨ Sistema Incantesimi

### Panoramica
Grimorio completo con **27 incantesimi** iconici di Harry Potter divisi per categoria.

### Categorie Incantesimi

#### ⚔️ Attacco (Attack)
| Incantesimo | Formula | Potenza | Mana | Livello |
|------------|---------|---------|------|---------|
| Expelliarmus | *Expelliarmus!* | 30 | 15 | 1 |
| Stupefy | *Stupefy!* | 40 | 20 | 3 |
| Petrificus Totalus | *Petrificus Totalus!* | 35 | 25 | 5 |
| Incendio | *Incendio!* | 50 | 30 | 7 |
| Bombarda | *Bombarda!* | 70 | 45 | 12 |
| Confringo | *Confringo!* | 65 | 40 | 10 |

#### 🛡️ Difesa (Defense)
| Incantesimo | Formula | Potenza | Mana | Livello |
|------------|---------|---------|------|---------|
| Protego | *Protego!* | 40 | 20 | 2 |
| Protego Maxima | *Protego Maxima!* | 60 | 35 | 8 |
| Expecto Patronum | *Expecto Patronum!* | 80 | 50 | 15 |

#### ❤️ Cura (Healing)
| Incantesimo | Formula | Potenza | Mana | Livello |
|------------|---------|---------|------|---------|
| Episkey | *Episkey!* | 30 | 15 | 4 |
| Vulnera Sanentur | *Vulnera Sanentur!* | 60 | 40 | 10 |
| Rennervate | *Rennervate!* | 25 | 20 | 5 |

#### 🔧 Utilità (Utility)
- Lumos / Nox (Luce)
- Alohomora (Apre serrature)
- Accio (Richiama oggetti)
- Wingardium Leviosa (Levitazione)
- Revelio (Rivela nascosto)
- Reparo (Ripara oggetti)

#### 🎭 Incantesimi (Charm)
- Obliviate (Cancella memoria)
- Confundo (Confonde)
- Riddikulus (Anti-Molliccio)

#### 🔮 Trasfigurazione (Transfiguration)
- Vera Verto (Animale → Calice)
- Avifors (Oggetto → Uccello)

### ⚫ Maledizioni Senza Perdono

**ATTENZIONE**: L'uso è severamente proibito e punito con Azkaban!

| Maledizione | Effetto | Potenza | Mana |
|------------|---------|---------|------|
| **Crucio** | Dolore atroce | 100 | 80 |
| **Imperio** | Controllo totale | 90 | 75 |
| **Avada Kedavra** | Morte istantanea | 150 | 100 |

### Sistema di Progressione

#### Competenza (Proficiency)
- Scala 1-100
- Aumenta con l'uso
- Migliora efficacia incantesimo

#### Statistiche Tracciabili
- Incantesimi appresi
- Incantesimi lanciati
- Ultimo utilizzo
- Competenza per incantesimo

### File Implementati
```
database/migrations/2025_01_15_000001_create_spells_table.php
database/seeds/SpellsTableSeeder.php
resources/views/front/spells/index.blade.php
```

### Migration
```bash
php artisan migrate
php artisan db:seed --class=SpellsTableSeeder
```

### Database Schema
```sql
spells:
- id, name, incantation, description
- type (attack/defense/healing/utility/charm/curse/transfiguration)
- power, mana_cost, required_level
- element, cooldown, duration
- icon, rarity, is_forbidden

user_spells:
- user_id, spell_id
- proficiency, times_used, last_used_at
```

---

## ⚔️ Arena di Combattimento

### Panoramica
Sistema di combattimento a turni strategico con statistiche, effetti e ricompense dinamiche.

### Meccaniche di Combattimento

#### Sistema a Turni
1. **Iniziativa**: Il personaggio più veloce attacca per primo
2. **Azioni Giocatore**:
   - Attacco Base
   - Lancia Incantesimo
   - Difesa (bonus difesa temporaneo)
   - Fuga (50% probabilità)

3. **IA Nemico**: Attacco automatico basato su statistiche

#### Statistiche Combattimento

**Giocatore:**
- HP (Punti Vita)
- Mana (Energia magica)
- Attacco (ATK)
- Difesa (DEF)
- Velocità (SPD)

**Creature:**
- HP (dalla tabella `creature`)
- Danno (DMG)
- Difesa (calcolata)
- Velocità (calcolata)
- Livello

#### Calcolo Danni
```javascript
danno_base = ATK_attaccante - DEF_difensore
modificatore_casuale = random(-2, +2)
danno_finale = max(1, danno_base + modificatore_casuale)
```

### Interfaccia Battaglia

#### Campo di Battaglia
- **Vista Doppia**: Giocatore vs Nemico
- **Barre HP animate**: Aggiornamento in tempo reale
- **Barra Mana**: Per incantesimi
- **Effetti Attivi**: Visualizzazione buff/debuff
- **Turno Indicato**: Animazione pulse sul combattente attivo

#### Log di Combattimento
- Storico azioni ultimi 10 turni
- Colori differenziati:
  - Rosso: Danni
  - Verde: Cura
  - Giallo: Avvisi
  - Blu: Informazioni

### Sistema Ricompense

#### Vittoria
- **XP**: `livello_creatura * 15 * (1 + random(0.3))`
- **Galeoni**: `livello_creatura * 10 * (1 + random(0.5))`
- Possibile drop oggetto raro

#### Sconfitta
- Ritorno all'arena
- Nessuna penalità permanente
- Possibilità di ritentare

### File Implementati
```
resources/views/front/combat/arena.blade.php
```

### Creature Disponibili
Tutte le creature dalla tabella `creature`:
- Acromantule
- Folletti
- Pixie
- Basilisco
- Draghi
- E molte altre...

### Controller Suggerito
```php
class CombatController extends Controller
{
    public function arena()
    {
        $creatures = Creature::where('level', '<=', auth()->user()->level + 5)
            ->get();

        return view('front.combat.arena', compact('creatures'));
    }

    public function battle(Request $request, $creatureId)
    {
        // Logic per salvare risultato battaglia
        // Aggiornare XP, money, statistiche
    }
}
```

---

## 🏆 Sistema Achievement

### Panoramica
Sistema completo di riconoscimenti con **25+ achievement** divisi per categoria, rarità e ricompense.

### Categorie Achievement

#### ⚔️ Combattimento
| Achievement | Requisito | Punti | Rarità |
|------------|-----------|-------|--------|
| Primo Sangue | 1 creatura sconfitta | 10 | Common |
| Cacciatore di Creature | 50 creature | 25 | Uncommon |
| Sterminatore | 200 creature | 50 | Rare |
| Invincibile | 10 vittorie perfette | 75 | Epic |

#### 🗺️ Esplorazione
| Achievement | Requisito | Punti | Rarità |
|------------|-----------|-------|--------|
| Primo Passo | 1 location visitata | 5 | Common |
| Esploratore | Tutte le location | 30 | Uncommon |
| Scopritore di Segreti | 5 passaggi segreti | 40 | Rare |

#### 👥 Sociale
| Achievement | Requisito | Punti | Rarità |
|------------|-----------|-------|--------|
| Socievole | 1 post forum | 5 | Common |
| Chiacchierone | 100 post forum | 25 | Uncommon |
| Popolare | 50 reazioni positive | 20 | Uncommon |

#### 📦 Collezione
| Achievement | Requisito | Punti | Rarità |
|------------|-----------|-------|--------|
| Collezionista Principiante | 10 oggetti | 10 | Common |
| Mercante | 50 transazioni | 30 | Uncommon |
| Ricco Sfondato | 10,000 Galeoni | 50 | Rare |

#### 🎓 Maestria
| Achievement | Requisito | Punti | Rarità |
|------------|-----------|-------|--------|
| Apprendista Mago | 1 incantesimo | 10 | Common |
| Maestro degli Incantesimi | 25 incantesimi | 50 | Rare |
| Lancia-Incantesimi | 500 incantesimi lanciati | 40 | Rare |
| Eroe di Hogwarts | 100 quest complete | 60 | Epic |

#### ⭐ Speciali (Leggendari)
| Achievement | Requisito | Punti | Rarità |
|------------|-----------|-------|--------|
| Orgoglio di Casata | Casata 1° posto | 100 | Legendary |
| Veterano | 365 giorni di login | 150 | Legendary |
| Prescelto | Livello massimo (50) | 200 | Legendary |
| Leggenda Oscura | 3 maledizioni proibite | 100 | Legendary |

### Achievement Segreti
Alcuni achievement sono **nascosti** finché non vengono sbloccati:
- Scopritore di Segreti
- Prescelto
- Studente Modello
- Leggenda Oscura

### Sistema di Tracciamento

#### Progresso
```javascript
{
  achievement_id: 5,
  progress: 23,        // Progressso attuale
  required: 50,       // Valore richiesto
  completed: false,
  percentage: 46%     // 23/50 * 100
}
```

#### Trigger Automatici
Il sistema può trackare automaticamente:
- `kill_creatures`: Creature uccise
- `complete_quests`: Quest completate
- `visit_locations`: Location visitate
- `forum_posts`: Post scritti
- `learn_spells`: Incantesimi appresi
- `cast_spells`: Incantesimi lanciati
- E molti altri...

### Ricompense
Ogni achievement offre:
- **Punti Achievement**: Per classifica
- **XP**: Esperienza bonus
- **Galeoni**: Denaro di gioco
- **Titoli** (futuro): Titoli speciali da mostrare

### File Implementati
```
database/migrations/2025_01_15_000002_create_achievements_table.php
database/seeds/AchievementsTableSeeder.php
resources/views/front/achievements/index.blade.php
```

### Migration
```bash
php artisan migrate
php artisan db:seed --class=AchievementsTableSeeder
```

---

## 🔧 Integrazione e Setup

### Installazione Dipendenze

#### Frontend (già incluso via CDN)
- TailwindCSS 3.3
- Alpine.js 3.x
- Font Awesome 6

#### Backend
```bash
# Migration nuove tabelle
php artisan migrate

# Seeding incantesimi e achievement
php artisan db:seed --class=SpellsTableSeeder
php artisan db:seed --class=AchievementsTableSeeder
```

### Route da Aggiungere

```php
// routes/web.php

Route::middleware(['auth'])->group(function () {

    // Inventario
    Route::get('/inventory', 'InventoryController@index')->name('inventory.index');
    Route::post('/inventory/equip', 'InventoryController@equip')->name('inventory.equip');
    Route::post('/inventory/use', 'InventoryController@use')->name('inventory.use');
    Route::post('/inventory/sell', 'InventoryController@sell')->name('inventory.sell');

    // Quest Generator
    Route::get('/quest-generator', 'QuestGeneratorController@index')->name('quest-generator.index');
    Route::post('/quest-generator/generate', 'QuestGeneratorController@generate')->name('quest-generator.generate');

    // Incantesimi
    Route::get('/spells', 'SpellController@index')->name('spells.index');
    Route::post('/spells/learn/{id}', 'SpellController@learn')->name('spells.learn');
    Route::post('/spells/cast/{id}', 'SpellController@cast')->name('spells.cast');

    // Combattimento
    Route::get('/combat/arena', 'CombatController@arena')->name('combat.arena');
    Route::post('/combat/battle/{creature}', 'CombatController@battle')->name('combat.battle');
    Route::post('/combat/end', 'CombatController@end')->name('combat.end');

    // Achievement
    Route::get('/achievements', 'AchievementController@index')->name('achievements.index');
});
```

### Controller da Creare

#### 1. InventoryController
```php
php artisan make:controller InventoryController
```

#### 2. QuestGeneratorController
✅ Già creato in `app/Http/Controllers/QuestGeneratorController.php`

#### 3. SpellController
```php
php artisan make:controller SpellController
```

#### 4. CombatController
```php
php artisan make:controller CombatController
```

#### 5. AchievementController
```php
php artisan make:controller AchievementController
```

### Modelli da Creare

```bash
php artisan make:model Spell
php artisan make:model UserSpell
php artisan make:model Achievement
php artisan make:model UserAchievement
```

### Menu di Navigazione

Aggiungi al menu principale:
```html
<a href="{{ route('inventory.index') }}">
    <i class="fas fa-backpack"></i> Inventario
</a>

<a href="{{ route('quest-generator.index') }}">
    <i class="fas fa-scroll"></i> Generatore Quest
</a>

<a href="{{ route('spells.index') }}">
    <i class="fas fa-wand-magic"></i> Grimorio
</a>

<a href="{{ route('combat.arena') }}">
    <i class="fas fa-swords"></i> Arena
</a>

<a href="{{ route('achievements.index') }}">
    <i class="fas fa-trophy"></i> Achievement
</a>
```

---

## 🎨 Temi e Personalizzazione

### Palette Colori

#### Rarità Oggetti/Achievement
```css
Common: #9CA3AF (Grigio)
Uncommon: #10B981 (Verde)
Rare: #3B82F6 (Blu)
Epic: #8B5CF6 (Viola)
Legendary: #F59E0B (Oro)
```

#### Casate
```css
Grifondoro: from-red-600 to-yellow-600
Serpeverde: from-green-700 to-gray-800
Corvonero: from-blue-700 to-blue-900
Tassorosso: from-yellow-500 to-yellow-700
```

---

## 📊 Database Schema Completo

### Nuove Tabelle

```sql
-- Incantesimi
CREATE TABLE spells (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),
    incantation VARCHAR(255),
    description TEXT,
    type ENUM('attack', 'defense', 'healing', 'utility', 'charm', 'curse', 'transfiguration'),
    power INT DEFAULT 0,
    mana_cost INT DEFAULT 10,
    required_level INT DEFAULT 1,
    element VARCHAR(50),
    cooldown INT DEFAULT 0,
    duration INT DEFAULT 0,
    icon VARCHAR(50),
    rarity ENUM('common', 'uncommon', 'rare', 'epic', 'legendary'),
    is_forbidden BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

CREATE TABLE user_spells (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    spell_id INT,
    proficiency INT DEFAULT 1,
    times_used INT DEFAULT 0,
    last_used_at TIMESTAMP NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (spell_id) REFERENCES spells(id) ON DELETE CASCADE
);

-- Achievement
CREATE TABLE achievements (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),
    description TEXT,
    icon VARCHAR(50),
    category ENUM('combat', 'exploration', 'social', 'collection', 'mastery', 'special'),
    points INT DEFAULT 10,
    rarity ENUM('common', 'uncommon', 'rare', 'epic', 'legendary'),
    required_value INT DEFAULT 1,
    requirement_type VARCHAR(100),
    exp_reward INT DEFAULT 0,
    money_reward INT DEFAULT 0,
    is_hidden BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

CREATE TABLE user_achievements (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    achievement_id INT,
    progress INT DEFAULT 0,
    completed BOOLEAN DEFAULT FALSE,
    completed_at TIMESTAMP NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (achievement_id) REFERENCES achievements(id) ON DELETE CASCADE
);
```

---

## 🚀 Features Future

### Pianificate
- [ ] **Mercato/Trading**: Scambio oggetti tra giocatori
- [ ] **Diario Personale**: Journal per annotazioni
- [ ] **Crafting System**: Creazione pozioni
- [ ] **Guild/Clan**: Gruppi di giocatori
- [ ] **PvP Arena**: Combattimento giocatore vs giocatore
- [ ] **Lezioni Interattive**: Mini-giochi per materie
- [ ] **Sistema Titoli**: Titoli sbloccabili
- [ ] **Pet System**: Familiari magici
- [ ] **Housing**: Stanza personale decorabile

### In Sviluppo
- [x] Sistema Inventario
- [x] Generatore Quest
- [x] Sistema Incantesimi
- [x] Arena Combattimento
- [x] Sistema Achievement

---

## 📞 Supporto

Per domande o problemi:
- Consulta la documentazione admin: `README_ADMIN.md`
- Controlla i controller di esempio in questo documento
- Verifica le migration siano state eseguite

---

**Versione**: 2.0.0
**Data**: Novembre 2025
**Autore**: Claude AI Assistant
**Licenza**: MIT

*Sviluppato con magia ✨ per Hogwarts GDR*
