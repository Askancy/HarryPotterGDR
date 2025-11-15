# Sistema Punti Case - Documentazione Completa

## Panoramica

Il sistema di punti case è un componente fondamentale del GDR di Harry Potter che permette agli studenti di guadagnare punti per la propria casa attraverso varie attività, e agli admin di gestire e monitorare la classifica delle case.

## Caratteristiche Principali

### 1. Assegnazione Punti Manuale (Admin)
- Interfaccia admin completa per assegnare/rimuovere punti
- Supporto per multiple tipologie di assegnazione
- Assegnazione di massa a tutti i membri di una casa
- Reset punti per nuovo anno scolastico con archivio storico

### 2. Assegnazione Automatica
- **Quest completate** - Punti basati sulla difficoltà
- **Achievement** - Punti basati sulla rarità
- **Login giornaliero** - Bonus presenza
- **Vittorie in combattimento** - Punti per duelli vinti
- **Apprendimento incantesimi** - Punti per nuovi spell
- **Eventi e competizioni** - Punti per vittorie casa

### 3. Visualizzazione Pubblica
- Classifica case realtime
- Storico attività recente
- Migliori contributori settimanali
- Archivio stagioni precedenti

### 4. Widget Integrato
- Widget classifica embeddable
- Aggiornamento automatico ogni 30 secondi
- Progress bar comparative

## Database

### Tabelle Create

#### house_points_log
Storico completo di tutte le assegnazioni punti:
```sql
- id
- house_id (casa che riceve/perde punti)
- user_id (studente coinvolto - nullable)
- awarded_by (admin/moderatore - nullable)
- points (intero, può essere negativo)
- type (enum: manual, quest_complete, achievement, event_win, etc.)
- reason (motivo testuale)
- details (dettagli aggiuntivi)
- created_at
- updated_at
```

#### house_points_archive
Archivio delle stagioni passate:
```sql
- id
- season (nome stagione, es: "Anno Scolastico 2025")
- houses_data (JSON con classifica finale)
- created_at
```

## Setup SQL Manuale

Se non puoi eseguire le migrations, usa questo SQL:

```sql
-- Tabella log punti
CREATE TABLE IF NOT EXISTS `house_points_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `house_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `awarded_by` int(10) unsigned DEFAULT NULL,
  `points` int(11) NOT NULL,
  `type` enum('manual','quest_complete','achievement','event_win','good_behavior','rule_violation','competition','attendance','system') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'manual',
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `details` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `house_points_log_house_id_foreign` (`house_id`),
  KEY `house_points_log_user_id_foreign` (`user_id`),
  KEY `house_points_log_awarded_by_foreign` (`awarded_by`),
  CONSTRAINT `house_points_log_house_id_foreign` FOREIGN KEY (`house_id`) REFERENCES `houses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `house_points_log_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `house_points_log_awarded_by_foreign` FOREIGN KEY (`awarded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabella archivio
CREATE TABLE IF NOT EXISTS `house_points_archive` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `season` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `houses_data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

## Utilizzo

### Per Amministratori

#### Accedere al Pannello
1. Vai su `/admin/house-points`
2. Vedrai la classifica attuale e l'attività recente

#### Assegnare Punti Singoli
1. Clicca su "Assegna Punti" sulla carta della casa
2. Inserisci:
   - Numero di punti (positivo o negativo)
   - Tipo di assegnazione
   - Motivo
   - (Opzionale) Username dello studente
3. Conferma

#### Assegnazione di Massa
1. Clicca su "Assegnazione di Massa"
2. Seleziona la casa
3. Inserisci punti per studente
4. Inserisci motivo (es: "Vittoria Coppa delle Case")
5. Conferma

Esempio: Se la casa ha 50 membri e assegni 10 punti per studente, la casa riceverà 500 punti totali.

#### Reset Punti (Nuovo Anno)
1. Clicca su "Reset Punti (Nuovo Anno)"
2. Digita "RESET" per confermare
3. Inserisci nome stagione (es: "Anno Scolastico 2025")
4. Conferma

**Nota:** I dati attuali vengono archiviati prima del reset.

### Per Sviluppatori

#### Assegnare Punti Automaticamente nel Codice

```php
use App\Helpers\HousePointsHelper;

// Quest completata
HousePointsHelper::onQuestComplete($userId, 'hard'); // 20 punti

// Achievement sbloccato
HousePointsHelper::onAchievementUnlock($userId, 'epic'); // 25 punti

