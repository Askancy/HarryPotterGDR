# Sistema di Redirect Post-Login Basato su Smistamento

## Panoramica

Il sistema di autenticazione è stato modificato per reindirizzare automaticamente gli utenti in base al loro stato di smistamento nelle case di Hogwarts.

## Comportamento

### 🎓 Dopo il Login

Quando un utente effettua il login:

1. **Utente NON smistato** (senza casa):
   - Viene portato al **Cappello Parlante** per lo smistamento
   - Route: `/sorting-hat` → `sorting-hat.show`

2. **Utente GIÀ smistato** (con casa):
   - Viene portato alla **Sala Comune** della propria casa
   - Route: `/house/common-room` → `house.common-room`

### 📝 Dopo la Registrazione

I nuovi utenti vengono sempre portati allo **smistamento**:
- Non hanno ancora una casa assegnata
- Devono completare il quiz del Cappello Parlante
- Route: `/sorting-hat` → `sorting-hat.show`

### 🔒 Utenti Già Autenticati

Se un utente già loggato prova ad accedere a pagine di login/registrazione:
- **Senza casa**: reindirizzato allo smistamento
- **Con casa**: reindirizzato alla sala comune

---

## File Modificati

### 1. LoginController (`app/Http/Controllers/Auth/LoginController.php`)

**Modifiche:**
- Rimosso `protected $redirectTo = '/'`
- Aggiunto metodo dinamico `redirectTo()`

```php
protected function redirectTo()
{
    $user = Auth::user();

    // Se l'utente non ha ancora una casa (team), portalo allo smistamento
    if (!$user || !$user->team) {
        return route('sorting-hat.show');
    }

    // Altrimenti portalo alla sala comune della sua casa
    return route('house.common-room');
}
```

### 2. RegisterController (`app/Http/Controllers/Auth/RegisterController.php`)

**Modifiche:**
- Rimosso `protected $redirectTo = '/sorting-hat'`
- Aggiunto metodo dinamico `redirectTo()`

```php
protected function redirectTo()
{
    return route('sorting-hat.show');
}
```

### 3. RedirectIfAuthenticated Middleware (`app/Http/Middleware/RedirectIfAuthenticated.php`)

**Modifiche:**
- Modificato da `return redirect('/')` a logica condizionale

```php
if (Auth::guard($guard)->check()) {
    $user = Auth::user();

    if (!$user->team) {
        return redirect()->route('sorting-hat.show');
    }

    return redirect()->route('house.common-room');
}
```

---

## Nuovo Middleware: EnsureUserHasHouse

### File Creato

`app/Http/Middleware/EnsureUserHasHouse.php`

### Scopo

Protegge route specifiche assicurando che l'utente sia stato smistato.

### Come Funziona

- Se l'utente è autenticato ma **non ha una casa**: reindirizza allo smistamento
- Se l'utente **ha una casa**: procede normalmente

### Registrazione

Registrato in `app/Http/Kernel.php` con alias `'sorted'`

```php
protected $routeMiddleware = [
    // ...
    'sorted' => \App\Http\Middleware\EnsureUserHasHouse::class,
];
```

### Utilizzo

Puoi applicare questo middleware a qualsiasi route che richiede che l'utente sia smistato:

```php
// Singola route
Route::get('/school/classes', [SchoolController::class, 'classes'])
    ->middleware(['auth', 'sorted']);

// Gruppo di route
Route::middleware(['auth', 'sorted'])->group(function () {
    Route::get('/school', [SchoolController::class, 'index']);
    Route::get('/school/classes', [SchoolController::class, 'classes']);
    Route::get('/house/common-room', [SortingHatController::class, 'commonRoom']);
});
```

---

## Esempi di Flusso Utente

### Scenario 1: Nuovo Utente

1. Utente si registra
2. ✅ Reindirizzato a `/sorting-hat`
3. Completa quiz smistamento
4. Casa assegnata (es. Grifondoro)
5. ✅ Reindirizzato a `/house/common-room`

### Scenario 2: Utente Esistente Senza Casa

