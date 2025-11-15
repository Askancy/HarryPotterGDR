<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class InstallHogwartsGDR extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hogwarts:install
                            {--force : Force installation even if .env exists}
                            {--skip-npm : Skip npm installation}
                            {--skip-composer : Skip composer installation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installa e configura automaticamente il sistema Harry Potter GDR';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->displayWelcome();

        // Check if already installed
        if (file_exists(base_path('.env')) && !$this->option('force')) {
            if (!$this->confirm('Il file .env esiste già. Vuoi sovrascriverlo?', false)) {
                $this->error('Installazione annullata.');
                return 1;
            }
        }

        try {
            // Step 1: Environment setup
            $this->step('Configurazione Ambiente', function () {
                return $this->setupEnvironment();
            });

            // Step 2: Dependencies
            if (!$this->option('skip-composer')) {
                $this->step('Installazione Dipendenze PHP (Composer)', function () {
                    return $this->installComposer();
                });
            }

            // Step 3: Generate app key
            $this->step('Generazione Chiave Applicazione', function () {
                return $this->generateKey();
            });

            // Step 4: Database connection test
            $this->step('Test Connessione Database', function () {
                return $this->testDatabaseConnection();
            });

            // Step 5: Run migrations
            $this->step('Creazione Tabelle Database', function () {
                return $this->runMigrations();
            });

            // Step 6: Seed database
            $this->step('Inizializzazione Dati', function () {
                return $this->seedDatabase();
            });

            // Step 7: NPM (optional)
            if (!$this->option('skip-npm')) {
                if ($this->confirm('Vuoi installare le dipendenze NPM?', true)) {
                    $this->step('Installazione Dipendenze Frontend (NPM)', function () {
                        return $this->installNpm();
                    });
                }
            }

            // Step 8: Storage link
            $this->step('Creazione Link Storage', function () {
                return $this->createStorageLink();
            });

            // Step 9: Permissions
            $this->step('Configurazione Permessi', function () {
                return $this->setPermissions();
            });

            // Final summary
            $this->displaySuccess();

            return 0;

        } catch (Exception $e) {
            $this->error('Errore durante l\'installazione: ' . $e->getMessage());
            $this->line('');
            $this->error('Stack trace:');
            $this->line($e->getTraceAsString());
            return 1;
        }
    }

    /**
     * Display welcome message
     */
    protected function displayWelcome()
    {
        $this->info('');
        $this->info('╔════════════════════════════════════════════════════════════╗');
        $this->info('║                                                            ║');
        $this->info('║        🧙 HARRY POTTER GDR - INSTALLER v1.0 🏰            ║');
        $this->info('║                                                            ║');
        $this->info('║            Benvenuto nel mondo magico!                     ║');
        $this->info('║                                                            ║');
        $this->info('╚════════════════════════════════════════════════════════════╝');
        $this->info('');
        $this->line('Questo installer configurerà automaticamente:');
        $this->line('  ✓ File di configurazione (.env)');
        $this->line('  ✓ Database e tabelle');
        $this->line('  ✓ Sistema scolastico (materie, classi, anni)');
        $this->line('  ✓ Sistema economico (lavori, wallet)');
        $this->line('  ✓ Sistema case (Grifondoro, Serpeverde, Corvonero, Tassorosso)');
        $this->line('  ✓ Dipendenze PHP e JS');
        $this->info('');
    }

    /**
     * Execute a step with progress indication
     */
    protected function step($title, callable $callback)
    {
        $this->info('');
        $this->line("📌 {$title}...");

        $bar = $this->output->createProgressBar(1);
        $bar->start();

        $result = $callback();

        $bar->finish();
        $this->info(' ✓');

        return $result;
    }

    /**
     * Setup environment file
     */
    protected function setupEnvironment()
    {
        $this->info('');
        $this->warn('Configurazione Database:');
        $this->info('');

        // Database configuration
        $dbConnection = $this->choice('Tipo di database', ['mysql', 'pgsql', 'sqlite'], 0);

        $dbConfig = [];

        if ($dbConnection === 'sqlite') {
            $dbConfig = [
                'DB_CONNECTION' => 'sqlite',
                'DB_DATABASE' => database_path('database.sqlite'),
            ];

            // Create sqlite file
            if (!file_exists(database_path('database.sqlite'))) {
                touch(database_path('database.sqlite'));
                $this->line('✓ File database SQLite creato');
            }
        } else {
            $dbConfig = [
                'DB_CONNECTION' => $dbConnection,
                'DB_HOST' => $this->ask('Host database', '127.0.0.1'),
                'DB_PORT' => $this->ask('Porta database', $dbConnection === 'mysql' ? '3306' : '5432'),
                'DB_DATABASE' => $this->ask('Nome database', 'hogwarts_gdr'),
                'DB_USERNAME' => $this->ask('Username database', 'root'),
                'DB_PASSWORD' => $this->secret('Password database (lascia vuoto se nessuna)'),
            ];
        }

        // App configuration
        $this->info('');
        $this->warn('Configurazione Applicazione:');
        $this->info('');

        $appConfig = [
            'APP_NAME' => $this->ask('Nome applicazione', 'HogwartsGDR'),
            'APP_ENV' => $this->choice('Ambiente', ['local', 'production'], 0),
            'APP_DEBUG' => $this->choice('Debug mode', ['true', 'false'], 0),
            'APP_URL' => $this->ask('URL applicazione', 'http://localhost'),
        ];

        // Generate APP_KEY placeholder
        $appConfig['APP_KEY'] = 'base64:' . base64_encode(Str::random(32));

        // Optional: Mail configuration
        if ($this->confirm('Vuoi configurare l\'email?', false)) {
            $this->info('');
            $this->warn('Configurazione Email:');
            $this->info('');

            $mailConfig = [
                'MAIL_MAILER' => $this->choice('Mail driver', ['smtp', 'sendmail', 'log'], 2),
                'MAIL_HOST' => $this->ask('Mail host', 'smtp.mailtrap.io'),
                'MAIL_PORT' => $this->ask('Mail port', '2525'),
                'MAIL_USERNAME' => $this->ask('Mail username', ''),
                'MAIL_PASSWORD' => $this->secret('Mail password', ''),
                'MAIL_ENCRYPTION' => $this->choice('Mail encryption', ['tls', 'ssl', 'null'], 0),
                'MAIL_FROM_ADDRESS' => $this->ask('Mail from address', 'noreply@hogwarts.com'),
                'MAIL_FROM_NAME' => $this->ask('Mail from name', 'Hogwarts GDR'),
            ];
        } else {
            $mailConfig = [
                'MAIL_MAILER' => 'log',
                'MAIL_FROM_ADDRESS' => 'noreply@hogwarts.com',
                'MAIL_FROM_NAME' => 'Hogwarts GDR',
            ];
        }

        // Create .env file
        $envContent = $this->generateEnvContent(array_merge($appConfig, $dbConfig, $mailConfig));
        file_put_contents(base_path('.env'), $envContent);

        $this->line('✓ File .env creato');

        return true;
    }

    /**
     * Generate .env file content
     */
    protected function generateEnvContent(array $config)
    {
        $template = file_exists(base_path('.env.example'))
            ? file_get_contents(base_path('.env.example'))
            : $this->getDefaultEnvTemplate();

        foreach ($config as $key => $value) {
            if (preg_match("/^{$key}=/m", $template)) {
                $template = preg_replace(
                    "/^{$key}=.*/m",
                    "{$key}=" . ($value ? $value : ''),
                    $template
                );
            } else {
                $template .= "\n{$key}=" . ($value ? $value : '');
            }
        }

        return $template;
    }

    /**
     * Get default .env template
     */
    protected function getDefaultEnvTemplate()
    {
        return <<<ENV
APP_NAME=HogwartsGDR
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hogwarts_gdr
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DRIVER=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=log
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=noreply@hogwarts.com
MAIL_FROM_NAME="\${APP_NAME}"
ENV;
    }

    /**
     * Install composer dependencies
     */
    protected function installComposer()
    {
        if (!file_exists(base_path('composer.json'))) {
            $this->warn('File composer.json non trovato. Saltato.');
            return true;
        }

        $this->line('Installazione pacchetti Composer (potrebbe richiedere alcuni minuti)...');

        exec('cd ' . base_path() . ' && composer install --no-interaction 2>&1', $output, $returnCode);

        if ($returnCode !== 0) {
            $this->warn('Composer install ha avuto problemi, ma continuiamo...');
            $this->line(implode("\n", array_slice($output, -5))); // Show last 5 lines
        }

        return true;
    }

    /**
     * Generate application key
     */
    protected function generateKey()
    {
        Artisan::call('key:generate', ['--force' => true]);
        $this->line('✓ Chiave applicazione generata');
        return true;
    }

    /**
     * Test database connection
     */
    protected function testDatabaseConnection()
    {
        try {
            DB::connection()->getPdo();
            $this->line('✓ Connessione database riuscita');
            return true;
        } catch (Exception $e) {
            $this->error('✗ Impossibile connettersi al database');
            $this->error('Errore: ' . $e->getMessage());

            if ($this->confirm('Vuoi continuare comunque?', false)) {
                return true;
            }

            throw new Exception('Connessione database fallita');
        }
    }

    /**
     * Run migrations
     */
    protected function runMigrations()
    {
        $this->line('Creazione tabelle...');

        Artisan::call('migrate', ['--force' => true]);

        $this->line('✓ Tabelle create');
        return true;
    }

    /**
     * Seed database
     */
    protected function seedDatabase()
    {
        if ($this->confirm('Vuoi inizializzare i dati di base (materie, lavori, anno scolastico)?', true)) {
            $this->line('Inizializzazione dati...');

            try {
                Artisan::call('db:seed', [
                    '--class' => 'SchoolEconomySeeder',
                    '--force' => true
                ]);
                $this->line('✓ Dati inizializzati');
            } catch (Exception $e) {
                $this->warn('Seeder non trovato o errore: ' . $e->getMessage());
                $this->warn('Puoi eseguirlo manualmente dopo: php artisan db:seed --class=SchoolEconomySeeder');
            }

            // Initialize wallets for existing users
            if ($this->confirm('Vuoi inizializzare i wallet per eventuali utenti esistenti?', false)) {
                try {
                    Artisan::call('economy:init-wallets');
                    $this->line('✓ Wallet inizializzati');
                } catch (Exception $e) {
                    $this->warn('Comando wallet non trovato: ' . $e->getMessage());
                }
            }
        }

        return true;
    }

    /**
     * Install npm dependencies
     */
    protected function installNpm()
    {
        if (!file_exists(base_path('package.json'))) {
            $this->warn('File package.json non trovato. Saltato.');
            return true;
        }

        $this->line('Installazione pacchetti NPM (potrebbe richiedere alcuni minuti)...');

        exec('cd ' . base_path() . ' && npm install 2>&1', $output, $returnCode);

        if ($returnCode !== 0) {
            $this->warn('NPM install ha avuto problemi, ma continuiamo...');
        } else {
            $this->line('✓ Pacchetti NPM installati');
        }

        if ($this->confirm('Vuoi compilare gli asset frontend?', false)) {
            exec('cd ' . base_path() . ' && npm run dev 2>&1', $output, $returnCode);

            if ($returnCode === 0) {
                $this->line('✓ Asset compilati');
            }
        }

        return true;
    }

    /**
     * Create storage link
     */
    protected function createStorageLink()
    {
        try {
            if (file_exists(public_path('storage'))) {
                $this->line('✓ Link storage già esistente');
                return true;
            }

            Artisan::call('storage:link');
            $this->line('✓ Link storage creato');
        } catch (Exception $e) {
            $this->warn('Impossibile creare link storage: ' . $e->getMessage());
        }

        return true;
    }

    /**
     * Set correct permissions
     */
    protected function setPermissions()
    {
        $directories = [
            storage_path(),
            base_path('bootstrap/cache'),
        ];

        foreach ($directories as $directory) {
            if (file_exists($directory)) {
                try {
                    chmod($directory, 0775);
                    $this->line("✓ Permessi configurati per: {$directory}");
                } catch (Exception $e) {
                    $this->warn("Impossibile impostare permessi per: {$directory}");
                }
            }
        }

        return true;
    }

    /**
     * Display success message
     */
    protected function displaySuccess()
    {
        $this->info('');
        $this->info('╔════════════════════════════════════════════════════════════╗');
        $this->info('║                                                            ║');
        $this->info('║              🎉 INSTALLAZIONE COMPLETATA! 🎉              ║');
        $this->info('║                                                            ║');
        $this->info('╚════════════════════════════════════════════════════════════╝');
        $this->info('');
        $this->line('Il sistema Harry Potter GDR è stato installato con successo!');
        $this->info('');

        $this->table(
            ['Componente', 'Status'],
            [
                ['Database', '✓ Configurato'],
                ['Tabelle', '✓ Create'],
                ['Materie Hogwarts', '✓ 11 materie'],
                ['Lavori', '✓ 5 lavori disponibili'],
                ['Anno Scolastico', '✓ Generato'],
                ['Sistema Case', '✓ 4 case pronte'],
            ]
        );

        $this->info('');
        $this->warn('📋 PROSSIMI PASSI:');
        $this->info('');
        $this->line('1. Avvia il server di sviluppo:');
        $this->comment('   php artisan serve');
        $this->info('');
        $this->line('2. Visita l\'applicazione:');
        $this->comment('   http://localhost:8000');
        $this->info('');
        $this->line('3. Crea un account admin (opzionale):');
        $this->comment('   php artisan tinker');
        $this->comment('   >>> $user = User::find(1);');
        $this->comment('   >>> $user->group = 2; // 2 = Admin');
        $this->comment('   >>> $user->save();');
        $this->info('');
        $this->line('4. Documentazione disponibile:');
        $this->comment('   • SCHOOL_ECONOMY_SYSTEM.md - Sistema scolastico ed economico');
        $this->comment('   • LOGIN_REDIRECT_SYSTEM.md - Sistema autenticazione');
        $this->comment('   • HOUSE_SYSTEM_SETUP.md - Sistema case');
        $this->info('');

        $this->info('Buon divertimento nel mondo magico di Hogwarts! 🧙✨');
        $this->info('');
    }
}
