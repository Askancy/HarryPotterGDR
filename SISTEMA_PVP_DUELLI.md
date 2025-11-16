# 🎮 Sistema di Combattimento PvP e Duelli Magici - Hogwarts GDR

## 📋 Panoramica

È stato implementato un sistema completo di combattimento PvP (Player vs Player) con duelli magici turn-based per il GDR di Harry Potter. Il sistema include meccaniche avanzate di combattimento, statistiche dei giocatori, classifiche e ricompense.

---

## ✨ Caratteristiche Principali

### 🎯 Sistema di Duello
- **Duelli a turni**: Sistema di combattimento strategico basato sui turni
- **Duelli classificati**: Influenzano il ranking ELO dei giocatori
- **Duelli di allenamento**: Pratica senza rischi o ricompense
- **Sistema di sfida**: Invita altri giocatori a duellare

### ⚔️ Meccaniche di Combattimento
1. **Azioni disponibili per turno**:
   - **Lancia Incantesimo**: Usa un incantesimo offensivo, difensivo o curativo
   - **Difenditi**: Riduce i danni del 50% e recupera il 10% del mana
   - **Fuggi**: Abbandona il duello (penalità: -20 punti ranking)

2. **Calcolo del danno**:
   ```
   Danno Base = Potere Incantesimo
   Moltiplicatore Magico = 1 + (Potere Magico Giocatore / 100)
   Moltiplicatore Padronanza = 0.5 + (Padronanza Incantesimo / 100)

   Danno Finale = Danno Base × Moltiplicatore Magico × Moltiplicatore Padronanza

   Probabilità Critico = min(30%, Destrezza / 10)
   Se Critico: Danno × 1.5

   Probabilità Schivata = min(20%, Destrezza Difensore / 15)
   Se Schivata: Danno = 0

   Se in Difesa: Danno × 0.5

   Riduzione Difesa = Difesa Difensore / 10
   Danno Finale = max(0, Danno - Riduzione Difesa)
   ```

### 📊 Sistema di Statistiche
Traccia per ogni giocatore:
- **Duelli totali**, vittorie, sconfitte, fughe
- **Danno totale** inflitto e ricevuto
- **Guarigione totale** effettuata
- **Incantesimi lanciati**, colpi critici, schivate
- **Incantesimo preferito** (più usato)
- **Serie di vittorie** (attuale e massima)
- **Punti ranking** (sistema ELO)
- **Record personali**: danno massimo, vittoria più veloce, duello più lungo

### 🏆 Sistema di Ranking
- **Punti ELO**: Inizia a 1000 punti
- **Calcolo dinamico**: Basato sulla differenza di ranking tra i giocatori
- **Classifica globale**: Mostra i top 50 giocatori
- **Posizione in classifica**: Aggiornata dopo ogni duello

### 🎁 Ricompense
Per i duelli classificati (non di allenamento):
- **Esperienza**: 50 × (Livello Avversario / Livello Giocatore)
- **Galeoni**: 5% del denaro dell'avversario (max 500)
- **Punti Casa**: 5-15 punti in base alla difficoltà
- **Punti Ranking**: Guadagno/perdita ELO

---

## 🗄️ Struttura Database

### Tabella `duels`
Memorizza i duelli tra giocatori.

**Campi principali**:
- `challenger_id`: Giocatore che lancia la sfida
- `opponent_id`: Giocatore sfidato
- `winner_id`: Vincitore del duello
- `status`: pending, active, completed, declined, expired, fled
- `current_turn`: Numero del turno corrente
- `current_player_id`: Giocatore che deve giocare
- `*_health`, `*_mana`: Salute e mana attuali di entrambi i giocatori
- `*_effects`: Effetti attivi (buff/debuff) in formato JSON
- `is_practice`: Flag per duelli di allenamento
- `exp_reward`, `money_reward`, `house_points_reward`: Ricompense

### Tabella `duel_turns`
Memorizza ogni turno di ogni duello.

**Campi principali**:
- `duel_id`: Riferimento al duello
- `turn_number`: Numero del turno
- `player_id`: Giocatore che ha eseguito l'azione
- `action_type`: spell, defend, item, flee
- `spell_id`: Incantesimo lanciato (se applicabile)
- `damage_dealt`, `healing_done`: Danni/cure effettuati
- `mana_used`, `mana_restored`: Mana consumato/recuperato
- `is_critical`, `is_dodged`, `is_blocked`: Flag di combattimento
- `effects_applied`: Effetti applicati in questo turno (JSON)
- `description`: Descrizione testuale dell'azione
- `*_health_after`, `*_mana_after`: Stato dopo il turno

