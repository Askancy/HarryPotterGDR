# Sistema Case di Hogwarts - Guida alla Configurazione

## Panoramica

Questo documento descrive le modifiche apportate per implementare il sistema di smistamento nelle case di Hogwarts con sala comune e chat realtime.

## Funzionalità Implementate

### 1. Cerimonia dello Smistamento (Cappello Parlante)
- Quiz interattivo con 10 domande per determinare la casa
- Animazioni e grafica immersiva
- Assegnazione automatica della casa in base alle risposte
- Reindirizzamento automatico dopo la registrazione

### 2. Sala Comune della Casa
- Chat realtime con polling ogni 3 secondi
- Lista membri online/offline
- Statistiche della casa (rank, punti, membri, quest completate)
- Sistema di annunci con priorità
- Calendario eventi con RSVP
- Design specifico per ogni casa con colori tematici

### 3. Tracking Attività Utenti
- Colonna `last_activity` per tracciare gli utenti online
- Middleware che aggiorna l'attività ogni 2 minuti

## File Creati/Modificati

### Controllers
- `app/Http/Controllers/SortingHatController.php` - Gestisce smistamento e sala comune
- `app/Http/Controllers/Api/HouseApiController.php` - API per chat e funzionalità sala comune
- `app/Http/Controllers/Auth/RegisterController.php` - Modificato per reindirizzare al Cappello Parlante

### Middleware
- `app/Http/Middleware/UpdateLastActivity.php` - Aggiorna timestamp ultima attività
- `app/Http/Kernel.php` - Registrato middleware nel gruppo 'web'

### Views
- `resources/views/auth/sorting-hat.blade.php` - Cerimonia smistamento
- `resources/views/front/house/common-room.blade.php` - Sala comune

### Routes
- Aggiunte route in `routes/web.php` per:
  - Sorting Hat (GET/POST)
  - Common Room
  - API endpoints (messaggi, membri, annunci, eventi, statistiche)

### Migrations
1. `database/migrations/2025_01_15_000003_create_house_chat_table.php`
2. `database/migrations/2025_01_15_000004_add_last_activity_to_users_table.php`
3. `database/migrations/2025_01_15_000005_make_team_nullable_in_users_table.php`

## Setup Manuale del Database

Se non puoi eseguire `php artisan migrate`, esegui manualmente questo SQL:

```sql
-- 1. Crea tabelle per il sistema chat casa
CREATE TABLE IF NOT EXISTS `house_messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `house_id` int(10) unsigned NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `message_type` enum('text','system','announcement') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `is_pinned` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `house_messages_user_id_foreign` (`user_id`),
  KEY `house_messages_house_id_foreign` (`house_id`),
  CONSTRAINT `house_messages_house_id_foreign` FOREIGN KEY (`house_id`) REFERENCES `houses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `house_messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `house_events` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `house_id` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `type` enum('quest','competition','social','training') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'social',
  `event_date` timestamp NOT NULL,
  `max_participants` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `house_events_house_id_foreign` (`house_id`),
  CONSTRAINT `house_events_house_id_foreign` FOREIGN KEY (`house_id`) REFERENCES `houses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `house_event_participants` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `status` enum('going','maybe','declined') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'going',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `house_event_participants_event_id_foreign` (`event_id`),
  KEY `house_event_participants_user_id_foreign` (`user_id`),
  CONSTRAINT `house_event_participants_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `house_events` (`id`) ON DELETE CASCADE,
  CONSTRAINT `house_event_participants_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `house_announcements` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `house_id` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority` enum('urgent','high','medium','low') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium',
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `house_announcements_house_id_foreign` (`house_id`),
  CONSTRAINT `house_announcements_house_id_foreign` FOREIGN KEY (`house_id`) REFERENCES `houses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Aggiungi colonna last_activity alla tabella users
ALTER TABLE `users` ADD COLUMN `last_activity` timestamp NULL DEFAULT NULL AFTER `remember_token`;

-- Imposta timestamp corrente per gli utenti esistenti
UPDATE `users` SET `last_activity` = NOW() WHERE `last_activity` IS NULL;

-- 3. Rendi la colonna team nullable (per consentire registrazione senza casa)
ALTER TABLE `users` MODIFY `team` ENUM('1', '2', '3', '4') NULL;
```

