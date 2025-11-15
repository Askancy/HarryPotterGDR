# Sistema Scolastico ed Economico - Harry Potter GDR

## Panoramica

Questo documento descrive il sistema scolastico completo con calendario procedurale, classi, promozioni/bocciature e il sistema economico per guadagnare e spendere denaro.

## Installazione

### 1. Installa Dipendenze

```bash
composer install
npm install
```

### 2. Esegui le Migrations

```bash
php artisan migrate
```

### 3. Inizializza il Sistema

```bash
php artisan db:seed --class=SchoolEconomySeeder
```

Questo comando:
- Crea tutte le materie di Hogwarts
- Genera i lavori disponibili
- Crea il primo anno scolastico
- Genera le classi per tutti gli anni (1-7)
- Attiva l'anno scolastico

### 4. Inizializza i Wallet (Opzionale)

Se hai già utenti esistenti, esegui:

```bash
php artisan economy:init-wallets
```

---

## Sistema Calendario Scolastico

### Generazione Anno Scolastico

Il sistema genera automaticamente un calendario scolastico procedurale con:

- **Anno scolastico**: dal 1 settembre al 30 giugno
- **6 Termini**:
  1. Primo Trimestre (1 set - 20 dic)
  2. Vacanze di Natale (21 dic - 6 gen)
  3. Secondo Trimestre (7 gen - 31 mar)
  4. Vacanze di Pasqua (1 apr - 15 apr)
  5. Terzo Trimestre (16 apr - 15 giu)
  6. Esami Finali (16 giu - 30 giu)

### Comandi Artisan

#### Genera nuovo anno scolastico

```bash
php artisan school:generate-year [year_number] [--theme="Tema Anno"] [--activate]
```

Esempio:
```bash
php artisan school:generate-year 2 --theme="Anno del Torneo Tremaghi" --activate
```

#### Termina anno scolastico

```bash
php artisan school:end-year [year_id] [--force]
```

Questo comando:
- Calcola le performance di tutti gli studenti
- Valuta promozioni/bocciature
- Promuove gli studenti meritevoli
- Invia notifiche
- Disattiva l'anno

---

## Sistema Scolastico

### Materie

Il sistema include tutte le materie canoniche di Hogwarts:

**Obbligatorie (anni 1-7):**
- Difesa contro le Arti Oscure
- Trasfigurazione
- Pozioni
- Incantesimi
- Erbologia
- Storia della Magia
- Astronomia

**Opzionali (anni 3+):**
- Divinazione
- Cura delle Creature Magiche
- Aritmanzia
- Studio delle Rune

### Classi

Ogni classe ha:
- Materia
- Professore
- Anno (1-7)
- Sezione
- Limite studenti (30)
- Aula
- Orario

### Iscrizione

Gli studenti possono:
1. Iscriversi manualmente alle classi
2. Usare l'iscrizione automatica per materie obbligatorie

```php
// Nel controller
$user->enrollInYear($schoolYear);
```

### Sistema di Voti

#### Scala di Voti

Basata sul sistema canonico di Harry Potter:
- **O** (Outstanding) - 100 - Eccezionale
- **E** (Exceeds Expectations) - 85 - Supera le Aspettative
- **A** (Acceptable) - 70 - Accettabile
- **P** (Poor) - 50 - Scarso
- **D** (Dreadful) - 30 - Deludente
- **T** (Troll) - 10 - Terribile

#### Tipi di Valutazione

- Compiti (homework)
- Quiz (quiz)
- Esami di metà anno (midterm)
- Esami finali (final)
- Progetti (project)
- Partecipazione (participation)

### Promozione/Bocciatura

#### Criteri di Promozione

Uno studente viene promosso se:
- Media voti ≥ 60 (almeno Acceptable)
- Massimo 2 materie insufficienti
- Assenze < 30% delle lezioni

#### Criteri di Bocciatura

Uno studente viene bocciato se:
- Media < 60
- Più di 2 materie insufficienti
- Troppe assenze (≥ 30%)

#### Diploma

Gli studenti del 7° anno con i requisiti vengono diplomati.

---

## Sistema Economico

### Valute Magiche

Il sistema supporta le tre valute del mondo magico:
- **Galleons** (moneta principale)
- **Sickles** (1 Galleon = 17 Sickles)
- **Knuts** (1 Sickle = 29 Knuts)

### Wallet

