@echo off
REM Harry Potter GDR - Windows Installation Script
REM Questo script automatizza l'installazione completa del sistema su Windows

color 0A

echo ========================================================
echo.
echo         HARRY POTTER GDR - INSTALLER v1.0
echo.
echo            Benvenuto nel mondo magico!
echo.
echo ========================================================
echo.

REM Check PHP
where php >nul 2>nul
if %errorlevel% neq 0 (
    color 0C
    echo [ERRORE] PHP non trovato. Installa PHP ^>= 8.1
    echo Download: https://windows.php.net/download/
    pause
    exit /b 1
)

echo [OK] PHP trovato
php --version | findstr /C:"PHP"
echo.

REM Check Composer
where composer >nul 2>nul
if %errorlevel% neq 0 (
    color 0E
    echo [ATTENZIONE] Composer non trovato
    echo.
    echo Vuoi installarlo adesso? (S/N)
    set /p install_composer=
    if /i "%install_composer%"=="S" (
        echo Download Composer da: https://getcomposer.org/
        start https://getcomposer.org/download/
        echo.
        echo Dopo l'installazione, riavvia questo script.
        pause
        exit /b 1
    ) else (
        color 0C
        echo [ERRORE] Composer e' necessario per continuare
        pause
        exit /b 1
    )
)

echo [OK] Composer trovato
composer --version
echo.

REM Check Node (optional)
where node >nul 2>nul
if %errorlevel% neq 0 (
    color 0E
    echo [ATTENZIONE] Node.js non trovato (opzionale per asset frontend)
) else (
    echo [OK] Node.js trovato
    node --version
)
echo.

color 0B
echo Scegli il metodo di installazione:
echo.
echo 1) Installazione Interattiva (Consigliata)
echo 2) Installazione Rapida (usa configurazioni predefinite)
echo 3) Installazione Manuale (solo dipendenze)
echo.
set /p choice=Scegli (1-3):

if "%choice%"=="1" goto interactive
if "%choice%"=="2" goto quick
if "%choice%"=="3" goto manual

color 0C
echo Scelta non valida
pause
exit /b 1

:interactive
echo.
color 0A
echo [*] Avvio installazione interattiva...
echo.

REM Install composer dependencies if needed
if not exist "vendor" (
    echo [*] Installazione dipendenze Composer...
    call composer install --no-interaction
    if %errorlevel% neq 0 (
        color 0C
        echo [ERRORE] Errore durante l'installazione delle dipendenze
        pause
        exit /b 1
    )
    echo [OK] Dipendenze installate
)

REM Run Laravel installer command
echo.
echo [*] Avvio installer Laravel...
php artisan hogwarts:install

goto end

:quick
echo.
color 0A
echo [*] Avvio installazione rapida...
echo.

REM Copy .env.example to .env
if exist ".env.example" (
    copy .env.example .env
    echo [OK] File .env creato
) else (
    echo [ATTENZIONE] .env.example non trovato, creazione .env minimo
    (
        echo APP_NAME=HogwartsGDR
        echo APP_ENV=local
        echo APP_KEY=
        echo APP_DEBUG=true
        echo APP_URL=http://localhost
        echo.
        echo DB_CONNECTION=sqlite
        echo DB_DATABASE=database/database.sqlite
        echo.
        echo CACHE_DRIVER=file
        echo SESSION_DRIVER=file
        echo QUEUE_CONNECTION=sync
        echo.
        echo MAIL_MAILER=log
    ) > .env
    echo [OK] File .env creato
)

REM Install composer dependencies
if not exist "vendor" (
    echo [*] Installazione dipendenze Composer...
    call composer install --no-interaction --prefer-dist
    if %errorlevel% neq 0 (
        color 0C
        echo [ERRORE] Errore durante l'installazione
        pause
        exit /b 1
    )
)

REM Generate key
echo [*] Generazione chiave applicazione...
php artisan key:generate --force

REM Create SQLite database
findstr /C:"DB_CONNECTION=sqlite" .env >nul
if %errorlevel% equ 0 (
    if not exist "database\database.sqlite" (
        type nul > database\database.sqlite
        echo [OK] Database SQLite creato
    )
)

REM Run migrations
echo [*] Creazione tabelle database...
php artisan migrate --force
if %errorlevel% neq 0 (
    color 0E
    echo [ATTENZIONE] Errore durante le migrations
    pause
)

REM Seed database
echo [*] Inizializzazione dati...
php artisan db:seed --class=SchoolEconomySeeder --force
if %errorlevel% neq 0 (
    color 0E
    echo [ATTENZIONE] Errore durante il seeding
    echo Puoi eseguirlo manualmente: php artisan db:seed --class=SchoolEconomySeeder
)

REM Storage link
php artisan storage:link 2>nul

REM NPM (if available)
where npm >nul 2>nul
if %errorlevel% equ 0 (
    if exist "package.json" (
        echo.
        set /p install_npm=Installare dipendenze NPM? (S/N):
        if /i "%install_npm%"=="S" (
            call npm install
            echo [OK] Dipendenze NPM installate
        )
    )
)

echo.
color 0A
echo [OK] Installazione rapida completata!

goto end

:manual
echo.
color 0A
echo [*] Installazione solo dipendenze...
echo.

REM Composer
if not exist "vendor" (
    echo [*] Installazione dipendenze Composer...
    call composer install --no-interaction
    echo [OK] Dipendenze Composer installate
)

REM NPM (if available)
where npm >nul 2>nul
if %errorlevel% equ 0 (
    if exist "package.json" (
        echo.
        set /p install_npm=Installare dipendenze NPM? (S/N):
        if /i "%install_npm%"=="S" (
            call npm install
            echo [OK] Dipendenze NPM installate
        )
    )
)

echo.
color 0E
echo [ATTENZIONE] Ricorda di:
echo   1. Copiare .env.example in .env
echo   2. Configurare il database in .env
echo   3. Eseguire: php artisan key:generate
echo   4. Eseguire: php artisan migrate
echo   5. Eseguire: php artisan db:seed --class=SchoolEconomySeeder

:end
echo.
color 0D
echo ========================================================
echo.
echo          INSTALLAZIONE COMPLETATA!
echo.
echo ========================================================
echo.
color 0B
echo Prossimi passi:
echo.
echo 1. Avvia il server:
echo    php artisan serve
echo.
echo 2. Visita l'applicazione:
echo    http://localhost:8000
echo.
echo 3. Documentazione disponibile:
echo    - SCHOOL_ECONOMY_SYSTEM.md
echo    - LOGIN_REDIRECT_SYSTEM.md
echo    - HOUSE_SYSTEM_SETUP.md
echo.
color 0A
echo Buon divertimento nel mondo magico di Hogwarts!
echo.
pause
