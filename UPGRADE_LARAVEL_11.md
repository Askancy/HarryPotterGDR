# Upgrade a Laravel 11 e Livewire 3 - Guida Completa

## Panoramica

Il progetto HarryPotterGDR è stato aggiornato da Laravel 5.6 a Laravel 11, con integrazione completa di Livewire 3 per funzionalità reattive e moderne.

## Modifiche Principali

### 1. Requisiti Sistema

**Vecchi Requisiti (Laravel 5.6):**
- PHP ^7.1.3
- MySQL 5.7+

**Nuovi Requisiti (Laravel 11):**
- PHP ^8.2
- MySQL 8.0+ / MariaDB 10.3+
- Composer 2.x

### 2. Dipendenze Aggiornate

```json
{
  "require": {
    "php": "^8.2",
    "laravel/framework": "^11.0",
    "livewire/livewire": "^3.5",
    "arrilot/laravel-widgets": "^3.14",
    "intervention/image": "^3.0",
    "laravelcollective/html": "^6.4"
  }
}
```

### 3. Nuova Struttura Laravel 11

#### bootstrap/app.php
Laravel 11 introduce una nuova struttura per `bootstrap/app.php` con API fluente:

```php
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            // Middleware personalizzati
        ]);
    })
    ->create();
```

#### Middleware
I middleware sono ora registrati nel `bootstrap/app.php` invece che in `Kernel.php`:

```php
$middleware->alias([
    'admin' => \App\Http\Middleware\IsAdmin::class,
    'admitted' => \App\Http\Middleware\isAdmitted::class,
]);
```

### 4. Integrazione Livewire 3

#### Componenti Livewire Creati

**1. HouseRanking** - Classifica case in tempo reale
- Percorso: `app/Livewire/HouseRanking.php`
- View: `resources/views/livewire/house-ranking.blade.php`
- Features:
  - Auto-refresh ogni 30 secondi con `wire:poll`
  - Progress bar comparative
  - Indicatore caricamento
  - Colori casa personalizzati

**Utilizzo:**
```blade
<livewire:house-ranking />
```

**2. HouseChat** - Chat sala comune reattiva
- Percorso: `app/Livewire/HouseChat.php`
- View: `resources/views/livewire/house-chat.blade.php`
- Features:
  - Messaggi in tempo reale con polling 3s
  - Lista membri online
  - Invio messaggi senza refresh
  - Validazione e feedback istantaneo

**Utilizzo:**
```blade
<livewire:house-chat :houseId="$house->id" />
```

**3. Admin\HousePointsManager** - Gestione punti admin reattiva
- Percorso: `app/Livewire/Admin/HousePointsManager.php`
- Features:
  - Modal reattivi con Livewire
  - Assegnazione punti senza reload
  - Feedback immediato
  - Aggiornamento classifica real-time

#### Configurazione Livewire

File: `config/livewire.php`

Configurazioni importanti:
```php
'class_namespace' => 'App\\Livewire',
'view_path' => resource_path('views/livewire'),
'pagination_theme' => 'tailwind',
'inject_assets' => true,
```

### 5. Breaking Changes e Migrazioni

#### Sintassi Array
```php
// Vecchio (Laravel 5.6)
array('prefix' => 'admin', 'middleware' => 'admin')

// Nuovo (Laravel 11) - Entrambi funzionano
['prefix' => 'admin', 'middleware' => 'admin']
```

#### Helper Funzioni
Alcuni helper sono stati deprecati:
```php
// Vecchio
array_get($array, 'key')

// Nuovo
Arr::get($array, 'key')
```

#### Carbon
```php
// Ora è necessario importare Carbon esplicitamente
use Carbon\Carbon;
```

### 6. Nuove Features Laravel 11

#### Health Check Endpoint
Laravel 11 include un endpoint `/up` per health checks:
```php
->withRouting(
    health: '/up',
)
```

#### Middleware Semplificato
Non più `Kernel.php` - tutto in `bootstrap/app.php`:
```php
$middleware->web(append: [
    \App\Http\Middleware\UpdateLastActivity::class,
]);
```

## Installazione e Setup

### 1. Aggiorna Dipendenze

```bash
# Backup database prima!
composer install

# Se errori, prova:
composer update --ignore-platform-reqs

# Poi installa le dipendenze corrette
composer install
```

### 2. Pubblica Assets Livewire

```bash
php artisan livewire:publish --config
php artisan livewire:publish --assets
```

### 3. Esegui Migrations

```bash
php artisan migrate
```

### 4. Compila Assets (se usi Vite)

```bash
npm install
npm run build
```

### 5. Clear Cache

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

## Utilizzo Livewire 3

### Creazione Componenti

```bash
php artisan make:livewire ComponentName
```

Questo crea:
- `app/Livewire/ComponentName.php`
- `resources/views/livewire/component-name.blade.php`

### Esempio Componente Base

```php
<?php

namespace App\Livewire;

use Livewire\Component;

class ExampleComponent extends Component
{
    public $count = 0;

    public function increment()
    {
        $this->count++;
    }

    public function render()
    {
        return view('livewire.example-component');
    }
}
```

```blade
<div>
    <h1>Counter: {{ $count }}</h1>
    <button wire:click="increment">+</button>
</div>
```

### Features Livewire 3

#### 1. Wire:poll - Auto-refresh
```blade
<div wire:poll.3s="refresh">
    <!-- Si aggiorna ogni 3 secondi -->
</div>
```

