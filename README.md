# 🧙‍♂️ Hogwarts GDR - Gioco di Ruolo Browser-Based

[![Laravel](https://img.shields.io/badge/Laravel-11.0-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://www.php.net)
[![Livewire](https://img.shields.io/badge/Livewire-3.5-purple.svg)](https://livewire.laravel.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

Un **gioco di ruolo online** ambientato nell'universo magico di Harry Potter. Iscriviti a Hogwarts, vieni smistato in una Casa, frequenta le lezioni, guadagna denaro attraverso lavori part-time e interagisci con altri studenti in un'esperienza magica completamente immersiva.

---

## 📚 Indice

- [Caratteristiche Principali](#-caratteristiche-principali)
- [Requisiti di Sistema](#-requisiti-di-sistema)
- [Installazione](#-installazione)
- [Configurazione](#️-configurazione)
- [Comandi Artisan Personalizzati](#-comandi-artisan-personalizzati)
- [Tecnologie Utilizzate](#-tecnologie-utilizzate)
- [Struttura del Progetto](#-struttura-del-progetto)
- [Funzionalità Dettagliate](#-funzionalità-dettagliate)
- [Contribuire](#-contribuire)
- [Licenza](#-licenza)

---

## ✨ Caratteristiche Principali

### 🏰 Sistema delle Case
- **4 Case di Hogwarts**: Grifondoro, Serpeverde, Corvonero e Tassorosso
- **Cappello Parlante Interattivo**: Quiz di smistamento personalizzato
- **Sala Comune Esclusiva**: Chat in tempo reale per ogni Casa (Livewire)
- **Sistema Punti Casa**: Competizione tra le Case con classifiche
- **Eventi di Casa**: Tornei, sfide e attività esclusive

### 📖 Sistema Scolastico Completo
- **7 Anni Accademici**: Progressione dal 1° al 7° anno
- **11 Materie Canoniche**:
  - Obbligatorie: Difesa Contro le Arti Oscure, Trasfigurazione, Pozioni, Incantesimi, Erbologia, Storia della Magia, Astronomia
  - Elettive (dal 3° anno): Divinazione, Cura delle Creature Magiche, Aritmanzia, Studio delle Rune
- **Sistema di Valutazione**: O, E, A, P, D, T (Ottimo → Troll)
- **Calendario Scolastico Procedurale**: Trimestri, vacanze, periodi d'esame
- **Lezioni Giornaliere**: Slot mattutini e pomeridiani con presenza
- **Quiz e Compiti**: Sistema di verifica dell'apprendimento
- **Promozione/Bocciatura Automatica**: A fine anno scolastico

### 💰 Sistema Economico
- **Valuta Magica**: Galeoni, Falci e Zellini
- **5 Lavori Part-Time Disponibili**:
  - Assistente di Biblioteca
  - Raccolta Ingredienti
  - Tutoraggio Studenti
  - Assistente Pozioni
  - Custode delle Creature
- **Sistema di Negozi**: Acquisti e vendite
- **Negozi Possedibili**: I giocatori possono acquistare e gestire negozi
- **Cronologia Transazioni Completa**
- **Classifica Ricchezza**

### 🗺️ Esplorazione e Luoghi
- **5+ Località Magiche**:
  - 🏪 Diagon Alley (Livello 1) - Quartiere commerciale
  - 🍺 Hogsmeade (Livello 3) - Villaggio dei maghi
  - 🌑 Knockturn Alley (Livello 10) - Area delle Arti Oscure
  - 🏡 Godric's Hollow (Livello 5) - Villaggio storico
  - 🌲 Foresta Proibita (Livello 15) - Zona pericolosa
- **Sistema di Viaggio**: Spostamento tra località
- **Eventi Casuali**: 20% di probabilità durante i viaggi
- **Negozi Iconici**: Olivander, MondoMago, Weasley Wizard Wheezes, Honeydukes, e molti altri

### ⚔️ Sistema di Progressione RPG
- **Sistema Livelli**: EXP progressiva con formula esponenziale
- **8 Categorie di Abilità**: Combattimento, Magia, Difesa, Erbologia, Pozioni, Divinazione, Incantesimi, Trasfigurazione
- **Sistema Vantaggi (Perks)**: Abilità passive, attive e a toggle
- **Ricompense per Livello**: Denaro, titoli e sblocchi

### 🎯 Sistema Quest Dinamico
- **Generatore Procedurale di Quest**: 6 tipi di missioni
  - Raccolta (oggetti)
  - Combattimento (creature)
  - Esplorazione (luoghi)
  - Consegna (trasporti)
  - Investigazione (misteri)
  - Orgoglio di Casa (sfide specifiche)
- **20+ Template di Quest** con placeholder dinamici
- **Ricompense Automatiche**: Basate su difficoltà e livello
- **Limite Giornaliero**: 3 quest al giorno

### ✨ Sistema Incantesimi
- **27 Incantesimi Canonici**: Expelliarmus, Stupefy, Protego, Lumos, e molti altri
- **Categorie di Incantesimi**: Attacco, Difesa, Utilità, Trasfigurazione
- **Costi in Mana**: Gestione delle risorse magiche
- **Requisiti di Livello**: Sblocco progressivo

### 👔 Sistema Abbigliamento e Equipaggiamento
- **7 Slot Equipaggiamento**: Cappello, Tunica, Camicia, Pantaloni, Scarpe, Accessorio, Mantello
- **4 Livelli di Rarità**: Comune (Grigio), Raro (Blu), Epico (Viola), Leggendario (Oro)
- **Bonus Statistiche**: Forza, Intelligenza, Destrezza, Carisma, Difesa, Magia
- **Oggetti Specifici per Casa**

### 👥 Sistemi Sociali
- **Sistema Amicizie**: Invio/accettazione richieste, lista amici
- **Messaggistica Privata**: Conversazioni one-to-one in tempo reale
- **Forum di Gioco**: Categorie, sezioni, topic e post
- **Chat Locanda Multiplayer**: Chat di gruppo nelle locande
- **Profili Pubblici Utente**: Pagine profilo personalizzabili

### 🔔 Notifiche in Tempo Reale
- **Sistema Notifiche Livewire**: Campanella con contatore non letti
- **Categorie**: Achievement, Level Up, Quest Complete, Shop Purchase, Eventi, Punti Casa
- **Auto-refresh**: Ogni 30 secondi
- **Link Diretti**: Collegamenti al contenuto

### 🎲 Sistemi Aggiuntivi
- **Eventi Casuali**: Sistema dinamico di eventi random
- **Sistema Meteo**: Condizioni climatiche dinamiche
- **Achievement**: Tracciamento obiettivi e traguardi
- **Ticket Supporto**: Sistema di assistenza integrato
- **Pannello Admin Completo**: Gestione di tutti i sistemi di gioco

---

## 🖥️ Requisiti di Sistema

### Requisiti Minimi

- **PHP**: 8.2 o superiore
- **Composer**: Ultima versione stabile
- **Node.js**: 16.x o superiore
- **NPM**: 8.x o superiore
- **Database**: MySQL 8.0+ / MariaDB 10.3+ / PostgreSQL 13+ / SQLite 3.35+
- **Server Web**: Apache 2.4+ / Nginx 1.18+
- **Estensioni PHP richieste**:
  - BCMath
  - Ctype
  - Fileinfo
  - JSON
  - Mbstring
  - OpenSSL
  - PDO
  - Tokenizer
  - XML
  - GD o Imagick (per processamento immagini)

### Requisiti Consigliati

- **PHP**: 8.3
- **MySQL**: 8.0+
- **RAM**: Minimo 2GB
- **Storage**: 1GB libero

---

## 🚀 Installazione

### Metodo 1: Installazione Automatica (Consigliato)

#### Su Linux/Mac:

```bash
# 1. Clona il repository
git clone https://github.com/Askancy/HarryPotterGDR.git
cd HarryPotterGDR

# 2. Esegui lo script di installazione
chmod +x install.sh
./install.sh
```

#### Su Windows:

```batch
# 1. Clona il repository
git clone https://github.com/Askancy/HarryPotterGDR.git
cd HarryPotterGDR

# 2. Esegui lo script di installazione
install.bat
```

---

### Metodo 2: Comando Artisan

```bash
# 1. Clona il repository
git clone https://github.com/Askancy/HarryPotterGDR.git
cd HarryPotterGDR

# 2. Installa dipendenze PHP
composer install

# 3. Copia il file di configurazione
cp .env.example .env

# 4. Genera la chiave dell'applicazione
php artisan key:generate

# 5. Esegui il comando di installazione guidata
php artisan hogwarts:install
```

Il comando `hogwarts:install` ti guiderà attraverso:
- Configurazione database
- Migrazione tabelle
- Seeding dati iniziali
- Installazione dipendenze frontend
- Compilazione asset

---

### Metodo 3: Installazione Manuale Passo-Passo

#### Passo 1: Clona il Repository

```bash
git clone https://github.com/Askancy/HarryPotterGDR.git
cd HarryPotterGDR
```

#### Passo 2: Installa le Dipendenze PHP

```bash
composer install
```

**Cosa fa**: Scarica tutte le dipendenze PHP definite in `composer.json` (Laravel, Livewire, Intervention Image, ecc.)

#### Passo 3: Crea il File di Configurazione

```bash
cp .env.example .env
```

**Cosa fa**: Crea una copia del file di esempio delle variabili d'ambiente

#### Passo 4: Genera la Chiave dell'Applicazione

```bash
php artisan key:generate
```

**Cosa fa**: Genera una chiave di crittografia univoca per l'applicazione (APP_KEY nel file .env)

#### Passo 5: Configura il Database

Modifica il file `.env` con le credenziali del tuo database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hogwarts_gdr
DB_USERNAME=root
DB_PASSWORD=
```

**Opzione SQLite (più semplice per sviluppo)**:

```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

Se usi SQLite, crea il file del database:

```bash
touch database/database.sqlite
```

#### Passo 6: Esegui le Migrazioni del Database

```bash
php artisan migrate
```

**Cosa fa**: Crea tutte le tabelle del database (47+ tabelle) necessarie per il gioco

#### Passo 7: Popola il Database con i Dati Iniziali

```bash
php artisan db:seed --class=SchoolEconomySeeder
```

**Cosa fa**: Inserisce i dati iniziali:
- Le 4 Case di Hogwarts
- Le 11 materie scolastiche
- Località magiche (Diagon Alley, Hogsmeade, ecc.)
- Negozi iconici (Olivander, Honeydukes, ecc.)
- I 5 lavori part-time
- Incantesimi canonici
- Abilità di base

#### Passo 8: Inizializza il Sistema Economico

```bash
php artisan economy:init-wallets
```

**Cosa fa**: Crea i wallet virtuali per gestire i Galeoni

```bash
php artisan seed:jobs
```

**Cosa fa**: Popola i lavori disponibili nel database

#### Passo 9: Installa le Dipendenze Frontend

```bash
npm install
```

**Cosa fa**: Scarica tutte le dipendenze JavaScript (Vue.js, Bootstrap, Tailwind CSS, jQuery, ecc.)

#### Passo 10: Compila gli Asset Frontend

**Per sviluppo** (con watch per ricompilazione automatica):

```bash
npm run dev
```

**Per produzione** (file ottimizzati e minificati):

```bash
npm run production
```

**Cosa fa**: Compila JavaScript, CSS e altri asset usando Laravel Mix

#### Passo 11: Crea Storage Link (per upload file/immagini)

```bash
php artisan storage:link
```

**Cosa fa**: Crea un collegamento simbolico da `public/storage` a `storage/app/public`

#### Passo 12: (Opzionale) Genera l'Anno Scolastico

```bash
php artisan school:generate-year 2024
```

**Cosa fa**: Genera un anno scolastico completo con:
- Trimestri e vacanze
- Calendario eventi (Quidditch, banchetti, tornei)
- Periodi d'esame

#### Passo 13: (Opzionale) Genera Lezioni Giornaliere

```bash
php artisan lessons:generate-daily
```

**Cosa fa**: Crea il programma di lezioni quotidiane con slot mattutini e pomeridiani

#### Passo 14: Avvia il Server di Sviluppo

```bash
php artisan serve
```

**Cosa fa**: Avvia il server di sviluppo Laravel su `http://localhost:8000`

#### Passo 15: Accedi all'Applicazione

Apri il browser e vai su:

```
http://localhost:8000
```

**Registra un nuovo account** e inizia la tua avventura magica!

---

## ⚙️ Configurazione

### Configurazione Database

Modifica `.env` per il tuo database:

```env
# MySQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hogwarts_gdr
DB_USERNAME=root
DB_PASSWORD=your_password

# oppure SQLite (più semplice)
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

### Configurazione Sistema Scolastico

```env
# Anno scolastico: dal 1 settembre al 30 giugno
SCHOOL_YEAR_START_MONTH=9
SCHOOL_YEAR_START_DAY=1
SCHOOL_YEAR_END_MONTH=6
SCHOOL_YEAR_END_DAY=30
```

### Configurazione Sistema Economico

```env
# Galeoni iniziali per ogni nuovo studente
STARTING_GALLEONS=25

# Abilita il sistema wallet
WALLET_ENABLED=true
```

### Configurazione Sistema Case

```env
# Abilita il sistema punti casa
HOUSE_POINTS_ENABLED=true

# Assegnazione automatica punti casa
AUTO_HOUSE_POINTS=true
```

### Configurazione Funzionalità di Gioco

```env
# Abilita eventi casuali durante i viaggi
ENABLE_RANDOM_EVENTS=true

# Abilita il sistema lavori
ENABLE_JOBS=true

# Abilita il sistema scolastico completo
ENABLE_SCHOOL_SYSTEM=true
```

### Configurazione Email (Opzionale)

Per produzione con Gmail:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@hogwarts.com"
MAIL_FROM_NAME="Hogwarts GDR"
```

Per sviluppo con Mailtrap:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
```

---

## 🛠️ Comandi Artisan Personalizzati

Il progetto include diversi comandi Artisan per facilitare la gestione del gioco:

### `php artisan hogwarts:install`

**Installazione guidata completa** del gioco. Include:
- Verifica requisiti
- Configurazione database
- Migrazione tabelle
- Seeding dati
- Installazione dipendenze frontend
- Compilazione asset

**Uso**:
```bash
php artisan hogwarts:install
```

---

### `php artisan school:generate-year [year]`

**Genera un anno scolastico completo** con calendario, eventi, trimestri e vacanze.

**Parametri**:
- `year` - Anno da generare (es. 2024)

**Uso**:
```bash
php artisan school:generate-year 2024
```

**Genera**:
- 3 trimestri scolastici
- Vacanze natalizie e pasquali
- Eventi di Quidditch
- Banchetti (inizio/fine anno, Halloween, Natale)
- Tornei e cerimonie
- Periodi d'esame

---

### `php artisan school:end-year [year_id]`

**Termina un anno scolastico** ed esegue:
- Valutazione finale degli studenti
- Promozione o bocciatura automatica
- Archiviazione punti casa
- Reset per nuovo anno

**Parametri**:
- `year_id` - ID dell'anno scolastico da terminare

**Uso**:
```bash
php artisan school:end-year 1
```

---

### `php artisan lessons:generate-daily`

**Genera il programma di lezioni giornaliere** con:
- Slot mattutini (8:00-12:00)
- Slot pomeridiani (14:00-18:00)
- Assegnazione classi
- Assegnazione professori

**Uso**:
```bash
php artisan lessons:generate-daily
```

**Pianifica questo comando con cron** per esecuzione automatica:

```bash
# Nel file crontab
0 0 * * * cd /path/to/HarryPotterGDR && php artisan lessons:generate-daily
```

---

### `php artisan economy:init-wallets`

**Inizializza i wallet economici** per tutti gli utenti esistenti.

**Uso**:
```bash
php artisan economy:init-wallets
```

---

### `php artisan seed:jobs`

**Popola i 5 lavori part-time** nel database:
- Assistente di Biblioteca
- Raccolta Ingredienti
- Tutoraggio Studenti
- Assistente Pozioni
- Custode delle Creature

**Uso**:
```bash
php artisan seed:jobs
```

---

## 🏗️ Tecnologie Utilizzate

### Backend

| Tecnologia | Versione | Descrizione |
|------------|----------|-------------|
| **Laravel** | 11.0 | Framework PHP moderno e robusto |
| **PHP** | 8.2+ | Linguaggio di programmazione |
| **Livewire** | 3.5 | Componenti dinamici senza scrivere JavaScript |
| **Laravel Widgets** | 3.14 | Widget riutilizzabili per l'UI |
| **Intervention Image** | 3.0 | Processamento e manipolazione immagini |
| **Konekt HTML** | 6.7 | Helper HTML per Laravel 11 |

### Frontend

| Tecnologia | Versione | Descrizione |
|------------|----------|-------------|
| **Vue.js** | 2.5.7 | Framework JavaScript progressivo |
| **Bootstrap** | 4.0 | Framework CSS responsive |
| **Tailwind CSS** | 3.3 | Framework CSS utility-first |
| **jQuery** | 3.2 | Libreria JavaScript |
| **Laravel Mix** | 2.0 | Compilazione asset semplificata |
| **Axios** | 0.18 | Client HTTP basato su promise |

### Database

- **MySQL** 8.0+ (consigliato)
- **PostgreSQL** 13+
- **SQLite** 3.35+
- **MariaDB** 10.3+

### Sviluppo

| Tool | Versione | Descrizione |
|------|----------|-------------|
| **Laravel Debugbar** | 3.13 | Barra di debug per sviluppo |
| **PHPUnit** | 11.0 | Testing framework |
| **Laravel Sail** | 1.26 | Ambiente Docker per Laravel |
| **Laravel Pint** | 1.13 | Code style fixer |
| **Faker** | 1.23 | Generazione dati fake per test |

---

## 📂 Struttura del Progetto

```
HarryPotterGDR/
├── app/
│   ├── Console/
│   │   └── Commands/              # Comandi Artisan personalizzati
│   │       ├── InstallHogwartsGDR.php
│   │       ├── SchoolYearGenerate.php
│   │       ├── SchoolYearEnd.php
│   │       ├── GenerateDailyLessons.php
│   │       ├── InitializeWallets.php
│   │       └── SeedJobs.php
│   ├── Http/
│   │   ├── Controllers/           # Controller MVC
│   │   │   ├── Admin/             # Pannello amministrativo
│   │   │   ├── HouseController.php
│   │   │   ├── SchoolController.php
│   │   │   ├── EconomyController.php
│   │   │   ├── LocationController.php
│   │   │   ├── QuestController.php
│   │   │   └── ...
│   │   ├── Livewire/              # Componenti Livewire
│   │   │   ├── HouseChat.php
│   │   │   ├── NotificationBell.php
│   │   │   ├── ClothingInventory.php
│   │   │   └── ...
│   │   └── Middleware/            # Middleware personalizzati
│   │       └── EnsureUserHasHouse.php
│   ├── Models/                    # Modelli Eloquent
│   │   ├── User.php
│   │   ├── Team.php               # Case
│   │   ├── Subject.php            # Materie
│   │   ├── Location.php
│   │   ├── Quest.php
│   │   ├── Wallet.php
│   │   └── ...
│   └── ...
├── database/
│   ├── migrations/                # 47+ migrazioni database
│   ├── seeders/                   # Seeder per dati iniziali
│   │   └── SchoolEconomySeeder.php
│   └── factories/                 # Factory per testing
├── resources/
│   ├── views/                     # Template Blade
│   │   ├── houses/                # Viste Case
│   │   ├── school/                # Viste Sistema Scolastico
│   │   ├── economy/               # Viste Economia
│   │   ├── locations/             # Viste Località
│   │   ├── admin/                 # Pannello Admin
│   │   └── ...
│   ├── js/                        # File JavaScript
│   │   ├── app.js
│   │   └── components/            # Componenti Vue
│   └── css/                       # File CSS
│       ├── app.css
│       └── tailwind.css
├── routes/
│   ├── web.php                    # Route web principali
│   ├── api.php                    # Route API
│   └── console.php                # Route console
├── public/                        # File pubblici accessibili
│   ├── index.php                  # Entry point
│   ├── css/                       # CSS compilati
│   ├── js/                        # JS compilati
│   └── images/                    # Immagini statiche
├── storage/
│   ├── app/                       # File applicazione
│   ├── logs/                      # Log applicazione
│   └── framework/                 # Cache, sessions, views
├── tests/                         # Test PHPUnit
├── .env.example                   # Template configurazione
├── composer.json                  # Dipendenze PHP
├── package.json                   # Dipendenze JavaScript
├── artisan                        # CLI Artisan
├── install.sh                     # Script installazione Linux/Mac
├── install.bat                    # Script installazione Windows
└── README.md                      # Questo file
```

---

## 🎮 Funzionalità Dettagliate

### Sistema delle Case

Dopo la registrazione, ogni studente viene smistato dal **Cappello Parlante** attraverso un quiz interattivo che determina la Casa di appartenenza:

- **Grifondoro** - Coraggio e audacia
- **Serpeverde** - Astuzia e ambizione
- **Corvonero** - Intelligenza e saggezza
- **Tassorosso** - Lealtà e dedizione

Una volta smistato, accedi alla **Sala Comune** della tua Casa con:
- Chat in tempo reale con i compagni di Casa
- Classifica punti Casa
- Eventi esclusivi
- Annunci di Casa

### Sistema Scolastico

Il sistema scolastico replica fedelmente l'esperienza di Hogwarts:

**Anni Accademici**: 7 anni dal primo al settimo
**Materie**: 11 materie canoniche dalla saga
**Valutazioni**: Sistema di voti O-T (Outstanding to Troll)
**Lezioni**: Programma giornaliero con presenza obbligatoria
**Eventi**: Tornei di Quidditch, banchetti, cerimonie

**Generazione Anno Scolastico**:
```bash
php artisan school:generate-year 2024
```

Genera automaticamente:
- Calendario scolastico completo
- 3 trimestri
- Vacanze (Natale, Pasqua, Estate)
- Eventi Quidditch
- Banchetti (Halloween, Natale, Fine Anno)
- Periodi d'esame

### Sistema Economico

Gestisci i tuoi **Galeoni** attraverso:

**Lavori Part-Time** (5 disponibili):
- Guadagno basato su qualità e livello
- Sistema di cooldown configurabile
- Bonus in base alle abilità

**Negozi**:
- Acquista oggetti magici
- Possibilità di acquistare e gestire negozi
- Sistema di profitti per proprietari

**Transazioni**:
- Cronologia completa
- Categorie: quest, level up, acquisti, lavori, trasferimenti, tasse, multe, regali

### Sistema Quest

Il **generatore procedurale di quest** crea missioni dinamiche basate su:
- Livello del giocatore
- Casa di appartenenza
- Quest completate
- Progressione di gioco

**Tipi di Quest**:
1. **Raccolta**: "Raccogli 10 Mandragore per il Professor Sprout"
2. **Combattimento**: "Sconfiggi 5 Dissennatori nella Foresta Proibita"
3. **Esplorazione**: "Scopri la Camera dei Segreti"
4. **Consegna**: "Consegna questa lettera a Hogsmeade"
5. **Investigazione**: "Indaga sul furto nella Sala Comune"
6. **Orgoglio di Casa**: "Vinci 100 punti per il Grifondoro"

### Località ed Esplorazione

Viaggia attraverso il mondo magico:

| Località | Livello | Tipo | Caratteristiche |
|----------|---------|------|-----------------|
| **Diagon Alley** | 1 | Città | Hub commerciale principale |
| **Hogsmeade** | 3 | Villaggio | Primo villaggio interamente magico |
| **Knockturn Alley** | 10 | Città | Mercato nero e Arti Oscure |
| **Godric's Hollow** | 5 | Villaggio | Luogo storico |
| **Foresta Proibita** | 15 | Zona pericolosa | Creature e pericoli |

**Viaggiando**:
- 20% chance di eventi casuali
- Incontri con creature
- Scoperta di oggetti
- NPC e dialoghi

### Progressione del Personaggio

**Sistema Livelli**:
- Guadagna EXP completando quest, lezioni, lavori
- Livello massimo: Configurabile
- Ricompense per livello: Denaro, titoli, abilità

**Abilità** (12+):
- 8 categorie di abilità
- Massimo 10 livelli per abilità
- Bonus statistiche
- Allocazione manuale punti abilità

**Vantaggi (Perks)**:
- Abilità passive (sempre attive)
- Abilità attive (da attivare)
- Sistema prerequisiti
- Requisiti livello/abilità

### Componenti Real-Time (Livewire)

Componenti che si aggiornano in tempo reale senza ricaricare la pagina:

- **House Chat**: Chat Sala Comune
- **Notification Bell**: Campanella notifiche
- **Inn Chat**: Chat locanda multiplayer
- **Private Messages**: Messaggi privati
- **Friends List**: Lista amici
- **Progression Bar**: Barra EXP/Livello

### Pannello Amministrativo

Gestione completa del gioco con:

- **Utenti**: Gestione account, ban, permessi
- **Oggetti**: CRUD oggetti magici
- **Creature**: Gestione bestiario
- **Negozi**: Creazione e modifica negozi
- **Punti Casa**: Assegnazione manuale, bulk, reset
- **Quest**: Creazione quest personalizzate
- **Eventi**: Gestione eventi random
- **Forum**: Moderazione e gestione
- **Località**: Creazione nuove località

---

## 🤝 Contribuire

I contributi sono benvenuti! Segui questi passi:

1. **Fork** il repository
2. Crea un **branch** per la tua feature (`git checkout -b feature/NuovaFunzionalita`)
3. **Commit** le tue modifiche (`git commit -m 'Aggiunge NuovaFunzionalita'`)
4. **Push** al branch (`git push origin feature/NuovaFunzionalita`)
5. Apri una **Pull Request**

### Linee Guida

- Segui le convenzioni di codice Laravel
- Scrivi test per le nuove funzionalità
- Documenta il codice con commenti chiari
- Aggiorna la documentazione se necessario

---

## 📝 Licenza

Questo progetto è distribuito sotto licenza **MIT**. Vedi il file [LICENSE](LICENSE) per i dettagli.

---

## 🐛 Segnalazione Bug

Hai trovato un bug? Apri una [Issue](https://github.com/Askancy/HarryPotterGDR/issues) su GitHub.

---

## 💬 Supporto

Hai bisogno di aiuto?

- Apri una [Issue](https://github.com/Askancy/HarryPotterGDR/issues)
- Consulta la [documentazione Laravel](https://laravel.com/docs)
- Visita il [forum di Livewire](https://github.com/livewire/livewire/discussions)

---

## 🙏 Ringraziamenti

- **J.K. Rowling** per l'universo magico di Harry Potter
- **Laravel Team** per il fantastico framework
- **Livewire Team** per i componenti reattivi
- Tutti i **contributori** del progetto

---

## 🚀 Prossimi Sviluppi

- [ ] Sistema di combattimento PvP
- [ ] Duelli magici
- [ ] Sistema di squadre Quidditch
- [ ] Creature da allevare
- [ ] Sistema di crafting pozioni
- [ ] Mercato di scambio tra giocatori
- [ ] Modalità storia interattiva
- [ ] Boss fight e dungeon
- [ ] Sistema di gilde/club
- [ ] Eventi stagionali

---

## 📸 Screenshot

![Hogwarts GDR](https://via.placeholder.com/800x400?text=Hogwarts+GDR+Screenshot)

*Screenshot del gioco saranno aggiunti presto*

---

<div align="center">

**Fatto con ❤️ per gli amanti di Harry Potter**

[⬆ Torna su](#-hogwarts-gdr---gioco-di-ruolo-browser-based)

</div>