Ogni utente ha un wallet che traccia:
- Denaro corrente (per valuta)
- Totale guadagnato
- Totale speso
- Storico transazioni

### Modi per Guadagnare Denaro

#### 1. Quest (esistente)
Ricompense al completamento delle quest

#### 2. Livelli (esistente)
Ricompense automatiche al level up

#### 3. Eventi Casuali (esistente)
Ricompense dagli eventi random

#### 4. Lavori (NUOVO)

5 lavori disponibili:

| Lavoro | Pagamento | Livello Min | Anno Min | Cooldown | Requisiti |
|--------|-----------|-------------|----------|----------|-----------|
| Assistente Bibliotecario | 5 G | 1 | 1 | 24h | - |
| Raccolta Ingredienti | 8 G | 3 | 1 | 12h | Herbology 2 |
| Tutoraggio Studenti | 12 G | 5 | 3 | 24h | - |
| Assistente di Pozioni | 15 G | 7 | 4 | 24h | Potions 5 |
| Custode delle Creature | 10 G | 4 | 3 | 24h | - |

**Qualità del Lavoro:**
Il pagamento varia in base alla qualità (score 0-100):
- Score basso: 50% del pagamento base
- Score alto: 150% del pagamento base

#### 5. Negozi (ESTESO)

I proprietari di negozi guadagnano dal margine di profitto delle vendite.

#### 6. Trasferimenti

Gli utenti possono inviare denaro ad altri giocatori.

### Sistema Negozi

#### Inventario Negozi

Ogni negozio ha:
- Stock prodotti
- Prezzi base
- Margini di profitto
- Riassortimento automatico

#### Acquisti

Il sistema gestisce:
- Verifica disponibilità
- Verifica fondi
- Sottrazione denaro
- Aggiornamento inventario
- Profitto al proprietario
- Logging transazioni

### Transazioni

Tutti i movimenti di denaro vengono tracciati:

**Tipi di transazione:**
- `quest_reward` - Ricompensa quest
- `level_up` - Reward livello
- `event_reward` - Reward evento
- `shop_purchase` - Acquisto negozio
- `shop_sale` - Vendita (proprietario)
- `job_payment` - Pagamento lavoro
- `transfer_sent` - Trasferimento inviato
- `transfer_received` - Trasferimento ricevuto
- `tax` - Tassa
- `fine` - Multa
- `gift` - Regalo
- `admin_adjustment` - Aggiustamento admin

---

## API / Routes

### School System

```
GET  /school                    - Dashboard scolastica
GET  /school/classes            - Classi disponibili
POST /school/classes/{id}/enroll - Iscrizione a classe
GET  /school/classes/{id}       - Dettagli classe
GET  /school/performance        - Performance annuale
GET  /school/calendar           - Calendario scolastico
POST /school/auto-enroll        - Iscrizione automatica
```

### Economy System

```
GET  /economy/wallet            - Wallet personale
GET  /economy/transactions      - Storico transazioni
POST /economy/transfer          - Trasferisci denaro
GET  /economy/leaderboard       - Classifica ricchezza
GET  /economy/stats             - Statistiche economia
```

### Jobs System

```
GET  /jobs                      - Lista lavori
GET  /jobs/{id}                 - Dettagli lavoro
POST /jobs/{id}/start           - Inizia lavoro
GET  /jobs/work/{id}            - Svolgi lavoro
POST /jobs/work/{id}/complete   - Completa lavoro
GET  /jobs/history              - Storico lavori
```

---

## Esempi di Utilizzo

### Generare un Anno Scolastico

```php
use App\Models\SchoolYear;
use App\Models\SchoolClass;

// Genera anno
$schoolYear = SchoolYear::generateYear(1, 'Anno Inaugurale');

// Genera classi
SchoolClass::generateForYear($schoolYear);

// Attiva
$schoolYear->activate();
```

### Iscrivere uno Studente

```php
$user = Auth::user();
$class = SchoolClass::find($classId);

if ($user->canEnrollInClass($class)) {
    $enrollment = $class->enrollStudent($user);
}

// Oppure iscrizione automatica a tutte le materie obbligatorie
$user->enrollInYear($schoolYear);
```

### Dare un Voto

```php
use App\Models\Grade;

Grade::create([
    'enrollment_id' => $enrollment->id,
    'user_id' => $student->id,
    'school_class_id' => $class->id,
    'type' => 'final',
    'grade_numeric' => 85, // Viene auto-convertito in 'E'
    'feedback' => 'Ottimo lavoro!',
    'graded_by' => $professor->id,
    'graded_date' => now(),
]);
```