### Tabella `duel_statistics`
Statistiche globali per giocatore.

**Campi principali**:
- `user_id`: Riferimento al giocatore (unico)
- `total_duels`, `duels_won`, `duels_lost`, `duels_fled`: Contatori duelli
- `total_damage_dealt`, `total_damage_received`: Statistiche di danno
- `total_spells_cast`, `total_critical_hits`, `total_dodges`: Statistiche combattimento
- `favorite_spell_id`: Incantesimo più usato
- `current_winning_streak`, `longest_winning_streak`: Serie di vittorie
- `ranking_points`: Punti ELO (default: 1000)
- `rank_position`: Posizione in classifica
- `highest_damage_single_spell`: Record di danno
- `fastest_victory_turns`: Vittoria più veloce (in turni)
- `longest_duel_turns`: Duello più lungo
- `house_points_earned`: Punti casa guadagnati dai duelli

---

## 🎯 Modelli Eloquent

### `Duel` (app/Models/Duel.php)
**Relazioni**:
- `challenger()`: Giocatore sfidante
- `opponent()`: Giocatore sfidato
- `winner()`: Vincitore
- `location()`: Luogo del duello
- `turns()`: Tutti i turni del duello

**Metodi principali**:
- `isPlayerTurn(User $user)`: Verifica se è il turno del giocatore
- `getOpponentFor(User $user)`: Ottiene l'avversario
- `getCurrentHealthFor(User $user)`: Salute attuale del giocatore
- `getCurrentManaFor(User $user)`: Mana attuale del giocatore
- `updateHealth(User $user, int $newHealth)`: Aggiorna la salute
- `updateMana(User $user, int $newMana)`: Aggiorna il mana
- `nextTurn()`: Passa al turno successivo

**Scopes**:
- `active()`: Duelli attivi
- `pending()`: Duelli in attesa di accettazione
- `completed()`: Duelli completati
- `forUser($userId)`: Duelli di un giocatore

### `DuelTurn` (app/Models/DuelTurn.php)
**Relazioni**:
- `duel()`: Duello di appartenenza
- `player()`: Giocatore che ha eseguito l'azione
- `spell()`: Incantesimo usato

**Metodi**:
- `getActionDescription()`: Descrizione testuale dell'azione
- `isSpellAction()`, `isDefendAction()`, etc.: Verifica tipo di azione

### `DuelStatistic` (app/Models/DuelStatistic.php)
**Relazioni**:
- `user()`: Giocatore
- `favoriteSpell()`: Incantesimo preferito

**Metodi principali**:
- `recordVictory(Duel $duel)`: Registra una vittoria
- `recordDefeat(Duel $duel)`: Registra una sconfitta
- `recordFlee()`: Registra una fuga
- `getWinRate()`: Calcola percentuale di vittorie
- `updateRankPosition()`: Aggiorna posizione in classifica

**Scopes**:
- `topRanked($limit)`: Top giocatori per punti ranking

---

## 🛠️ Service Layer

### `DuelService` (app/Services/DuelService.php)
Gestisce tutta la logica di business dei duelli.

**Metodi principali**:

#### `createDuel(User $challenger, User $opponent, bool $isPractice)`
Crea una nuova sfida a duello.
- Calcola le ricompense in base al livello
- Invia notifica all'avversario
- Imposta scadenza a 30 minuti

#### `acceptDuel(Duel $duel)`
Accetta una sfida a duello.
- Imposta lo stato su "active"
- Inizia il duello con il challenger come primo giocatore
- Notifica il challenger

#### `castSpell(Duel $duel, User $player, Spell $spell)`
Lancia un incantesimo durante il duello.
- Verifica mana sufficiente
- Calcola danno con modificatori
- Gestisce critici, schivate, difese
- Aggiorna statistiche giocatore
- Verifica condizione di vittoria
- Passa al turno successivo

#### `defend(Duel $duel, User $player)`
Esegue azione di difesa.
- Imposta flag di difesa (riduce danno al 50%)
- Recupera 10% del mana massimo
- Passa al turno successivo

#### `flee(Duel $duel, User $player)`
Fugge dal duello.
- Termina il duello con vittoria per l'avversario
- Applica penalità di -20 punti ranking
- Aggiorna statistiche di entrambi i giocatori

---

## 🎮 Controller

### `DuelController` (app/Http/Controllers/DuelController.php)

**Rotte disponibili**:

