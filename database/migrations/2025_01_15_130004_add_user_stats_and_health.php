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
        Schema::table('users', function (Blueprint $table) {
            // Sistema vita/salute
            $table->integer('current_health')->default(100)->after('money');
            $table->integer('max_health')->default(100)->after('current_health');
            $table->integer('current_mana')->default(100)->after('max_health');
            $table->integer('max_mana')->default(100)->after('current_mana');

            // Statistiche base (se non già presenti)
            $table->integer('strength')->default(10)->after('max_mana');
            $table->integer('intelligence')->default(10)->after('strength');
            $table->integer('dexterity')->default(10)->after('intelligence');
            $table->integer('charisma')->default(10)->after('dexterity');
            $table->integer('defense')->default(10)->after('charisma');
            $table->integer('magic_power')->default(10)->after('defense');

            // Sistema perk
            $table->integer('perk_points')->default(0)->after('skill_points');
            $table->integer('total_perk_points_earned')->default(0)->after('perk_points');

            // Streak lezioni (giorni consecutivi)
            $table->integer('lesson_streak')->default(0)->after('total_perk_points_earned');
            $table->date('last_lesson_date')->nullable()->after('lesson_streak');

            // Performance accademica
            $table->float('academic_average')->default(0)->after('last_lesson_date'); // Media voti
            $table->integer('total_lessons_attended')->default(0)->after('academic_average');
        });

        // Tabella log recupero salute/mana
        Schema::create('health_regeneration_log', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->enum('type', ['health', 'mana', 'both']);
            $table->integer('amount');
            $table->string('source'); // Es: "rest", "potion", "spell", "natural"
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_regeneration_log');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'current_health',
                'max_health',
                'current_mana',
                'max_mana',
                'strength',
                'intelligence',
                'dexterity',
                'charisma',
                'defense',
                'magic_power',
                'perk_points',
                'total_perk_points_earned',
                'lesson_streak',
                'last_lesson_date',
                'academic_average',
                'total_lessons_attended'
            ]);
        });
    }
};
