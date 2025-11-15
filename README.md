# 🧙‍♂️ Harry Potter GDR

<div align="center">

![Hogwarts](https://img.shields.io/badge/Hogwarts-School%20of%20Witchcraft-purple?style=for-the-badge)
![Laravel](https://img.shields.io/badge/Laravel-11.0-red?style=for-the-badge&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.1+-blue?style=for-the-badge&logo=php)
![Livewire](https://img.shields.io/badge/Livewire-3.5-purple?style=for-the-badge)

**Un GDR Browser-Based ambientato nel magico mondo di Harry Potter**

[Caratteristiche](#-caratteristiche) •
[Installazione](#-installazione-rapida) •
[Documentazione](#-documentazione) •
[Demo](#-demo)

</div>

---

## 📖 Descrizione

Harry Potter GDR è un gioco di ruolo online browser-based che permette ai giocatori di vivere un'esperienza immersiva nel mondo magico di Harry Potter. Iscriviti a Hogwarts, vieni smistato in una casa, frequenta le lezioni, guadagna denaro con i lavori e interagisci con altri studenti!

## ✨ Caratteristiche

### 🏰 Sistema Case Completo
- **4 Case di Hogwarts**: Grifondoro, Serpeverde, Corvonero, Tassorosso
- **Cappello Parlante**: Sistema di smistamento interattivo
- **Sale Comuni**: Chat dedicata per ogni casa
- **Punti Casa**: Sistema di competizione tra case
- **Classifica**: Visualizzazione punti e statistiche

### 🎓 Sistema Scolastico Completo
- **11 Materie Canoniche**:
  - Obbligatorie: Difesa contro le Arti Oscure, Trasfigurazione, Pozioni, Incantesimi, Erbologia, Storia della Magia, Astronomia
  - Opzionali (dal 3° anno): Divinazione, Cura delle Creature Magiche, Aritmanzia, Studio delle Rune
- **7 Anni Scolastici**: Progressione dall'ingresso al diploma
- **Sistema Classi**: Iscrizioni, professori, aule
- **Voti Canonici**: O, E, A, P, D, T (Outstanding → Troll)
- **Promozione/Bocciatura**: Valutazione automatica a fine anno
- **Calendario Procedurale**: Anno scolastico generato automaticamente con trimestri, vacanze ed esami

### 💰 Sistema Economico
- **Valute Magiche**: Galleons, Sickles, Knuts
- **Wallet Personale**: Tracking completo di guadagni e spese
- **5 Lavori Disponibili**:
  - Assistente Bibliotecario
  - Raccolta Ingredienti
  - Tutoraggio Studenti
  - Assistente di Pozioni
  - Custode delle Creature
- **Negozi**: Sistema completo con inventario e profitti
- **Transazioni**: Trasferimenti tra giocatori
- **Leaderboard**: Classifica dei più ricchi

### 🎮 Sistema di Gioco
- **Livelli ed Esperienza**: Progressione del personaggio
- **Skills**: 8 abilità magiche (Combat, Magic, Defense, Herbology, Potions, Divination, Charms, Transfiguration)
- **Locations**: Esplora il mondo magico
- **Eventi Casuali**: Eventi random dinamici
- **Quest**: Missioni da completare
- **Notifiche Real-time**: Sistema di notifiche integrato

### 🔐 Sistema Autenticazione
- **Registrazione Guidata**: Flusso ottimizzato
- **Auto-Redirect**: Reindirizzamento intelligente basato su smistamento
- **Middleware Protettivo**: Route protette per utenti smistati

---

## 🚀 Installazione Rapida

### Prerequisiti

- PHP >= 8.1
- Composer
- Database (MySQL/MariaDB/PostgreSQL/SQLite)
- Node.js >= 16 (opzionale)

### Metodo 1: Script Automatico (Consigliato)

#### Linux/Mac
```bash
git clone https://github.com/tuousername/HarryPotterGDR.git
cd HarryPotterGDR
chmod +x install.sh
./install.sh
```

#### Windows
```cmd
git clone https://github.com/tuousername/HarryPotterGDR.git
cd HarryPotterGDR
install.bat
```

### Metodo 2: Comando Laravel

```bash
composer install
php artisan hogwarts:install
```

### Metodo 3: Manuale

```bash
# 1. Clona e installa
git clone https://github.com/tuousername/HarryPotterGDR.git
cd HarryPotterGDR
composer install

# 2. Configura
cp .env.example .env
php artisan key:generate

# 3. Database (SQLite - più semplice)
touch database/database.sqlite
# Modifica .env: DB_CONNECTION=sqlite

# 4. Setup database
php artisan migrate
php artisan db:seed --class=SchoolEconomySeeder

# 5. Avvia
php artisan serve
```

Visita: `http://localhost:8000`

📖 **Guida Completa**: [INSTALLATION.md](INSTALLATION.md)

---

## 📚 Documentazione

### Guide Principali

| Documento | Descrizione |
|-----------|-------------|
| [INSTALLATION.md](INSTALLATION.md) | Guida installazione completa |
| [SCHOOL_ECONOMY_SYSTEM.md](SCHOOL_ECONOMY_SYSTEM.md) | Sistema scolastico ed economico |
| [LOGIN_REDIRECT_SYSTEM.md](LOGIN_REDIRECT_SYSTEM.md) | Sistema autenticazione e redirect |
| [HOUSE_SYSTEM_SETUP.md](HOUSE_SYSTEM_SETUP.md) | Sistema case e smistamento |
| [HOUSE_POINTS_SYSTEM.md](HOUSE_POINTS_SYSTEM.md) | Sistema punti casa |

### Comandi Artisan

```bash
# Sistema Scolastico
php artisan school:generate-year [year] [--theme=""] [--activate]
php artisan school:end-year [year_id] [--force]

# Sistema Economico
php artisan seed:jobs
php artisan economy:init-wallets

# Installazione
php artisan hogwarts:install [--force] [--skip-npm] [--skip-composer]
```

---

## 🎯 Struttura del Progetto

```
HarryPotterGDR/
├── app/
│   ├── Console/Commands/          # Comandi Artisan personalizzati
│   │   ├── InstallHogwartsGDR.php
│   │   ├── SchoolYearGenerate.php
│   │   ├── SchoolYearEnd.php
│   │   └── ...
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── SchoolController.php
│   │   │   ├── JobController.php
│   │   │   ├── EconomyController.php
│   │   │   └── ...
│   │   └── Middleware/
│   │       └── EnsureUserHasHouse.php
│   └── Models/
│       ├── SchoolYear.php
│       ├── SchoolClass.php
│       ├── Subject.php
│       ├── Wallet.php
│       ├── Job.php
│       └── ...
├── database/
│   ├── migrations/
│   │   ├── 2025_01_15_120001_create_school_calendar_system.php
│   │   ├── 2025_01_15_120002_create_school_classes_system.php
│   │   ├── 2025_01_15_120003_create_economy_system.php
│   │   └── ...
│   └── seeders/
│       └── SchoolEconomySeeder.php
├── resources/views/              # Template Blade
├── routes/
│   └── web.php                   # Route applicazione
├── install.sh                    # Installer Linux/Mac
├── install.bat                   # Installer Windows
└── .env.example                  # Template configurazione
```

---

## 🎮 Come Giocare

### 1️⃣ Registrazione
- Crea il tuo account magico
- Scegli il tuo personaggio

### 2️⃣ Smistamento
- Completa il quiz del Cappello Parlante
- Vieni assegnato a una delle 4 case

### 3️⃣ Esplora Hogwarts
- Iscriviti alle lezioni
- Completa quest ed eventi
- Guadagna denaro con i lavori

### 4️⃣ Interagisci
- Chatta nella sala comune della tua casa
- Sfida altri studenti
- Contribuisci ai punti casa

### 5️⃣ Progressione
- Migliora le tue skills magiche
- Avanza di livello
- Ottieni il diploma al 7° anno

---

## 🛠️ Tecnologie Utilizzate

- **Backend**: Laravel 11.0
- **Frontend**: Livewire 3.5, Blade Templates
- **Database**: MySQL/MariaDB/PostgreSQL/SQLite
- **Autenticazione**: Laravel Auth
- **Real-time**: Livewire
- **Styling**: Bootstrap/Tailwind (configurabile)
- **Asset Building**: Vite

---

## 📊 Database Schema

### Tabelle Principali

**Sistema Calendario (4)**
- `school_years` - Anni scolastici
- `school_terms` - Trimestri e vacanze
- `school_events` - Eventi scolastici
- `event_participations` - Partecipazioni

**Sistema Scuola (5)**
- `subjects` - Materie
- `school_classes` - Classi
- `class_enrollments` - Iscrizioni
- `grades` - Voti
- `yearly_performances` - Performance annuali

**Sistema Economia (6)**
- `wallets` - Portafogli
- `transactions` - Transazioni
- `jobs` - Lavori disponibili
- `job_completions` - Lavori completati
- `shop_inventory` - Inventario negozi
- `shop_purchases` - Acquisti

**Sistema Case (3)**
- `houses` - Case di Hogwarts
- `house_points_log` - Log punti
- `house_messages` - Chat case

**Totale**: 37+ tabelle

---

## 🤝 Contribuire

Le contribuzioni sono benvenute! Per modifiche importanti:

1. Fai fork del progetto
2. Crea un branch (`git checkout -b feature/AmazingFeature`)
3. Committa le modifiche (`git commit -m 'Add AmazingFeature'`)
4. Pusha al branch (`git push origin feature/AmazingFeature`)
5. Apri una Pull Request

### Sviluppo

```bash
# Clona il repository
git clone https://github.com/tuousername/HarryPotterGDR.git

# Installa dipendenze
composer install
npm install

# Avvia in modalità sviluppo
php artisan serve
npm run dev
```

---

## 🐛 Bug Reports

Hai trovato un bug? [Apri un issue](https://github.com/tuousername/HarryPotterGDR/issues)

Includi:
- Descrizione del problema
- Passi per riprodurre
- Screenshot (se applicabile)
- Log errori (`storage/logs/laravel.log`)

---

## 📝 Roadmap

- [ ] Sistema Quidditch completo
- [ ] Tornei e competizioni
- [ ] GUFI/MAGO (esami standardizzati)
- [ ] Banca Gringott
- [ ] Mercato nero (Notturn Alley)
- [ ] Sistema matrimoni
- [ ] Famiglie magiche
- [ ] Mini-giochi interattivi
- [ ] App mobile (React Native)
- [ ] API REST completa

---

## 🎓 Crediti

### Sviluppato con

- [Laravel](https://laravel.com) - PHP Framework
- [Livewire](https://livewire.laravel.com) - Full-stack Framework
- [Harry Potter Universe](https://www.wizardingworld.com) - Mondo magico di J.K. Rowling

### Autori

- **Il tuo nome** - *Sviluppo principale* - [@tuousername](https://github.com/tuousername)

### Ringraziamenti

- J.K. Rowling per aver creato il magico mondo di Harry Potter
- La community Laravel
- Tutti i contributori

---

## 📜 Licenza

Questo progetto è distribuito sotto licenza MIT - vedi [LICENSE](LICENSE) per dettagli.

**Nota**: Harry Potter e tutti i relativi nomi, personaggi e simboli sono marchi registrati di Warner Bros. Entertainment Inc. Questo progetto è un fan-made non ufficiale e non affiliato.

---

<div align="center">

**🧙 Fatto con magia e Laravel ✨**

[⬆ Torna su](#-harry-potter-gdr)

</div>
