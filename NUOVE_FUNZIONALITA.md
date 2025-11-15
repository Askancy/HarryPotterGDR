# Nuove Funzionalità - Harry Potter GDR

## Panoramica

Questo documento descrive le nuove funzionalità implementate nel gioco GDR di Harry Potter. Il sistema è stato completamente aggiornato con nuove meccaniche di gioco, sistema di progressione, locations visitabili e molto altro.

## 📋 Indice

1. [Sistema di Notifiche](#sistema-di-notifiche)
2. [Sistema di Progressione](#sistema-di-progressione)
3. [Locations e Villaggi](#locations-e-villaggi)
4. [Negozi Visitabili](#negozi-visitabili)
5. [Sistema di Possesso Negozi](#sistema-di-possesso-negozi)
6. [Locande con Chat Multiplayer](#locande-con-chat-multiplayer)
7. [Eventi Casuali Dinamici](#eventi-casuali-dinamici)

---

## 📬 Sistema di Notifiche

### Descrizione
Sistema completo di notifiche real-time per tenere informati gli utenti su tutti gli eventi importanti del gioco.

### Caratteristiche
- **Notifiche Real-time**: Bell icon con contatore notifiche non lette
- **Categorie**: Achievement, Level Up, Quest Complete, Shop Purchase, Event Started, House Points, ecc.
- **Icone personalizzate**: Ogni tipo di notifica ha la sua icona FontAwesome
- **Link diretti**: Click sulla notifica per andare al contenuto correlato
- **Componente Livewire**: Aggiornamento automatico ogni 30 secondi

### Database
- Tabella: `notifications`
- Campi: user_id, type, title, message, icon, link, is_read, read_at, data (JSON)

### Routes
- `GET /notifications` - Lista tutte le notifiche
- `POST /notifications/{id}/read` - Segna come letta
- `POST /notifications/mark-all-read` - Segna tutte come lette
- `GET /notifications/unread-count` - Conta notifiche non lette (AJAX)

### Componente Livewire
```blade
<livewire:notification-bell />
```

---

## ⭐ Sistema di Progressione

### Descrizione
Sistema completo di livelli, esperienza e skill tree per la crescita del personaggio.

### Caratteristiche

#### Livelli ed Esperienza
- **EXP progressiva**: Ogni livello richiede più esperienza del precedente (formula: 100 * 1.5^(level-1))
- **Level Up automatico**: Quando raggiungi l'exp richiesta, sali di livello automaticamente
- **Ricompense per livello**: Galleon, Skill Points, Titoli speciali
- **Tracciamento totale**: Total EXP earned viene tracciato permanentemente

#### Skill System
- **12+ Skills**: Duello, Potenza Magica, Difesa contro Arti Oscure, Erbologia, Preparazione Pozioni, ecc.
- **Categorie**: combat, magic, defense, herbology, potions, divination, charms, transfiguration
- **Livello massimo per skill**: Configurabile (default 10)
- **Bonus**: Ogni skill fornisce bonus specifici (damage, accuracy, mana cost reduction, ecc.)
- **Allocazione manuale**: Usa Skill Points per potenziare le tue abilità

#### Database Tables
- `user_skills` - Pivot table per skills degli utenti
- `skills` - Definizione delle skill disponibili
- `level_rewards` - Ricompense per ogni livello (1-20)

### Routes
- `GET /progression` - Dashboard progressione personaggio
- `POST /progression/allocate-skill` - Alloca punto skill

### Componente Livewire
```blade
<livewire:progression-bar />
```

---

## 🏰 Locations e Villaggi

### Descrizione
Sistema di locations visitabili del mondo di Harry Potter, espandibile dall'admin.

### Locations Predefinite
1. **Diagon Alley** (Livello 1) - La via dello shopping magico
2. **Hogsmeade** (Livello 3) - Il villaggio completamente magico
3. **Knockturn Alley** (Livello 10) - La strada oscura delle Arti Oscure
4. **Godric's Hollow** (Livello 5) - Villaggio storico
5. **The Forbidden Forest** (Livello 15) - Foresta pericolosa

### Caratteristiche
- **Requisiti di livello**: Alcune location richiedono un livello minimo
- **Tipi**: village, city, landmark, secret
- **Eventi casuali**: 20% chance di triggherare evento quando viaggi
- **Tracciamento visite**: Conta visite, prima visita, ultima visita
- **Visitatori correnti**: Vedi chi altro è presente nella location

### Admin Panel
- Crea/Modifica/Elimina locations
- Imposta livello richiesto
- Abilita/disabilita eventi casuali
- Carica immagini custom

### Routes
- `GET /locations` - Mappa di tutte le locations
- `GET /locations/{slug}` - Vista dettagliata location
- `POST /locations/{slug}/travel` - Viaggia verso la location

---

## 🏪 Negozi Visitabili

### Descrizione
Negozi iconici del mondo di Harry Potter, visitabili e interattivi.

### Negozi Predefiniti

#### Diagon Alley
- **Olivander** - Bacchette magiche
- **MondoMago** - Oggetti magici vari
- **Weasley Wizard Wheezes** - Scherzi magici
- **Slug & Jiggers Apothecary** - Ingredienti per pozioni
- **Flourish and Blotts** - Libreria magica

#### Knockturn Alley
- **Borgin and Burkes** - Arti Oscure

#### Hogsmeade
- **The Three Broomsticks** - Locanda (Inn)
- **Hog's Head** - Locanda oscura (Inn)
- **Honeydukes** - Dolciumi
- **Zonko's Joke Shop** - Scherzi

### Tipi di Negozi
- **wands**: Bacchette
- **potions**: Pozioni e ingredienti
- **creatures**: Creature magiche
- **books**: Libri
- **clothing**: Abbigliamento
- **general**: Negozio generico
- **inn**: Locanda (con chat)
- **bank**: Banca (Gringott)

### Caratteristiche
- **Inventario dinamico**: Admin può configurare quali oggetti vende ogni negozio
- **Requisiti livello**: Alcuni negozi richiedono livello minimo
- **NPC Owners**: Ogni negozio ha un proprietario NPC
- **Acquisti**: Sistema di acquisto oggetti con Galleon

### Routes
- `GET /shops/{slug}` - Vista negozio
- `POST /shops/{slug}/purchase` - Acquista oggetto

---

## 💰 Sistema di Possesso Negozi

### Descrizione
I giocatori possono acquistare e possedere negozi, guadagnando profitti dalle vendite.

### Caratteristiche
- **Negozi acquistabili**: Alcuni negozi possono essere acquistati dai giocatori
- **Prezzo d'acquisto**: Configurabile dall'admin (range: 50,000 - 100,000 Galleon)
- **Profitti**: Il proprietario guadagna una percentuale (5-25%) su ogni vendita
- **Notifiche vendite**: Ricevi notifica quando qualcuno acquista nel tuo negozio
- **Gestione inventario**: Prossimamente - possibilità di modificare prezzi e inventario

### Meccanica
1. Visita un negozio con flag `is_purchasable = true`
2. Se hai abbastanza Galleon e il negozio non ha proprietario, puoi acquistarlo
3. Diventi `current_owner_id` del negozio
4. Guadagni `profit_percentage` su ogni vendita

### Admin Panel
- Imposta quali negozi sono acquistabili
- Configura prezzo di acquisto
- Imposta percentuale di profitto
- Resetta proprietario

### Routes
- `POST /shops/{slug}/purchase-shop` - Acquista il negozio

---

## 🍺 Locande con Chat Multiplayer

### Descrizione
Le locande (Inn) sono luoghi sociali dove i giocatori possono incontrarsi, chattare e partecipare a eventi insieme.

### Locande Disponibili
- **The Three Broomsticks** (Hogsmeade) - Locanda accogliente
- **Hog's Head** (Hogsmeade) - Locanda oscura

### Caratteristiche
- **Chat Real-time**: Componente Livewire con refresh automatico ogni 5 secondi
- **Visitatori presenti**: Vedi chi è online nella locanda (ultimi 5 minuti)
- **Tipi di messaggio**:
  - `text` - Messaggio normale
  - `action` - Azione RP (es: *beve burrobirra*)
  - `system` - Messaggio di sistema (eventi)
- **Eventi sociali**: Trigger eventi casuali multiplayer nella locanda
- **Avatar**: Mostra avatar degli utenti in chat

### Componente Livewire
```blade
<livewire:inn-chat :shopId="$inn->id" />
```

### Routes
- `GET /inns/{slug}` - Entra nella locanda
- `POST /inns/{slug}/leave` - Lascia la locanda
- `POST /inns/{slug}/trigger-event` - Avvia evento casuale

---

## 🎲 Eventi Casuali Dinamici

### Descrizione
Sistema di eventi casuali che si triggherano durante il gameplay, con scelte multiple e ricompense.

### Tipi di Eventi
- **location**: Eventi che si triggherano quando viaggi (20% chance)
- **inn**: Eventi sociali nelle locande
- **combat**: Incontri di combattimento
- **treasure**: Caccia al tesoro
- **social**: Interazioni sociali
- **mystery**: Eventi misteriosi

### Rarità
- **common** (60% probability): 50-100 EXP, 100-200 Galleon
- **uncommon** (25% probability): 80-150 EXP, 150-300 Galleon
- **rare** (10% probability): 150-200 EXP, 300-500 Galleon
- **epic** (4% probability): 200-300 EXP, 500-800 Galleon
- **legendary** (1% probability): 300-500 EXP, 800-1500 Galleon

### Eventi Predefiniti

#### Location Events
- **Incontro Misterioso** (common) - Indovinello da un mago strano
- **Creatura Magica Ferita** (uncommon) - Cura una creatura
- **Tesoro Nascosto** (rare) - Segui una mappa del tesoro

#### Inn Events
- **Sfida a Scacchi Magici** (common) - Partita di scacchi
- **Storia del Vecchio Mago** (uncommon) - Ascolta una storia antica
- **Duello Amichevole** (rare) - Partecipa a un duello

#### Mystery Events
- **Oggetto Misterioso** (epic) - Esamina oggetto misterioso
- **Portale Dimensionale** (legendary) - Attraversa un portale

### Caratteristiche
- **Scelte multiple**: Ogni evento ha 2+ scelte con diversi success rate
- **Durata limitata**: Eventi scadono dopo un tempo configurabile (20-120 minuti)
- **Eventi multiplayer**: Invita amici a partecipare (Inn events)
- **Ricompense dinamiche**: EXP, Galleon, oggetti (configurabile)
- **Partecipanti**: Traccia chi partecipa e il loro contributo

### Routes
- `GET /events/{id}` - Vista evento attivo
- `POST /events/{id}/choice` - Fai una scelta
- `POST /events/{id}/invite` - Invita un utente
- `POST /events/{id}/join` - Unisciti all'evento

### Componente Livewire
```blade
<livewire:active-events />
```

---

## 🔧 Installazione e Setup

### 1. Esegui le Migrations

```bash
php artisan migrate
```

Questo creerà tutte le nuove tabelle:
- notifications
- skills, user_skills, level_rewards
- locations, location_shops, user_locations
- inn_messages, inn_visitors
- random_events, user_random_events, event_participants

### 2. Popola il Database con i Seeders

```bash
php artisan db:seed --class=LocationSeeder
php artisan db:seed --class=LocationShopSeeder
php artisan db:seed --class=SkillSeeder
php artisan db:seed --class=RandomEventSeeder
php artisan db:seed --class=LevelRewardSeeder
```

### 3. Aggiorna gli Utenti Esistenti

Tutti gli utenti esistenti devono avere i nuovi campi inizializzati. Puoi farlo manualmente o creare una migration:

```sql
UPDATE users SET
  current_exp = 0,
  required_exp = 100,
  skill_points = 3,
  total_exp_earned = 0
WHERE current_exp IS NULL;
```

---

## 🎮 Come Usare le Nuove Funzionalità

### Per i Giocatori

1. **Visita Locations**: Vai su `/locations` per vedere la mappa del mondo magico
2. **Progressione**: Controlla `/progression` per vedere le tue skill e livello
3. **Negozi**: Visita Diagon Alley o Hogsmeade per fare shopping
4. **Locande**: Entra in The Three Broomsticks per chattare con altri giocatori
5. **Eventi**: Riceverai notifiche quando eventi casuali si attivano
6. **Notifiche**: Click sulla campanella in alto per vedere tutte le notifiche

### Per gli Admin

1. **Admin Panel**: `/admin`
2. **Gestisci Locations**: `/admin/locations`
3. **Gestisci Negozi**: `/admin/location-shops`
4. **Gestisci Skills**: `/admin/skills`
5. **Gestisci Eventi**: `/admin/random-events`

---

## 🔄 API e Integrazioni Future

### Possibili Estensioni
- Sistema di crafting per creare oggetti
- Missioni giornaliere e settimanali
- Sistema di gilde/alleanze
- PvP Arena per duelli
- Sistema di pet/familiars
- Breeding di creature magiche
- Sistema di casa (player housing)
- Mercato di scambio tra giocatori

---

## 📊 Statistiche e Metriche

Il sistema traccia automaticamente:
- Visite alle locations
- Oggetti acquistati
- Eventi completati
- Skill potenziate
- Livelli raggiunti
- Tempo speso nelle locande
- Messaggi inviati in chat

---

## ⚠️ Note Importanti

### Performance
- Le view Livewire si auto-aggiornano (notifiche ogni 30s, chat ogni 5s)
- Considera l'uso di Laravel Echo + Pusher per notifiche real-time su larga scala
- Le query sono ottimizzate con eager loading

### Sicurezza
- Tutte le routes sono protette da middleware `auth`
- Admin routes richiedono middleware `admin`
- Input validati in tutti i controller
- XSS protection tramite Blade escape automatico

### Scalabilità
- Eventi scaduti vengono automaticamente marcati come `expired`
- Considera job queue per notifiche massive
- Implementa cache per locations e shops statici

---

## 📝 Crediti

Implementato con:
- Laravel 11
- Livewire 3.5
- Tailwind CSS 3.3
- FontAwesome Icons

---

## 🐛 Bug Report e Feature Request

Per segnalare bug o richiedere nuove funzionalità, contatta gli amministratori del gioco.

---

**Buon Divertimento nel Mondo Magico! 🪄✨**
