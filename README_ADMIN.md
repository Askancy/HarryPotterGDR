# Hogwarts GDR - Pannello Admin Moderno

## 🎭 Panoramica

Pannello di amministrazione moderno sviluppato con **TailwindCSS** e **Alpine.js** per il GDR basato sul mondo di Harry Potter.

## ✨ Caratteristiche Principali

### 🎨 Design Moderno
- **TailwindCSS 3.3**: Framework CSS utility-first per un design moderno e responsive
- **Alpine.js**: JavaScript reattivo per interazioni dinamiche
- **Font Awesome 6**: Icone moderne e professionali
- **Tema Harry Potter**: Colori personalizzati per le 4 casate di Hogwarts

### 📊 Dashboard Amministrativa

#### Statistiche in Tempo Reale
- **Utenti Totali**: Conteggio e crescita
- **Quest Attive**: Monitoraggio missioni
- **Creature**: Gestione bestiario
- **Topic Forum**: Attività della community

#### Classifica Casate
- Visualizzazione in tempo reale dei punti
- Indicatore del vincitore con corona
- Design gradient personalizzato per ogni casata:
  - 🦁 **Grifondoro**: Rosso e Oro
  - 🐍 **Serpeverde**: Verde e Argento
  - 🦅 **Corvonero**: Blu e Bronzo
  - 🦡 **Tassorosso**: Giallo e Nero

### 🛠 Moduli Amministrativi

#### 1. Gestione Utenti (`/admin/user`)
- **Lista completa** con filtri avanzati:
  - Ricerca per username/nome/email
  - Filtro per ruolo (Admin/Moderatore/Utente)
  - Filtro per casata
- **Visualizzazione**:
  - Avatar utente
  - Informazioni casata con badge colorato
  - Livello ed esperienza
  - Monete possedute
  - Data registrazione
- **Azioni rapide**: Modifica, Visualizza, Elimina

#### 2. Gestione Punti Casate (`/admin/point`)
- **Classifica interattiva** con animazioni
- **Assegnazione rapida** punti (positivi/negativi)
- **Storico completo** modifiche con:
  - Data e ora
  - Casata modificata
  - Punti assegnati/rimossi
  - Motivo dell'assegnazione
  - Amministratore responsabile
- **Modal interattivo** per aggiunta/rimozione punti

#### 3. Gestione Oggetti Magici (`/admin/objects`)
- **Vista a griglia** con card moderne
- **Filtri**:
  - Ricerca per nome
  - Filtro per negozio
- **Informazioni oggetto**:
  - Immagine/icona
  - Nome e descrizione
  - Prezzo in monete
  - Statistiche (ATK/DEF se presenti)
  - Tipologia (Bacchetta, Libro, Pozione, etc.)
- **Design gradient** per ogni card

#### 4. Gestione Quest (`/admin/quest`)
- **Vista a griglia** con card informative
- **Filtri multipli**:
  - Ricerca per nome
  - Filtro per stato (Attiva/Completata)
  - Filtro per privacy (Pubblica/Privata)
- **Statistiche quest**:
  - Ricompense (XP e Monete)
  - Numero partecipanti
  - Quest completate
  - Livello difficoltà (stelle)
- **Badge di stato**: Pubblica/Privata, Attiva/Disattiva

#### 5. Moderazione Forum (`/admin/forum`)
- **Sistema a tab**:
  - **Topic Recenti**: Lista completa topic
  - **Segnalazioni**: Gestione report utenti
  - **Statistiche**: Overview attività forum

- **Gestione Topic**:
  - Visualizzazione sezione e autore
  - Statistiche (visualizzazioni, risposte)
  - Stato (In evidenza, Bloccato, Eliminato)
  - **Azioni moderazione**:
    - Fissa/Rimuovi fissaggio (Pin)
    - Blocca/Sblocca topic (Lock)
    - Elimina topic

- **Gestione Segnalazioni**:
  - Lista report pendenti
  - Informazioni reporter
  - Contenuto segnalazione
  - Link al topic segnalato
  - Azioni: Risolvi / Ignora

### 🎯 Componenti UI

#### Layout Principal (`modern.blade.php`)
- **Sidebar collassabile** (mobile-friendly)
- **Menu di navigazione** organizzato per sezioni
- **Top navbar** con:
  - Breadcrumb/titolo pagina
  - Notifiche
  - Menu utente con dropdown
- **Sistema messaggi** (success/error) integrato
- **Footer** informativo

#### Sidebar (`sidebar-content.blade.php`)
- **Menu gerarchico** con icone Font Awesome
- **Sottomenu espandibili** (Alpine.js)
- **Indicatori di stato** (attivo/inattivo)
- **Badge utente** con ruolo
- **Gradiente dark-magical** come sfondo

### 🎨 Palette Colori

```javascript
// Casate Hogwarts
gryffindor: '#dc2626' → '#b91c1c' → '#991b1b'
slytherin:  '#1a5d1a' → '#166534' → '#14532d'
ravenclaw:  '#1e40af' → '#1e3a8a' → '#1e3a8a'
hufflepuff: '#eab308' → '#ca8a04' → '#a16207'

// Gradients
magical-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%)
dark-magical-gradient: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%)
```

### 📱 Responsive Design

- **Mobile First**: Design ottimizzato per dispositivi mobili
- **Breakpoints**:
  - `sm`: 640px
  - `md`: 768px
  - `lg`: 1024px
  - `xl`: 1280px
- **Sidebar collassabile** su mobile con overlay
- **Tabelle scrollabili** orizzontalmente
- **Grid responsive** che si adatta automaticamente

### ⚡ Interattività