## Flusso Utente

1. **Registrazione** → L'utente si registra (senza assegnazione casa)
2. **Reindirizzamento** → Viene reindirizzato automaticamente a `/sorting-hat`
3. **Cerimonia** → Completa il quiz del Cappello Parlante
4. **Assegnazione** → La casa viene assegnata e l'utente riceve:
   - 10 punti casa iniziali
   - Messaggio di benvenuto nella chat
   - Annuncio per gli altri membri
5. **Sala Comune** → L'utente può accedere a `/house/common-room` per:
   - Chattare con i compagni di casa
   - Vedere chi è online
   - Partecipare agli eventi
   - Leggere gli annunci

## API Endpoints

Tutti gli endpoints richiedono autenticazione e verificano che l'utente appartenga alla casa richiesta:

- `GET /api/house/messages?house_id={id}` - Carica tutti i messaggi
- `GET /api/house/messages/new?house_id={id}&after_id={id}` - Polling per nuovi messaggi
- `POST /api/house/messages` - Invia messaggio (body: house_id, message)
- `GET /api/house/members?house_id={id}` - Lista membri con stato online
- `GET /api/house/announcements?house_id={id}` - Annunci attivi
- `GET /api/house/events?house_id={id}` - Eventi futuri
- `POST /api/house/events/{id}/join` - RSVP a un evento
- `GET /api/house/stats?house_id={id}` - Statistiche casa

## Note Tecniche

### Campo Database
Il sistema usa il campo `team` della tabella `users` per identificare la casa (valori: 1-4):
- 1 = Grifondoro
- 2 = Serpeverde
- 3 = Corvonero
- 4 = Tassorosso

### Realtime Chat
La chat usa un sistema di polling con intervalli di 3 secondi. Per una soluzione più scalabile, considera l'uso di:
- Laravel Echo + Pusher
- Laravel WebSockets
- Socket.io

### Attività Utente
Un utente è considerato "online" se `last_activity` è più recente di 5 minuti fa.

### Colori Casa
Ogni casa ha colori specifici definiti in `common-room.blade.php`:
- **Grifondoro**: Gradiente rosso-arancione (#dc2626 → #ea580c)
- **Serpeverde**: Gradiente verde scuro (#166534 → #14532d)
- **Corvonero**: Gradiente blu (#1e40af → #1e3a8a)
- **Tassorosso**: Gradiente giallo (#eab308 → #ca8a04)

## Testing

Per testare il sistema:

1. Registra un nuovo utente
2. Verifica il reindirizzamento al Cappello Parlante
3. Completa il quiz
4. Verifica l'assegnazione della casa
5. Accedi alla sala comune
6. Testa la chat, gli eventi, gli annunci
7. Registra un secondo utente nella stessa casa per testare la chat multipla

## Troubleshooting

### Gli utenti esistenti non vedono il Cappello Parlante
Soluzione: Imposta `team = NULL` per gli utenti che devono essere riassegnati:
```sql
UPDATE users SET team = NULL WHERE id = {user_id};
```

### La chat non si aggiorna
- Verifica che JavaScript sia abilitato
- Controlla la console del browser per errori
- Verifica che gli endpoint API rispondano correttamente

### Utenti sempre offline
- Verifica che il middleware `UpdateLastActivity` sia registrato
- Controlla che la colonna `last_activity` esista nella tabella `users`

## Prossimi Sviluppi Suggeriti

1. **Quest Collaborative di Casa** - Missioni che richiedono cooperazione
2. **Classifica Casa** - Visualizzazione punti e achievement per casa
3. **Sistema Notifiche** - Alert per nuovi messaggi, eventi, annunci
4. **Ruoli Casa** - Prefetti, Capitano Quidditch, etc.
5. **Bacheca Casa** - Post permanenti oltre agli annunci
6. **Sfide tra Case** - Competizioni settimanali/mensili
7. **Sala delle Trofei** - Visualizzazione achievement di casa
8. **WebSockets** - Chat realtime vera (sostituire polling)