// Login giornaliero
HousePointsHelper::onDailyLogin($userId); // 2 punti (max 1 volta al giorno)

// Vittoria combattimento
HousePointsHelper::onCombatVictory($userId, 'boss'); // 15 punti

// Incantesimo appreso
HousePointsHelper::onSpellLearned($userId, 'rare'); // 5 punti

// Evento vinto (da casa)
HousePointsHelper::onEventWin($houseId, 'Torneo di Quidditch', 100);

// Buon comportamento
HousePointsHelper::onGoodBehavior($userId, 'Aiuto a un compagno', 5);

// Violazione regole (punti negativi)
HousePointsHelper::onRuleViolation($userId, 'Uso magia proibita', -20, Auth::id());

// Competizione
HousePointsHelper::onCompetitionResult($houseId, 1, 'Coppa delle Case'); // 1° posto = 100 pts
```

#### Metodo Generico

```php
use App\Http\Controllers\HousePointsController;

HousePointsController::awardPoints(
    $houseId,        // ID della casa (1-4)
    $points,         // Punti (può essere negativo)
    $type,           // Tipo (vedi enum sopra)
    $reason,         // Motivo testuale
    $userId,         // ID studente (opzionale)
    $awardedBy,      // ID admin (opzionale, default Auth::id())
    $details         // Dettagli extra (opzionale)
);
```

### Tipi di Assegnazione Disponibili

- `manual` - Assegnazione manuale da admin
- `quest_complete` - Completamento quest
- `achievement` - Achievement sbloccato
- `event_win` - Vittoria evento
- `good_behavior` - Buon comportamento
- `rule_violation` - Violazione regole (di solito negativo)
- `competition` - Competizione tra case
- `attendance` - Presenza giornaliera
- `system` - Assegnazione di sistema

## API Endpoints

### Pubblici (No autenticazione richiesta)

#### GET /api/house-points/ranking
Ottieni classifica case attuale:
```json
{
  "houses": [
    {
      "id": 1,
      "name": "Grifondoro",
      "points": 1250,
      "rank": 1,
      "color": "#dc2626"
    },
    ...
  ]
}
```

#### GET /api/house-points/activity?limit=20&house_id=1
Ottieni attività recente:
- `limit` (opzionale, default 20) - Numero di entry
- `house_id` (opzionale) - Filtra per casa specifica

```json
{
  "activity": [
    {
      "id": 123,
      "house_name": "Grifondoro",
      "points": 10,
      "type": "quest_complete",
      "reason": "Quest completata (Difficoltà: hard)",
      "recipient_name": "Harry Potter",
      "awarder_name": "Sistema",
      "created_at": "2 ore fa",
      "created_at_full": "15 Gen 2025 14:30"
    },
    ...
  ]
}
```

#### GET /api/house-points/stats/{houseId}
Ottieni statistiche dettagliate di una casa:
```json
{
  "house": {
    "id": 1,
    "name": "Grifondoro",
    "total_points": 1250,
    "week_points": 85,
    "month_points": 320
  },
  "top_contributors": [
    {
      "id": 5,
      "name": "Harry Potter",
      "avatar": "/images/harry.jpg",
      "total_points": 45
    },
    ...
  ]
}
```

## Pagine

### Admin Panel
**URL:** `/admin/house-points`
**Accesso:** Solo amministratori

Funzionalità:
- Visualizza classifica case
- Assegna/rimuovi punti singoli
- Assegnazione di massa
- Reset punti con archivio
- Visualizza storico attività completo

### Classifica Pubblica
**URL:** `/house-points`
**Accesso:** Pubblico

Visualizza:
- Classifica attuale con ranking
- Migliori studenti della settimana
- Attività recente (ultimi 50 eventi)
- Archivio stagioni precedenti

## Widget Classifica

### Utilizzo in Blade Templates

```blade
@include('components.house-ranking-widget')
```

Il widget si aggiorna automaticamente ogni 30 secondi tramite polling API.

### Personalizzazione

Puoi modificare l'intervallo di polling nel file:
`resources/views/components/house-ranking-widget.blade.php`

Cerca:
```javascript
this.pollInterval = setInterval(() => this.loadRanking(), 30000); // 30 secondi
```

## Configurazione Punti

### Valori di Default

Questi valori sono configurabili in `App\Helpers\HousePointsHelper`:

**Quest:**
- Easy: 5 punti
- Medium: 10 punti
- Hard: 20 punti
- Expert: 30 punti

**Achievement:**
- Common: 5 punti
- Uncommon: 10 punti
- Rare: 15 punti
- Epic: 25 punti
- Legendary: 50 punti
- Hidden: 30 punti

**Combattimento:**
- Easy: 3 punti
- Medium: 5 punti
- Hard: 8 punti
- Boss: 15 punti

**Incantesimi:**
- Common: 2 punti
- Uncommon: 3 punti
- Rare: 5 punti
- Epic: 8 punti
- Legendary: 15 punti

**Altro:**
- Login giornaliero: 2 punti
- Buon comportamento: 5 punti (variabile)

**Competizioni:**
- 1° Posto: 100 punti
- 2° Posto: 75 punti
- 3° Posto: 50 punti
- 4° Posto: 25 punti

## Best Practices

### 1. Assegnazione Punti Bilanciata
- Non assegnare troppi punti per azioni semplici
- Mantieni un equilibrio tra le diverse case
- Usa punti negativi con moderazione

### 2. Motivazioni Chiare
- Fornisci sempre un motivo chiaro per l'assegnazione
- Usa le categorie appropriate
- Documenta decisioni importanti

### 3. Monitoraggio
- Controlla regolarmente l'attività recente
- Verifica eventuali abusi del sistema
- Monitora i migliori contributori

### 4. Reset Stagionale
- Esegui il reset all'inizio di ogni anno scolastico
- Comunica con anticipo agli utenti
- Celebra i vincitori della stagione precedente

## Esempi di Integrazione

### Esempio 1: Assegnare punti quando si completa una quest

Nel controller delle quest:
```php
use App\Helpers\HousePointsHelper;

