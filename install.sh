#!/bin/bash

# Harry Potter GDR - Installation Script
# Questo script automatizza l'installazione completa del sistema

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
PURPLE='\033[0;35m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

# Banner
echo -e "${PURPLE}"
echo "╔════════════════════════════════════════════════════════════╗"
echo "║                                                            ║"
echo "║        🧙 HARRY POTTER GDR - INSTALLER v1.0 🏰            ║"
echo "║                                                            ║"
echo "║            Benvenuto nel mondo magico!                     ║"
echo "║                                                            ║"
echo "╚════════════════════════════════════════════════════════════╝"
echo -e "${NC}"
echo ""

# Check PHP
echo -e "${CYAN}Controllo requisiti...${NC}"
if ! command -v php &> /dev/null; then
    echo -e "${RED}✗ PHP non trovato. Installa PHP >= 8.1${NC}"
    exit 1
fi

PHP_VERSION=$(php -r "echo PHP_VERSION;")
echo -e "${GREEN}✓ PHP $PHP_VERSION trovato${NC}"

# Check Composer
if ! command -v composer &> /dev/null; then
    echo -e "${YELLOW}⚠ Composer non trovato. Vuoi installarlo? (y/n)${NC}"
    read -r install_composer
    if [ "$install_composer" = "y" ]; then
        echo -e "${CYAN}Installazione Composer...${NC}"
        curl -sS https://getcomposer.org/installer | php
        sudo mv composer.phar /usr/local/bin/composer
        echo -e "${GREEN}✓ Composer installato${NC}"
    else
        echo -e "${RED}✗ Composer è necessario per continuare${NC}"
        exit 1
    fi
else
    COMPOSER_VERSION=$(composer --version | cut -d " " -f 3)
    echo -e "${GREEN}✓ Composer $COMPOSER_VERSION trovato${NC}"
fi

# Check Node (optional)
if command -v node &> /dev/null; then
    NODE_VERSION=$(node --version)
    echo -e "${GREEN}✓ Node $NODE_VERSION trovato${NC}"
else
    echo -e "${YELLOW}⚠ Node.js non trovato (opzionale per asset frontend)${NC}"
fi

echo ""

# Ask installation method
echo -e "${CYAN}Scegli il metodo di installazione:${NC}"
echo "1) Installazione Interattiva (Consigliata)"
echo "2) Installazione Rapida (usa configurazioni predefinite)"
echo "3) Installazione Manuale (solo dipendenze)"
echo ""
read -p "Scegli (1-3): " choice

case $choice in
    1)
        echo -e "${GREEN}Avvio installazione interattiva...${NC}"
        echo ""

        # Check if composer dependencies are installed
        if [ ! -d "vendor" ]; then
            echo -e "${CYAN}Installazione dipendenze Composer...${NC}"
            composer install --no-interaction
            echo -e "${GREEN}✓ Dipendenze installate${NC}"
        fi

        # Run Laravel installer
        php artisan hogwarts:install
        ;;

    2)
        echo -e "${GREEN}Avvio installazione rapida...${NC}"
        echo ""

        # Copy .env.example to .env
        if [ -f ".env.example" ]; then
            cp .env.example .env
            echo -e "${GREEN}✓ File .env creato${NC}"
        else
            echo -e "${YELLOW}⚠ .env.example non trovato, creazione .env minimo${NC}"
            cat > .env << 'EOF'
APP_NAME=HogwartsGDR
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

MAIL_MAILER=log
EOF
            echo -e "${GREEN}✓ File .env creato${NC}"
        fi

        # Install composer dependencies
        if [ ! -d "vendor" ]; then
            echo -e "${CYAN}Installazione dipendenze Composer...${NC}"
            composer install --no-interaction --prefer-dist
        fi

        # Generate key
        echo -e "${CYAN}Generazione chiave applicazione...${NC}"
        php artisan key:generate --force

        # Create SQLite database
        if grep -q "DB_CONNECTION=sqlite" .env; then
            touch database/database.sqlite
            echo -e "${GREEN}✓ Database SQLite creato${NC}"
        fi

        # Run migrations
        echo -e "${CYAN}Creazione tabelle database...${NC}"
        php artisan migrate --force

        # Seed database
        echo -e "${CYAN}Inizializzazione dati...${NC}"
        php artisan db:seed --class=SchoolEconomySeeder --force

        # Storage link
        php artisan storage:link

        # NPM (if available)
        if command -v npm &> /dev/null && [ -f "package.json" ]; then
            echo -e "${CYAN}Installare dipendenze NPM? (y/n)${NC}"
            read -r install_npm
            if [ "$install_npm" = "y" ]; then
                npm install
                echo -e "${GREEN}✓ Dipendenze NPM installate${NC}"
            fi
        fi

        echo ""
        echo -e "${GREEN}✓ Installazione rapida completata!${NC}"
        ;;

    3)
        echo -e "${GREEN}Installazione solo dipendenze...${NC}"
        echo ""

        # Composer
        if [ ! -d "vendor" ]; then
            echo -e "${CYAN}Installazione dipendenze Composer...${NC}"
            composer install --no-interaction
            echo -e "${GREEN}✓ Dipendenze Composer installate${NC}"
        fi

        # NPM (if available)
        if command -v npm &> /dev/null && [ -f "package.json" ]; then
            echo -e "${CYAN}Installare dipendenze NPM? (y/n)${NC}"
            read -r install_npm
            if [ "$install_npm" = "y" ]; then
                npm install
                echo -e "${GREEN}✓ Dipendenze NPM installate${NC}"
            fi
        fi

        echo ""
        echo -e "${YELLOW}⚠ Ricorda di:${NC}"
        echo "  1. Copiare .env.example in .env"
        echo "  2. Configurare il database in .env"
        echo "  3. Eseguire: php artisan key:generate"
        echo "  4. Eseguire: php artisan migrate"
        echo "  5. Eseguire: php artisan db:seed --class=SchoolEconomySeeder"
        ;;

    *)
        echo -e "${RED}Scelta non valida${NC}"
        exit 1
        ;;
esac

echo ""
echo -e "${PURPLE}╔════════════════════════════════════════════════════════════╗${NC}"
echo -e "${PURPLE}║              🎉 INSTALLAZIONE COMPLETATA! 🎉              ║${NC}"
echo -e "${PURPLE}╚════════════════════════════════════════════════════════════╝${NC}"
echo ""
echo -e "${CYAN}Prossimi passi:${NC}"
echo ""
echo -e "${YELLOW}1.${NC} Avvia il server:"
echo -e "   ${GREEN}php artisan serve${NC}"
echo ""
echo -e "${YELLOW}2.${NC} Visita l'applicazione:"
echo -e "   ${GREEN}http://localhost:8000${NC}"
echo ""
echo -e "${YELLOW}3.${NC} Documentazione disponibile:"
echo -e "   ${CYAN}• SCHOOL_ECONOMY_SYSTEM.md${NC} - Sistema scolastico ed economico"
echo -e "   ${CYAN}• LOGIN_REDIRECT_SYSTEM.md${NC} - Sistema autenticazione"
echo -e "   ${CYAN}• HOUSE_SYSTEM_SETUP.md${NC} - Sistema case"
echo ""
echo -e "${GREEN}Buon divertimento nel mondo magico di Hogwarts! 🧙✨${NC}"
echo ""
