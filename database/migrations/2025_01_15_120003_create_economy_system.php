<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabella wallet/portafoglio (tracking dettagliato del denaro)
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->decimal('galleons', 15, 2)->default(0);
            $table->decimal('sickles', 15, 2)->default(0);
            $table->decimal('knuts', 15, 2)->default(0);
            $table->decimal('total_earned', 15, 2)->default(0);
            $table->decimal('total_spent', 15, 2)->default(0);
            $table->timestamps();

            $table->index('galleons');
        });

        // Tabella transazioni
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', [
                'quest_reward',      // Ricompensa quest
                'level_up',          // Reward livello
                'event_reward',      // Reward evento
                'shop_purchase',     // Acquisto negozio
                'shop_sale',         // Vendita (se proprietario)
                'job_payment',       // Pagamento lavoro
                'transfer_sent',     // Trasferimento inviato
                'transfer_received', // Trasferimento ricevuto
                'tax',              // Tassa
                'fine',             // Multa
                'gift',             // Regalo
                'admin_adjustment'  // Aggiustamento admin
            ]);
            $table->decimal('amount', 15, 2);
            $table->enum('currency', ['galleons', 'sickles', 'knuts'])->default('galleons');
            $table->decimal('balance_after', 15, 2);
            $table->text('description');
            $table->foreignId('related_user_id')->nullable()->constrained('users')->onDelete('set null'); // per trasferimenti
            $table->foreignId('related_shop_id')->nullable()->constrained('location_shops')->onDelete('set null');
            $table->string('reference_type')->nullable(); // es. "App\Models\Quest"
            $table->unsignedBigInteger('reference_id')->nullable(); // ID dell'oggetto correlato
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index('type');
        });

        // Tabella inventario negozi
        Schema::create('shop_inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained('location_shops')->onDelete('cascade');
            $table->string('item_type'); // es. "App\Models\Objects", "App\Models\Spell"
            $table->unsignedBigInteger('item_id');
            $table->integer('quantity')->default(0);
            $table->decimal('base_price', 10, 2);
            $table->decimal('sell_price', 10, 2); // prezzo vendita al pubblico
            $table->integer('profit_margin')->default(20); // % margine
            $table->boolean('is_available')->default(true);
            $table->integer('restock_quantity')->default(10); // quantità riassortimento
            $table->timestamps();

            $table->unique(['shop_id', 'item_type', 'item_id'], 'shop_item_unique');
            $table->index('is_available');
        });

        // Tabella acquisti negozio
        Schema::create('shop_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained('location_shops')->onDelete('cascade');
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('shop_inventory_id')->constrained('shop_inventory')->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->decimal('shop_profit', 10, 2)->nullable(); // profitto del negozio
            $table->timestamps();

            $table->index(['shop_id', 'created_at']);
            $table->index('buyer_id');
        });

        // Tabella lavori disponibili
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->enum('type', ['daily', 'repeatable', 'one_time']);
            $table->decimal('base_payment', 10, 2);
            $table->integer('min_level')->default(1);
            $table->integer('min_grade')->default(1); // anno scolastico minimo
            $table->integer('duration_minutes')->default(60);
            $table->integer('cooldown_hours')->default(24);
            $table->json('requirements')->nullable(); // skill requirements
            $table->foreignId('location_id')->nullable()->constrained()->onDelete('set null');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('slug');
        });

        // Tabella lavori completati
        Schema::create('job_completions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('job_id')->constrained()->onDelete('cascade');
            $table->decimal('payment_received', 10, 2);
            $table->integer('quality_score')->nullable(); // 0-100
            $table->dateTime('started_at');
            $table->dateTime('completed_at');
            $table->dateTime('next_available_at')->nullable(); // per cooldown
            $table->timestamps();

            $table->index(['user_id', 'job_id']);
            $table->index('completed_at');
        });

        // Estendi users table per aggiungere money (se non esiste già)
        if (!Schema::hasColumn('users', 'money')) {
            Schema::table('users', function (Blueprint $table) {
                $table->decimal('money', 15, 2)->default(100)->after('level'); // Galleons iniziali
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_completions');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('shop_purchases');
        Schema::dropIfExists('shop_inventory');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('wallets');

        if (Schema::hasColumn('users', 'money')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('money');
            });
        }
    }
};
