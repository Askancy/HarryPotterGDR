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
        // Tabella materie
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Es: "Pozioni", "Difesa contro le Arti Oscure"
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('professor_name')->nullable();
            $table->string('classroom')->nullable(); // Es: "Aula Pozioni - Sotterranei"
            $table->enum('difficulty', ['beginner', 'intermediate', 'advanced', 'expert'])->default('beginner');
            $table->integer('min_level')->default(1);
            $table->string('icon')->nullable(); // FontAwesome icon class
            $table->string('color')->default('#6c757d'); // Colore tema materia

            // Ricompense base
            $table->integer('base_exp')->default(50);
            $table->integer('base_house_points')->default(5);

            // Quale abilità influenza
            $table->string('primary_skill')->nullable(); // Es: "intelligence", "dexterity"
            $table->string('secondary_skill')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Tabella lezioni giornaliere
        Schema::create('daily_lessons', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->enum('slot', ['morning', 'afternoon']); // Mattina o pomeriggio
            $table->integer('max_participants')->default(50);
            $table->integer('current_participants')->default(0);

            // Bonus del giorno
            $table->float('exp_multiplier')->default(1.0);
            $table->float('points_multiplier')->default(1.0);
            $table->string('special_bonus')->nullable(); // Es: "double_skill_points"

            $table->timestamps();

            $table->unique(['date', 'slot']);
            $table->index('date');
        });

        // Tabella partecipazioni lezioni
        Schema::create('lesson_attendances', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->foreignId('daily_lesson_id')->constrained('daily_lessons')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');

            // Performance nella lezione
            $table->enum('performance', ['poor', 'acceptable', 'good', 'excellent', 'outstanding'])->default('acceptable');
            $table->integer('exp_earned')->default(0);
            $table->integer('house_points_earned')->default(0);

            // Risposta a domande/quiz
            $table->integer('questions_answered')->default(0);
            $table->integer('correct_answers')->default(0);

            $table->timestamp('attended_at')->useCurrent();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['user_id', 'daily_lesson_id']);
            $table->index(['user_id', 'created_at']);
        });

        // Tabella progressi materie utente
        Schema::create('user_subject_progress', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');

            $table->integer('level')->default(1);
            $table->integer('experience')->default(0);
            $table->integer('required_exp')->default(100);
            $table->integer('total_lessons_attended')->default(0);
            $table->integer('total_questions_answered')->default(0);
            $table->integer('total_correct_answers')->default(0);

            // Performance media
            $table->float('average_score')->default(0);
            $table->enum('grade', ['T', 'D', 'P', 'A', 'E', 'O'])->default('A'); // Troll, Desolante, Penoso, Accettabile, Eccezionale, Oltre ogni previsione

            $table->timestamp('last_attended')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['user_id', 'subject_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_subject_progress');
        Schema::dropIfExists('lesson_attendances');
        Schema::dropIfExists('daily_lessons');
        Schema::dropIfExists('subjects');
    }
};