1. Utente fa login (ha un account vecchio senza casa)
2. ✅ Reindirizzato a `/sorting-hat`
3. Completa smistamento
4. ✅ Reindirizzato a `/house/common-room`

### Scenario 3: Utente con Casa

1. Utente fa login (già smistato in Serpeverde)
2. ✅ Reindirizzato direttamente a `/house/common-room` (Sala Comune Serpeverde)

### Scenario 4: Utente Prova ad Accedere a Pagina Protetta

```php
// web.php
Route::get('/jobs', [JobController::class, 'index'])
    ->middleware(['auth', 'sorted']);
```

1. Utente non autenticato prova ad accedere `/jobs`
2. ❌ Reindirizzato al login (`auth` middleware)
3. Fa login (ma non ha casa)
4. ❌ Reindirizzato a `/sorting-hat` (`sorted` middleware)
5. Completa smistamento
6. ✅ Può accedere a `/jobs`

---

## Route da Proteggere

### Route che DOVREBBERO avere il middleware `sorted`:

```php
Route::middleware(['auth', 'sorted'])->group(function () {
    // Scuola
    Route::get('/school', [SchoolController::class, 'index']);
    Route::get('/school/classes', [SchoolController::class, 'classes']);
    Route::get('/school/performance', [SchoolController::class, 'performance']);
    Route::get('/school/calendar', [SchoolController::class, 'calendar']);

    // Lavori
    Route::get('/jobs', [JobController::class, 'index']);
    Route::get('/jobs/{job}', [JobController::class, 'show']);

    // Economia
    Route::get('/economy/wallet', [EconomyController::class, 'wallet']);
    Route::get('/economy/transactions', [EconomyController::class, 'transactions']);

    // Locations
    Route::get('/locations', [LocationController::class, 'index']);

    // Casa
    Route::get('/house/common-room', [SortingHatController::class, 'commonRoom']);
});
```

### Route che NON dovrebbero avere `sorted`:

- `/sorting-hat` - Ovviamente accessibile senza casa
- `/sorting-hat/assign` - Endpoint per assegnare casa
- Route pubbliche (landing page, about, ecc.)
- API pubbliche

---

## Testing

### Test 1: Nuovo Utente

```bash
# 1. Registra nuovo utente
POST /register
{
    "username": "harry_potter",
    "email": "harry@hogwarts.com",
    "password": "password123"
}

# Aspettativa: redirect a /sorting-hat
```

### Test 2: Login Senza Casa

```bash
# 1. Rimuovi casa da utente test
UPDATE users SET team = NULL WHERE id = 1;

# 2. Effettua login
POST /login
{
    "email": "test@test.com",
    "password": "password"
}

# Aspettativa: redirect a /sorting-hat
```

### Test 3: Login Con Casa

```bash
# 1. Assicurati che utente abbia casa
UPDATE users SET team = 1 WHERE id = 1;

# 2. Effettua login
POST /login
{
    "email": "test@test.com",
    "password": "password"
}

# Aspettativa: redirect a /house/common-room
```

### Test 4: Middleware `sorted`

```bash
# 1. Rimuovi casa da utente loggato
UPDATE users SET team = NULL WHERE id = 1;

# 2. Prova ad accedere a route protetta
GET /school/classes

# Aspettativa: redirect a /sorting-hat con messaggio warning
```

---

## Vantaggi del Sistema

### ✅ Esperienza Utente Migliorata
- Gli utenti non vedono mai contenuti non pertinenti
- Flusso naturale: registrazione → smistamento → gioco

### ✅ Logica Centralizzata
- Un solo posto dove gestire la logica di redirect
- Facile da mantenere e modificare

### ✅ Flessibilità
- Il middleware `sorted` può essere applicato selettivamente
- Alcune route possono essere accessibili prima dello smistamento

### ✅ Sicurezza
- Impossibile accedere a contenuti che richiedono una casa
- Nessun errore per utenti non smistati

---

## Configurazione Opzionale

### Personalizza Messaggio di Avviso

Nel middleware `EnsureUserHasHouse.php`:

```php
return redirect()->route('sorting-hat.show')
    ->with('warning', 'Il tuo messaggio personalizzato qui!');
```

