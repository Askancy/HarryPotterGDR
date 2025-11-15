# 🧙 Harry Potter GDR - Guida Installazione

## Requisiti di Sistema

### Requisiti Minimi

- **PHP**: >= 8.1
- **Database**: MySQL 5.7+ / MariaDB 10.3+ / PostgreSQL 10+ / SQLite 3.8+
- **Composer**: ultima versione
- **Node.js**: >= 16.x (opzionale, per asset frontend)
- **NPM**: >= 8.x (opzionale)

### Estensioni PHP Richieste

- BCMath
- Ctype
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- PDO
- Tokenizer
- XML

---

## 🚀 Metodi di Installazione

### Opzione 1: Installazione Automatica (Consigliata)

#### Linux/Mac

```bash
# Clona il repository
git clone https://github.com/tuousername/HarryPotterGDR.git
cd HarryPotterGDR

# Esegui lo script di installazione
chmod +x install.sh
./install.sh
```

#### Windows

```cmd
REM Clona il repository
git clone https://github.com/tuousername/HarryPotterGDR.git
cd HarryPotterGDR

REM Esegui lo script di installazione
install.bat
```

Lo script ti guiderà attraverso 3 opzioni:
1. **Interattiva**: Configurazione passo-passo (consigliata)
2. **Rapida**: Usa configurazioni predefinite (SQLite)
3. **Manuale**: Installa solo dipendenze

---

### Opzione 2: Installazione con Comando Laravel

```bash
# Installa dipendenze
composer install

# Esegui l'installer interattivo
php artisan hogwarts:install
```

Questo comando:
- ✅ Crea il file `.env` interattivamente
- ✅ Configura il database
- ✅ Installa dipendenze
- ✅ Esegue migrations
- ✅ Inizializza dati (materie, lavori, anno scolastico)
- ✅ Configura storage e permessi

**Opzioni disponibili:**

```bash
# Forza installazione anche se .env esiste
php artisan hogwarts:install --force

# Salta installazione NPM
php artisan hogwarts:install --skip-npm

# Salta installazione Composer
php artisan hogwarts:install --skip-composer
```

---

### Opzione 3: Installazione Manuale

#### Passo 1: Clona Repository

```bash
git clone https://github.com/tuousername/HarryPotterGDR.git
cd HarryPotterGDR
```

#### Passo 2: Installa Dipendenze

```bash
# Dipendenze PHP
composer install

# Dipendenze JavaScript (opzionale)
npm install
```

#### Passo 3: Configura Ambiente

```bash
# Copia file di configurazione
cp .env.example .env

# Genera chiave applicazione
php artisan key:generate
```

#### Passo 4: Configura Database

Modifica `.env` con le tue credenziali:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hogwarts_gdr
DB_USERNAME=root
DB_PASSWORD=
```

**Oppure usa SQLite (più semplice):**

```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

```bash
# Crea file database SQLite
touch database/database.sqlite
```

#### Passo 5: Esegui Migrations

```bash
php artisan migrate
```

#### Passo 6: Inizializza Dati

```bash
# Seed completo (materie, lavori, anno scolastico)
php artisan db:seed --class=SchoolEconomySeeder

# Inizializza wallet per utenti esistenti (se necessario)
php artisan economy:init-wallets
```

#### Passo 7: Configura Storage

```bash
# Crea link simbolico per storage
php artisan storage:link

# Imposta permessi (Linux/Mac)
chmod -R 775 storage bootstrap/cache
```

#### Passo 8: Compila Asset (Opzionale)

```bash
# Development
npm run dev

# Production
npm run build
```

---

## 🗄️ Configurazione Database

### MySQL/MariaDB

```sql
-- Crea database
CREATE DATABASE hogwarts_gdr CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Crea utente (opzionale)
CREATE USER 'hogwarts_user'@'localhost' IDENTIFIED BY 'password_sicura';
GRANT ALL PRIVILEGES ON hogwarts_gdr.* TO 'hogwarts_user'@'localhost';
FLUSH PRIVILEGES;
```

File `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hogwarts_gdr
DB_USERNAME=hogwarts_user
DB_PASSWORD=password_sicura
```

### PostgreSQL

```sql
-- Crea database
CREATE DATABASE hogwarts_gdr ENCODING 'UTF8';

-- Crea utente (opzionale)
CREATE USER hogwarts_user WITH PASSWORD 'password_sicura';
GRANT ALL PRIVILEGES ON DATABASE hogwarts_gdr TO hogwarts_user;
```

File `.env`:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=hogwarts_gdr
DB_USERNAME=hogwarts_user
DB_PASSWORD=password_sicura
```

### SQLite (Consigliato per Test)

```bash
touch database/database.sqlite
```

File `.env`:
```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

---

## 🎮 Avvia l'Applicazione

### Server di Sviluppo

```bash
# Avvia server Laravel
php artisan serve

# Applicazione disponibile su:
# http://localhost:8000
```

### Configurazione Avanzata

```bash
# Specifica host e porta
php artisan serve --host=0.0.0.0 --port=8080

# Con watch per asset (in un altro terminale)
npm run dev
```

---

## 👤 Crea Utente Amministratore

### Metodo 1: Tramite Tinker

```bash
php artisan tinker
```

```php
// Trova un utente esistente
$user = User::find(1);

// Oppure crea nuovo utente
$user = User::create([
    'username' => 'admin',
    'name' => 'Admin',
    'surname' => 'Hogwarts',
    'email' => 'admin@hogwarts.com',
    'password' => bcrypt('password123'),
    'sex' => 0,
    'birthday' => '1980-01-01',
]);

// Imposta come admin
$user->group = 2; // 2 = Amministratore
$user->team = 1; // 1 = Grifondoro (opzionale)
$user->level = 1;
$user->money = 1000;
$user->save();

// Verifica
echo "Admin creato: {$user->username}";
```

