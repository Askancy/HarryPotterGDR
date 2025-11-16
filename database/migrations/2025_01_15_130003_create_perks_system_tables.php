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
        // Tabella categorie perk
        Schema::create('perk_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Es: "Combattimento", "Magia", "Sociale"
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->string('color')->default('#6c757d');
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // Tabella perk/talenti
        Schema::create('perks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('perk_categories')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');

            // Requisiti
            $table->integer('required_level')->default(1);
            $table->foreignId('required_perk_id')->nullable()->constrained('perks')->onDelete('set null'); // Perk prerequisito
            $table->string('required_skill')->nullable(); // Es: "intelligence:50"
            $table->string('required_subject')->nullable(); // Es: "potions:5" (livello 5 in Pozioni)

            // Costo
            $table->integer('perk_points_cost')->default(1);

            // Effetti del perk (JSON)
            $table->text('effects'); // {"stat_bonuses": {"intelligence": 5}, "special": "potion_master"}

            // Tipo perk
            $table->enum('type', ['passive', 'active', 'toggle'])->default('passive');

            // Livelli perk (se upgradabile)
            $table->integer('max_rank')->default(1);

            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Tabella perk utente
        Schema::create('user_perks', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->foreignId('perk_id')->constrained('perks')->onDelete('cascade');

            $table->integer('rank')->default(1); // Livello del perk se upgradabile
            $table->boolean('is_equipped')->default(true); // Se il perk è attivo

            $table->timestamp('unlocked_at')->useCurrent();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['user_id', 'perk_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_perks');
        Schema::dropIfExists('perks');
        Schema::dropIfExists('perk_categories');
    }
};