#### Alpine.js Features
- **Filtri in tempo reale** senza reload pagina
- **Modal dinamici** per form
- **Dropdown menu** animati
- **Tab switching** fluido
- **Sidebar toggle** smooth
- **Transizioni** eleganti

#### JavaScript Enhancements
- **Auto-refresh** statistiche (opzionale)
- **Conferma eliminazione** con dialogs nativi
- **Form submission** gestito
- **Ricerca live** nei contenuti

## 🚀 Installazione e Configurazione

### Requisiti
- Laravel 5.6+
- PHP 7.1.3+
- Node.js (per compilazione assets, opzionale)
- MySQL

### Setup

1. **TailwindCSS** è caricato via CDN nel layout:
```html
<script src="https://cdn.tailwindcss.com"></script>
```

2. **Alpine.js** caricato via CDN:
```html
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
```

3. **Font Awesome 6**:
```html
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
```

### Utilizzo

#### Accesso al Pannello Admin
```
URL: /admin
Ruolo richiesto: group >= 1 (Moderatore o Admin)
```

#### Struttura Route (da configurare in routes/web.php)
```php
Route::middleware(['auth', 'isAdmin'])->prefix('admin')->group(function () {
    Route::get('/', 'Admin\DashboardController@index')->name('admin.index');

    // Users
    Route::resource('user', 'Admin\UserController');

    // Points
    Route::resource('point', 'Admin\PointController');

    // Objects & Shops
    Route::resource('objects', 'Admin\ObjectsController');
    Route::resource('shop', 'Admin\ShopController');

    // Creatures
    Route::resource('creature', 'Admin\CreatureController');
    Route::resource('genre', 'Admin\GenreCreatureController');

    // Quests
    Route::resource('quest', 'Admin\QuestController');

    // Maps/Chat
    Route::resource('chat', 'Admin\ChatController');

    // Forum Moderation
    Route::get('forum', 'Admin\ForumController@index')->name('admin.forum.index');
    Route::post('forum/pin/{id}', 'Admin\ForumController@pin')->name('admin.forum.pin');
    Route::post('forum/unpin/{id}', 'Admin\ForumController@unpin')->name('admin.forum.unpin');
    Route::post('forum/lock/{id}', 'Admin\ForumController@lock')->name('admin.forum.lock');
    Route::post('forum/unlock/{id}', 'Admin\ForumController@unlock')->name('admin.forum.unlock');
    Route::delete('forum/{id}', 'Admin\ForumController@delete')->name('admin.forum.delete');
    Route::post('forum/report/resolve/{id}', 'Admin\ForumController@resolveReport')->name('admin.forum.report.resolve');
    Route::delete('forum/report/{id}', 'Admin\ForumController@deleteReport')->name('admin.forum.report.delete');
});
```

## 📝 Controller da Implementare

### DashboardController
```php
public function index() {
    return view('admin.dashboard');
}
```

### UserController
```php
public function index() {
    $users = User::paginate(20);
    return view('admin.user.index', compact('users'));
}
```

### PointController
```php
public function index() {
    $logs = LogsPoint::latest()->paginate(20);
    return view('admin.points.index', compact('logs'));
}

public function store(Request $request) {
    // Logic per assegnare/rimuovere punti
}
```

### ObjectsController
```php
public function index() {
    $objects = Objects::paginate(20);
    return view('admin.objects.index', compact('objects'));
}
```

### QuestController
```php
public function index() {
    $quests = Quest::paginate(20);
    return view('admin.quest.index', compact('quests'));
}
```

### ForumController
```php
public function index() {
    $topics = ForumTopic::latest()->paginate(20);
    $reports = ForumReport::where('status', 0)->latest()->paginate(10);
    return view('admin.forum.index', compact('topics', 'reports'));
}
```

## 🎓 Best Practices

### Performance
- **Paginazione**: Tutti i listati usano pagination
- **Eager Loading**: Carica relazioni in anticipo per evitare N+1 queries
- **CDN**: Assets caricati da CDN per velocità

### Sicurezza
- **CSRF Protection**: Tutti i form includono `@csrf`
- **Method Spoofing**: DELETE con `@method('DELETE')`
- **Autorizzazione**: Middleware `isAdmin` per proteggere routes
- **Validazione**: Input sanitizzato e validato

### UX/UI
- **Feedback visivo**: Messaggi success/error
- **Conferme**: Dialog per azioni distruttive
- **Loading states**: Indicatori di caricamento
- **Responsive**: Mobile-friendly
- **Accessibility**: ARIA labels e semantic HTML

## 🔮 Funzionalità Future

- [ ] **Livewire Components**: Componenti reattivi lato server
- [ ] **Chart.js Integration**: Grafici statistiche avanzate
- [ ] **Export Data**: CSV/PDF export
- [ ] **Bulk Actions**: Azioni massive su selezioni multiple
- [ ] **Activity Log**: Tracciamento completo azioni admin
- [ ] **Permissions System**: Ruoli e permessi granulari
- [ ] **Dark Mode**: Modalità scura
- [ ] **Real-time Notifications**: WebSocket notifications
- [ ] **Advanced Filters**: Filtri salvabili
- [ ] **API REST**: Endpoints per integrazioni

## 📚 Risorse

- [TailwindCSS Documentation](https://tailwindcss.com/docs)
- [Alpine.js Documentation](https://alpinejs.dev/)
- [Font Awesome Icons](https://fontawesome.com/icons)
- [Laravel Documentation](https://laravel.com/docs/5.6)

## 🧙‍♂️ Credits

Sviluppato con magia ✨ per il mondo di Harry Potter GDR

---

**Versione**: 1.0.0
**Data**: Novembre 2025
**Licenza**: MIT