### Metodo 2: Tramite Seeder

Crea `database/seeders/AdminUserSeeder.php`:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'username' => 'admin',
            'name' => 'Admin',
            'surname' => 'Hogwarts',
            'email' => 'admin@hogwarts.com',
            'password' => Hash::make('password123'),
            'sex' => 0,
            'birthday' => '1980-01-01',
            'group' => 2, // Admin
            'team' => 1,  // Grifondoro
            'level' => 1,
            'money' => 1000,
        ]);
    }
}
```

Esegui:
```bash
php artisan db:seed --class=AdminUserSeeder
```

---

## ✅ Verifica Installazione

### Test Rapido

```bash
# Verifica connessione database
php artisan tinker
>>> DB::connection()->getPdo();

# Conta utenti
>>> App\Models\User::count();

# Conta materie
>>> App\Models\Subject::count();
// Dovrebbe essere 11

# Conta lavori
>>> App\Models\Job::count();
// Dovrebbe essere 5

# Verifica anno scolastico
>>> App\Models\SchoolYear::getActive();
```

### Checklist Componenti

- [ ] Database connesso
- [ ] Tabelle create (37 migrations)
- [ ] Materie create (11 materie di Hogwarts)
- [ ] Lavori creati (5 lavori disponibili)
- [ ] Anno scolastico generato
- [ ] Classi create (77 classi totali)
- [ ] Storage link funzionante
- [ ] Server avviato correttamente

---

## 🔧 Troubleshooting

### Errore: "Class not found"

```bash
# Rigenera autoload
composer dump-autoload
```

### Errore: "Permission denied" (Linux/Mac)

```bash
# Imposta proprietario
sudo chown -R $USER:www-data storage bootstrap/cache

# Imposta permessi
chmod -R 775 storage bootstrap/cache
```

### Errore: "SQLSTATE[HY000] [2002] Connection refused"

```bash
# Verifica servizio MySQL
sudo systemctl status mysql  # Linux
brew services list           # Mac

# Avvia MySQL se necessario
sudo systemctl start mysql   # Linux
brew services start mysql    # Mac
```

### Errore: Migrations già eseguite

```bash
# Reset completo (ATTENZIONE: cancella tutti i dati)
php artisan migrate:fresh --seed

# Oppure rollback e riprova
php artisan migrate:rollback
php artisan migrate
```

### Problema: NPM non installa

```bash
# Pulisci cache
npm cache clean --force

# Rimuovi node_modules
rm -rf node_modules package-lock.json

# Reinstalla
npm install
```

### Database SQLite non trovato

```bash
# Crea directory se non esiste
mkdir -p database

# Crea file database
touch database/database.sqlite

# Verifica permessi
chmod 664 database/database.sqlite
```

---

## 🚀 Deploy in Produzione

### Preparazione

```bash
# Imposta environment
APP_ENV=production
APP_DEBUG=false

# Ottimizza
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Compila asset
npm run build
```

### Server Web

#### Apache

File `.htaccess` (già presente in `/public`):

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

#### Nginx

```nginx
server {
    listen 80;
    server_name hogwarts.example.com;
    root /var/www/hogwarts/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### Sicurezza

```bash
# Proteggi file sensibili
chmod 600 .env

# Disabilita listing directory
# (già configurato in .htaccess)

# Usa HTTPS (consigliato con Let's Encrypt)
certbot --nginx -d hogwarts.example.com
```

---

## 📚 Comandi Utili

### Manutenzione

```bash
# Pulisci cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Ottimizza per produzione
php artisan optimize

# Verifica scheduler
php artisan schedule:list

# Code queue (se usata)
php artisan queue:work
```

### Sistema Scolastico

```bash
# Genera nuovo anno scolastico
php artisan school:generate-year 2 --theme="Anno del Torneo" --activate

# Termina anno e valuta studenti
php artisan school:end-year

# Seed materie
php artisan tinker
>>> App\Models\Subject::seedHogwartsSubjects();
```

### Sistema Economico

```bash
# Inizializza lavori
php artisan seed:jobs

# Inizializza wallet utenti
php artisan economy:init-wallets
```

---

## 📖 Documentazione Aggiuntiva

- **SCHOOL_ECONOMY_SYSTEM.md** - Sistema scolastico ed economico completo
- **LOGIN_REDIRECT_SYSTEM.md** - Sistema autenticazione e redirect
- **HOUSE_SYSTEM_SETUP.md** - Sistema case e smistamento
- **HOUSE_POINTS_SYSTEM.md** - Sistema punti casa

---

## 🆘 Supporto

### Hai Problemi?

1. Controlla la sezione Troubleshooting sopra
2. Verifica i log: `storage/logs/laravel.log`
3. Consulta la documentazione Laravel: https://laravel.com/docs
4. Apri un issue su GitHub

### Contribuire

Le pull request sono benvenute! Per modifiche importanti:
1. Apri prima un issue per discutere le modifiche
2. Fai fork del repository
3. Crea un branch per la tua feature
4. Committa le modifiche
5. Pusha al branch
6. Apri una Pull Request

---

## 📄 Licenza

Questo progetto è distribuito sotto licenza [Inserisci Licenza].

---

## 🎉 Benvenuto a Hogwarts!

Una volta completata l'installazione:

1. Visita `http://localhost:8000`
2. Registra un account
3. Completa lo smistamento con il Cappello Parlante
4. Inizia la tua avventura magica!

**Buon divertimento! 🧙✨**