### Aggiungi Eccezioni

Se vuoi che alcuni utenti possano bypassare lo smistamento:

```php
protected function handle($request, Closure $next)
{
    if (Auth::check()) {
        $user = Auth::user();

        // Eccezione per admin
        if ($user->isAdmin()) {
            return $next($request);
        }

        if (!$user->team) {
            return redirect()->route('sorting-hat.show')
                ->with('warning', 'Devi prima essere smistato!');
        }
    }

    return $next($request);
}
```

### Redirect Condizionali per Casa Specifica

Esempio: Serpeverde ha una sala comune diversa

```php
protected function redirectTo()
{
    $user = Auth::user();

    if (!$user || !$user->team) {
        return route('sorting-hat.show');
    }

    // Redirect speciale per Serpeverde
    if ($user->team == 2) {
        return route('house.slytherin-room');
    }

    return route('house.common-room');
}
```

---

## Migrazione per Utenti Esistenti

Se hai utenti esistenti nel database senza casa assegnata:

```sql
-- Verifica utenti senza casa
SELECT id, username, team FROM users WHERE team IS NULL OR team = 0;

-- Opzione 1: Assegna casa casuale
UPDATE users
SET team = FLOOR(1 + RAND() * 4)
WHERE team IS NULL OR team = 0;

-- Opzione 2: Forza smistamento al prossimo login
-- (Non fare nulla, il sistema li porterà automaticamente allo smistamento)
```

---

## Troubleshooting

### Problema: Redirect Loop

**Causa**: La route `sorting-hat.show` richiede il middleware `sorted`

**Soluzione**: Assicurati che `/sorting-hat` NON abbia il middleware `sorted`:

```php
// ❌ SBAGLIATO
Route::get('/sorting-hat', [SortingHatController::class, 'show'])
    ->middleware(['auth', 'sorted']);

// ✅ CORRETTO
Route::get('/sorting-hat', [SortingHatController::class, 'show'])
    ->middleware(['auth']);
```

### Problema: Utenti Admin Bloccati

**Soluzione**: Aggiungi eccezione nel middleware per admin:

```php
if ($user->isAdmin()) {
    return $next($request);
}
```

### Problema: Sessione Non Mantiene Redirect

**Causa**: Session middleware non configurato

**Soluzione**: Verifica che `StartSession` sia nel middleware group `web`

---

## Note Tecniche

### Laravel Version
Compatibile con Laravel 11

### Dependencies
- `Illuminate\Support\Facades\Auth`
- `Illuminate\Foundation\Auth\AuthenticatesUsers`
- `Illuminate\Foundation\Auth\RegistersUsers`

### Performance
- Nessun impatto significativo (1 query aggiuntiva per verificare `user->team`)
- Query cacheable con Redis/Memcached se necessario

---

## Prossimi Sviluppi Possibili

1. **Onboarding Esteso**
   - Tutorial post-smistamento
   - Tour della sala comune
   - Regalo di benvenuto

2. **Analytics**
   - Track tempo medio allo smistamento
   - Distribuzione case
   - Tassi di completamento

3. **A/B Testing**
   - Test diversi flussi di onboarding
   - Ottimizzazione quiz smistamento

4. **Notifiche**
   - Email di benvenuto post-smistamento
   - Notifica compagni di casa

---

## Riepilogo Comandi Git

```bash
# Verifica modifiche
git status

# Aggiungi modifiche
git add app/Http/Controllers/Auth/LoginController.php
git add app/Http/Controllers/Auth/RegisterController.php
git add app/Http/Middleware/RedirectIfAuthenticated.php
git add app/Http/Middleware/EnsureUserHasHouse.php
git add app/Http/Kernel.php

# Commit
git commit -m "feat: Implementa sistema redirect post-login basato su smistamento

- LoginController: redirect dinamico a sorting-hat o common-room
- RegisterController: nuovi utenti vanno sempre a sorting-hat
- RedirectIfAuthenticated: gestisce utenti già autenticati
- Nuovo middleware EnsureUserHasHouse ('sorted') per proteggere route
- Registrato middleware 'sorted' nel Kernel"

# Push
git push
```