#### GET `/duels`
Dashboard dei duelli.
- Mostra duello attivo
- Inviti pendenti
- Duelli recenti
- Statistiche personali
- Classifica top 10

#### GET `/duels/create`
Form per creare una nuova sfida.
- Lista giocatori nella stessa location
- Opzione duello di allenamento

#### POST `/duels`
Crea una nuova sfida.
**Validazioni**:
- Non puoi sfidare te stesso
- Devi avere almeno 50% salute e mana
- Non puoi essere già in un duello
- Max 5 livelli di differenza per duelli classificati

#### GET `/duels/{id}`
Visualizza un duello specifico.
- Storia completa dei turni
- Barre di salute/mana
- Lista incantesimi disponibili
- Azioni possibili (se è il tuo turno)

#### POST `/duels/{id}/accept`
Accetta un invito a duello.

#### POST `/duels/{id}/decline`
Rifiuta un invito a duello.

#### POST `/duels/{id}/cast-spell`
Lancia un incantesimo.
**Parametri**: `spell_id`

#### POST `/duels/{id}/defend`
Esegui difesa.

#### POST `/duels/{id}/flee`
Fuggi dal duello.

#### GET `/duels/leaderboard`
Classifica globale (top 50).

#### GET `/duels/statistics`
Statistiche personali dettagliate.
- Tutti i record
- Storico duelli paginato

---

## 🔧 Installazione

### 1. Eseguire le Migrazioni

```bash
php artisan migrate
```

Questo creerà le tabelle:
- `duels`
- `duel_turns`
- `duel_statistics`

### 2. Aggiungere Link al Menu di Navigazione

Aggiungere al menu principale (es. `resources/views/layouts/app.blade.php`):

```html
<a href="{{ route('duels.index') }}" class="nav-link">
    <i class="fa fa-magic"></i> Duelli
</a>
```

---

## 📝 Utilizzo

### Per i Giocatori

1. **Iniziare un Duello**:
   - Vai su `/duels/create`
   - Scegli un avversario nella tua location
   - Scegli se fare un duello classificato o di allenamento
   - Invia la sfida

2. **Accettare una Sfida**:
   - Vai su `/duels`
   - Clicca "Accetta" su una sfida pendente
   - Verrai portato al duello

3. **Combattere**:
   - Quando è il tuo turno:
     - Scegli un incantesimo dalla lista
     - OPPURE clicca "Difenditi"
     - OPPURE clicca "Fuggi" (se disperato!)
   - Aspetta che l'avversario giochi il suo turno
   - Ripeti fino alla vittoria o alla sconfitta

4. **Visualizzare Statistiche**:
   - Vai su `/duels/statistics` per le tue statistiche
   - Vai su `/duels/leaderboard` per la classifica globale

### Restrizioni

- **Salute e Mana**: Minimo 50% per iniziare un duello
- **Livello**: Max 5 livelli di differenza per duelli classificati
- **Simultaneità**: Un solo duello attivo per volta
- **Scadenza**: Le sfide scadono dopo 30 minuti
- **Cooldown turno**: Nessun cooldown, ma è un gioco a turni

---

## 🎨 Frontend (Da Implementare)

### View da Creare

1. **resources/views/duels/index.blade.php**
   - Dashboard principale
   - Griglia con: duello attivo, inviti, recenti, statistiche, top 10

2. **resources/views/duels/create.blade.php**
   - Form per selezionare avversario
   - Checkbox per duello di allenamento

3. **resources/views/duels/show.blade.php**
   - Arena di combattimento
   - Barre salute/mana di entrambi i giocatori
   - Log dei turni
   - Pulsanti per azioni (incantesimi, difesa, fuga)
   - Animazioni CSS per le azioni

4. **resources/views/duels/leaderboard.blade.php**
   - Tabella classifiche con:
     - Posizione, nome, punti ranking
     - Vittorie/sconfitte, win rate
     - Serie di vittorie

5. **resources/views/duels/statistics.blade.php**
   - Statistiche personali dettagliate
   - Grafici (opzionale)
   - Storico duelli

### Componente Livewire (Opzionale)

Per aggiornamenti in tempo reale:

```bash
php artisan make:livewire DuelArena
```

Permetterebbe:
- Aggiornamento automatico quando l'avversario gioca
- Notifiche real-time
- Animazioni fluide

---

## 🧪 Testing

### Test Manuali

