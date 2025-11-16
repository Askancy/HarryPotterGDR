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
            $table->integer('user_id')->unsigned()->unique();
            $table->decimal('galleons', 15, 2)->default(0);
            $table->decimal('sickles', 15, 2)->default(0);
            $table->decimal('knuts', 15, 2)->default(0);
            $table->decimal('total_earned', 15, 2)->default(0);
            $table->decimal('total_spent', 15, 2)->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('galleons');
        });

        // Tabella transazioni
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();
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
            $table->integer('related_user_id')->unsigned()->nullable(); // per trasferimenti
            $table->integer('related_shop_id')->unsigned()->nullable();
            $table->string('reference_type')->nullable(); // es. "App\Models\Quest"
            $table->unsignedBigInteger('reference_id')->nullable(); // ID dell'oggetto correlato
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('related_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('related_shop_id')->references('id')->on('location_shops')->onDelete('set null');
            $table->index(['user_id', 'created_at']);
            $table->index('type');
        });

        // Tabella inventario negozi
        Schema::create('shop_inventory', function (Blueprint $table) {
            $table->id();
            $table->integer('shop_id')->unsigned();
            $table->string('item_type'); // es. "App\Models\Objects", "App\Models\Spell"
            $table->unsignedBigInteger('item_id');
            $table->integer('quantity')->default(0);
            $table->decimal('base_price', 10, 2);
            $table->decimal('sell_price', 10, 2); // prezzo vendita al pubblico
            $table->integer('profit_margin')->default(20); // % margine
            $table->boolean('is_available')->default(true);
            $table->integer('restock_quantity')->default(10); // quantità riassortimento
            $table->timestamps();

            $table->foreign('shop_id')->references('id')->on('location_shops')->onDelete('cascade');
            $table->unique(['shop_id', 'item_type', 'item_id'], 'shop_item_unique');
            $table->index('is_available');
        });

        // Tabella acquisti negozio
        Schema::create('shop_purchases', function (Blueprint $table) {
            $table->id();
            $table->integer('shop_id')->unsigned();
            $table->integer('buyer_id')->unsigned();
            $table->foreignId('shop_inventory_id')->constrained('shop_inventory')->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->decimal('shop_profit', 10, 2)->nullable(); // profitto del negozio
            $table->timestamps();

            $table->foreign('shop_id')->references('id')->on('location_shops')->onDelete('cascade');
            $table->foreign('buyer_id')->references('id')->on('users')->onDelete('cascade');
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
            $table->integer('location_id')->unsigned()->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('location_id')->references('id')->on('locations')->onDelete('set null');
            $table->index('slug');
        });

        // Tabella lavori completati
        Schema::create('job_completions', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->foreignId('job_id')->constrained()->onDelete('cascade');
            $table->decimal('payment_received', 10, 2);
            $table->integer('quality_score')->nullable(); // 0-100
            $table->dateTime('started_at');
            $table->dateTime('completed_at');
            $table->dateTime('next_available_at')->nullable(); // per cooldown
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