public function completeQuest($questId)
{
    // ... logica completamento quest ...

    $quest = Quest::find($questId);

    // Assegna punti basati sulla difficoltà
    HousePointsHelper::onQuestComplete(
        Auth::id(),
        $quest->difficulty
    );

    return redirect()->back()->with('success', 'Quest completata! Hai guadagnato punti per la tua casa!');
}
```

### Esempio 2: Punti giornalieri al login

Nel middleware o controller di autenticazione:
```php
use App\Helpers\HousePointsHelper;

public function login(Request $request)
{
    // ... logica login ...

    if (Auth::check()) {
        // Assegna punti login giornaliero
        HousePointsHelper::onDailyLogin(Auth::id());
    }

    // ...
}
```

### Esempio 3: Penalità per violazione regole

Nel controller moderazione:
```php
use App\Helpers\HousePointsHelper;

public function penalizeUser(Request $request, $userId)
{
    $reason = $request->input('reason');
    $points = $request->input('points'); // es: -10

    HousePointsHelper::onRuleViolation(
        $userId,
        $reason,
        $points,
        Auth::id() // moderatore che assegna
    );

    return redirect()->back()->with('warning', 'Penalità applicata');
}
```

## Troubleshooting

### I punti non si aggiornano
- Verifica che le migrations siano state eseguite
- Controlla che la tabella `houses` abbia la colonna `points`
- Verifica i log degli errori

### Widget non si aggiorna
- Controlla la console del browser per errori JavaScript
- Verifica che l'endpoint `/api/house-points/ranking` risponda
- Controlla che Alpine.js sia caricato

### Assegnazione automatica non funziona
- Verifica che l'utente abbia una casa assegnata (`team` non null)
- Controlla che il metodo Helper sia chiamato correttamente
- Verifica i log di `house_points_log`

## Sviluppi Futuri Suggeriti

1. **Dashboard Statistiche**
   - Grafici andamento punti nel tempo
   - Analisi contributori top
   - Metriche per tipologia di assegnazione

2. **Notifiche**
   - Alert quando la casa guadagna/perde punti
   - Notifiche per milestone raggiunti
   - Email riepilogo settimanale

3. **Gamification Avanzata**
   - Badge per contributori top
   - Streak di login giornalieri
   - Bonus moltiplicatori per eventi speciali

4. **Integrazione Eventi**
   - Sistema eventi casa automatizzato
   - Competizioni settimanali/mensili
   - Sfide tra case

5. **Mobile App/PWA**
   - App mobile per visualizzare classifica
   - Push notifications
   - Quick actions

## Credits

Sistema sviluppato per HarryPotterGDR
Basato sul mondo magico di J.K. Rowling