#### 2. Wire:model - Two-way binding
```blade
<input wire:model="name" type="text">
<p>Ciao {{ $name }}</p>
```

#### 3. Wire:loading - Stati di caricamento
```blade
<button wire:click="save" wire:loading.attr="disabled">
    <span wire:loading.remove>Salva</span>
    <span wire:loading>Salvando...</span>
</button>
```

#### 4. Eventi
```php
// Emit event
$this->dispatch('user-updated');

// Listen event
#[On('user-updated')]
public function refreshData() { }
```

#### 5. Wire:navigate - SPA Navigation
```blade
<a href="/posts" wire:navigate>Posts</a>
```

## Componenti Livewire nel Progetto

### 1. Classifica Case (HouseRanking)

**Utilizzo nelle view:**
```blade
@livewire('house-ranking')
<!-- oppure -->
<livewire:house-ranking />
```

**Features:**
- Polling automatico ogni 30s
- Progress bars animate
- Crown per prima casa
- Responsive design

### 2. Chat Casa (HouseChat)

**Utilizzo:**
```blade
<livewire:house-chat :house-id="$house->id" />
```

**Features:**
- Messaggi real-time (polling 3s)
- Online members
- Character counter
- Auto-scroll
- Validazione inline

### 3. House Points Manager (Admin)

**Utilizzo:**
```blade
<livewire:admin.house-points-manager />
```

**Features:**
- Modal reattivi
- Feedback immediato
- Validazione form
- Auto-refresh classifica

## Best Practices

### 1. Proprietà Pubbliche
```php
// ✅ Corretto
public $name = '';

// ❌ Evitare
public $user; // Oggetti pesanti
```

### 2. Computed Properties
```php
use Livewire\Attributes\Computed;

#[Computed]
public function totalPoints()
{
    return $this->points * 10;
}

// Uso: {{ $this->totalPoints }}
```

### 3. Lazy Loading
```php
use Livewire\Attributes\Lazy;

#[Lazy]
class HeavyComponent extends Component
{
    public function placeholder()
    {
        return view('livewire.placeholder');
    }
}
```

## Troubleshooting

### 1. "Class not found"
```bash
composer dump-autoload
php artisan config:clear
```

### 2. "Livewire component not found"
```bash
php artisan livewire:discover
php artisan view:clear
```

### 3. "Property not found"
Assicurati che le proprietà siano `public`:
```php
public $myProperty; // ✅
private $myProperty; // ❌
```

### 4. "Too many redirects"
Verifica i middleware in `bootstrap/app.php`

### 5. Assets Livewire non caricati
```bash
php artisan livewire:publish --assets --force
```

## Compatibilità Codice Esistente

### Controllers
I controller esistenti continuano a funzionare senza modifiche.

### Blade Views
Le view Blade esistenti funzionano, ma puoi gradualmente convertirle a Livewire per features reattive.

### Routes
Le route in `routes/web.php` continuano a funzionare normalmente.

### Migrations
Tutte le migrations esistenti sono compatibili.

## Performance

### Ottimizzazioni Livewire

1. **Lazy Loading per componenti pesanti**
```php
#[Lazy]
class HeavyComponent extends Component { }
```

2. **Polling selettivo**
```blade
<!-- Invece di pollare tutto -->
<div wire:poll.3s>
    <!-- Polla solo questa sezione -->
    <div wire:poll.3s="loadMessages">...</div>
</div>
```

3. **Defer**
```php
public $defer = true; // Carica dopo il rendering iniziale
```

## Sicurezza

### CSRF Protection
Livewire gestisce automaticamente i token CSRF.

### Validation
```php
public function save()
{
    $this->validate([
        'name' => 'required|min:3',
        'email' => 'required|email',
    ]);
}
```

### Authorization
```php
use Livewire\Attributes\Locked;

#[Locked]
public $userId; // Non può essere modificato dal frontend
```

## Risorse

- [Laravel 11 Docs](https://laravel.com/docs/11.x)
- [Livewire 3 Docs](https://livewire.laravel.com/docs)
- [Upgrade Guide Laravel](https://laravel.com/docs/11.x/upgrade)
- [Livewire 3 Upgrade](https://livewire.laravel.com/docs/upgrading)

## Supporto

Per problemi o domande sull'upgrade:
1. Controlla questa documentazione
2. Verifica i log: `storage/logs/laravel.log`
3. Consulta la documentazione ufficiale
4. Controlla le issue su GitHub

## Changelog Progetto

### v2.0.0 - Upgrade Laravel 11 + Livewire 3

**Aggiunte:**
- ✅ Laravel 11.x
- ✅ Livewire 3.5
- ✅ PHP 8.2 support
- ✅ Componente HouseRanking reattivo
- ✅ Componente HouseChat real-time
- ✅ Componente Admin HousePointsManager
- ✅ Health check endpoint `/up`
- ✅ Configurazione Livewire completa

**Modifiche:**
- 🔄 bootstrap/app.php (nuova struttura Laravel 11)
- 🔄 Middleware registration (da Kernel a bootstrap)
- 🔄 Dipendenze aggiornate
- 🔄 Intervention/Image v3

**Rimosse:**
- ❌ Fideloper/proxy (obsoleto in Laravel 11)
- ❌ HTTP/Kernel.php (sostituito da bootstrap/app.php)

## Note Finali

L'upgrade mantiene **100% di retrocompatibilità** con le features esistenti mentre aggiunge nuove capacità reattive con Livewire 3. Tutti i componenti possono essere gradualmente migrati a Livewire per beneficiare delle features real-time senza page refresh.