### Valutare Fine Anno

```php
use App\Models\YearlyPerformance;

// Genera performance
YearlyPerformance::generateForYear($schoolYear);

// Valuta tutti
$results = YearlyPerformance::evaluateAll($schoolYear);

// $results = ['promoted' => 50, 'retained' => 5, 'graduated' => 10]
```

### Aggiungere Denaro

```php
$user = Auth::user();

// Metodo semplice
$user->addMoney(100, 'Ricompensa quest');

// Con wallet
$wallet = Wallet::getOrCreateForUser($user);
$wallet->addMoney(50);

// Con transazione
Transaction::log(
    $user,
    'quest_reward',
    100,
    'Completamento Quest: Il Segreto della Camera',
    reference: $quest
);
```

### Completare un Lavoro

```php
$job = Job::find($jobId);
$user = Auth::user();

// Inizia
$completion = JobCompletion::start($user, $job);

// Completa (con qualità)
$completion->complete(85); // quality score

// Il sistema automaticamente:
// - Calcola pagamento basato su qualità
// - Aggiunge denaro al wallet
// - Crea transazione
// - Aggiunge esperienza
// - Calcola cooldown
```

### Acquistare in Negozio

```php
use App\Models\ShopPurchase;

$shop = LocationShop::find($shopId);
$inventoryItem = ShopInventory::find($itemId);

$purchase = ShopPurchase::process(
    $shop,
    $buyer,
    $inventoryItem,
    $quantity
);

if ($purchase) {
    // Acquisto riuscito
    // Il sistema ha gestito tutto automaticamente
}
```

---

## Database Schema

### Tabelle Calendario

- `school_years` - Anni scolastici
- `school_terms` - Termini (trimestri, vacanze, esami)
- `school_events` - Eventi scolastici
- `event_participations` - Partecipazioni eventi

### Tabelle Scuola

- `subjects` - Materie
- `school_classes` - Classi
- `class_enrollments` - Iscrizioni
- `grades` - Voti
- `yearly_performances` - Performance annuali

### Tabelle Economia

- `wallets` - Portafogli utenti
- `transactions` - Transazioni
- `jobs` - Lavori disponibili
- `job_completions` - Lavori completati
- `shop_inventory` - Inventario negozi
- `shop_purchases` - Acquisti

---

## Comandi Console Disponibili

```bash
# Genera anno scolastico
php artisan school:generate-year [year] [--theme=] [--activate]

# Termina anno scolastico
php artisan school:end-year [year_id] [--force]

# Inizializza lavori
php artisan seed:jobs

# Inizializza wallet
php artisan economy:init-wallets

# Seed completo
php artisan db:seed --class=SchoolEconomySeeder
```

---

## Note Tecniche

### Performance

- Tutte le query usano indici appropriati
- Le relazioni sono eager-loaded quando necessario
- Le transazioni sono atomiche

### Validazioni

- I voti sono validati e auto-convertiti
- I fondi sono verificati prima degli acquisti
- Le iscrizioni verificano capacità e prerequisiti

### Notifiche

Il sistema invia notifiche automatiche per:
- Iscrizione a classi
- Nuovi voti
- Fine anno (promozione/bocciatura)
- Lavori completati
- Denaro ricevuto

### Estensibilità

Il sistema è progettato per essere esteso:
- Aggiungi nuove materie tramite `Subject::create()`
- Aggiungi nuovi lavori tramite `Job::create()`
- Crea eventi scolastici custom
- Aggiungi nuovi tipi di transazione

---

## Troubleshooting

### Le migrations falliscono

```bash
php artisan migrate:fresh
php artisan db:seed --class=SchoolEconomySeeder
```

### Gli utenti non hanno wallet

```bash
php artisan economy:init-wallets
```

### Nessuna classe disponibile

```bash
# Verifica che esista un anno attivo
php artisan tinker
> App\Models\SchoolYear::getActive()

# Se non esiste, creane uno
> $year = App\Models\SchoolYear::generateYear(1);
> App\Models\SchoolClass::generateForYear($year);
> $year->activate();
```

---

## Supporto

Per domande o problemi, consulta:
- Modelli in `app/Models/`
- Controller in `app/Http/Controllers/`
- Migrations in `database/migrations/`