1. **Creare due utenti di test**
2. **User 1**: Crea sfida a User 2
3. **User 2**: Riceve notifica e accetta
4. **Combattimento**: Alternare azioni tra i due utenti
5. **Verificare**:
   - Calcolo corretto dei danni
   - Aggiornamento salute/mana
   - Critici e schivate funzionano
   - Difesa riduce danni del 50%
   - Vittoria assegna ricompense
   - Statistiche si aggiornano
   - Ranking cambia correttamente

### Test Automatici (Da Implementare)

```bash
php artisan make:test DuelSystemTest
```

Test da creare:
- `test_can_create_duel_challenge()`
- `test_cannot_challenge_self()`
- `test_cannot_challenge_with_low_health()`
- `test_can_accept_duel()`
- `test_can_cast_spell_in_duel()`
- `test_damage_calculation_is_correct()`
- `test_critical_hits_work()`
- `test_defending_reduces_damage()`
- `test_fleeing_ends_duel()`
- `test_statistics_update_correctly()`
- `test_elo_calculation_works()`

---

## 🔮 Future Espansioni

### Possibili Miglioramenti

1. **Tornei**:
   - Tornei programmati
   - Bracket eliminatori
   - Premi speciali

2. **Duelli di Squadra**:
   - 2v2 o 3v3
   - Strategia di squadra

3. **Oggetti Consumabili**:
   - Pozioni curative
   - Buff temporanei
   - Elixir speciali

4. **Effetti di Stato**:
   - Avvelenamento
   - Paralisi
   - Confusione
   - Buff/Debuff temporanei

5. **Replay System**:
   - Registra e rivedi duelli
   - Condividi con gli amici

6. **Stagioni Competitive**:
   - Reset ranking stagionale
   - Ricompense di fine stagione
   - Titoli speciali

7. **Arena Speciali**:
   - Location con bonus/malus
   - Eventi meteo
   - Hazard ambientali

8. **Achievement System**:
   - Badge per record specifici
   - Titoli sbloccabili
   - Sfide settimanali

---

## 📊 Bilanciamento

### Valori Consigliati per gli Incantesimi

#### Incantesimi di Attacco
- **Bassa potenza** (Livello 1-10): Power 20-30, Mana 10-15
- **Media potenza** (Livello 11-25): Power 40-60, Mana 20-30
- **Alta potenza** (Livello 26-50): Power 70-100, Mana 35-50

#### Incantesimi di Guarigione
- **Cura Leggera**: Restore 30 HP, Mana 15
- **Cura Media**: Restore 60 HP, Mana 30
- **Cura Maggiore**: Restore 100 HP, Mana 50

#### Incantesimi di Difesa/Utility
- **Scudo Base**: Power 0, Defense Buff, Mana 10
- **Scudo Avanzato**: Power 0, Defense Buff Grande, Mana 25

### Statistiche Giocatore Consigliate

**Livello 1**:
- Max Health: 100
- Max Mana: 50
- Tutti gli stats base: 10

**Crescita per livello**:
- Health: +10 per livello
- Mana: +5 per livello
- Stats: Aumentano con equipaggiamento e perks

---

## 🐛 Troubleshooting

### Problema: "Mana insufficiente"
**Soluzione**: Usa l'azione "Difenditi" per recuperare mana, oppure usa incantesimi meno costosi.

### Problema: "Non è il tuo turno"
**Soluzione**: Aspetta che l'avversario completi il suo turno. Ricarica la pagina se necessario.

### Problema: "Il duello non è attivo"
**Soluzione**: Il duello potrebbe essere scaduto o terminato. Torna alla dashboard dei duelli.

### Problema: "Impossibile sfidare questo giocatore"
**Soluzione**: Verifica:
- Livello di differenza (max 5 per duelli classificati)
- Il giocatore non è già in un duello
- Hai almeno 50% salute e mana

---

## 👥 Contributi

Per migliorare il sistema di duelli:

1. Fork il repository
2. Crea un branch per la tua feature (`git checkout -b feature/nuova-meccanica`)
3. Commit le modifiche (`git commit -m 'Aggiungi nuova meccanica'`)
4. Push al branch (`git push origin feature/nuova-meccanica`)
5. Apri una Pull Request

---

## 📄 Licenza

Questo sistema è parte del progetto Hogwarts GDR ed è soggetto alla stessa licenza del progetto principale.

---

## 📞 Supporto

Per domande o problemi:
- Apri una issue su GitHub
- Contatta gli admin del GDR
- Consulta la documentazione di Laravel 11

---

**Creato con ❤️ per la comunità di Hogwarts GDR**

*Buoni duelli e che la magia sia con voi!* 🪄✨
