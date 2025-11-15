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
        // Tabella dei vestiti disponibili
        Schema::create('clothing', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('type', ['hat', 'robe', 'shirt', 'pants', 'shoes', 'accessory', 'cloak']); // Tipo di vestito
            $table->enum('rarity', ['common', 'rare', 'epic', 'legendary'])->default('common');
            $table->string('image')->nullable();

            // Bonus statistiche
            $table->integer('strength_bonus')->default(0);
            $table->integer('intelligence_bonus')->default(0);
            $table->integer('dexterity_bonus')->default(0);
            $table->integer('charisma_bonus')->default(0);
            $table->integer('defense_bonus')->default(0);
            $table->integer('magic_bonus')->default(0);

            // Requisiti
            $table->integer('required_level')->default(1);
            $table->enum('required_house', ['1', '2', '3', '4'])->nullable(); // Se null, tutti possono usarlo

            // Prezzo e disponibilità
            $table->bigInteger('price')->default(0);
            $table->boolean('is_available')->default(true);
            $table->boolean('is_tradeable')->default(true);

            $table->timestamps();
        });

        // Tabella inventario vestiti utente (many-to-many)
        Schema::create('user_clothing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('clothing_id')->constrained('clothing')->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->timestamp('acquired_at')->useCurrent();
            $table->timestamps();

            $table->unique(['user_id', 'clothing_id']);
        });

        // Tabella vestiti equipaggiati
        Schema::create('equipped_clothing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('slot', ['hat', 'robe', 'shirt', 'pants', 'shoes', 'accessory', 'cloak']);
            $table->foreignId('clothing_id')->constrained('clothing')->onDelete('cascade');
            $table->timestamp('equipped_at')->useCurrent();
            $table->timestamps();

            $table->unique(['user_id', 'slot']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipped_clothing');
        Schema::dropIfExists('user_clothing');
        Schema::dropIfExists('clothing');
    }
};
