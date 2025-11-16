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
        // Tabella anno scolastico
        Schema::create('school_years', function (Blueprint $table) {
            $table->id();
            $table->integer('year_number')->unique(); // es. 1, 2, 3...
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(false);
            $table->string('theme')->nullable(); // tema dell'anno es. "Anno del Torneo Tremaghi"
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('is_active');
        });

        // Tabella termini/periodi scolastici
        Schema::create('school_terms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_year_id')->constrained()->onDelete('cascade');
            $table->string('name'); // es. "Primo Trimestre", "Vacanze di Natale"
            $table->enum('type', ['term', 'holiday', 'exam_period'])->default('term');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('order')->default(0); // ordine nel calendario
            $table->timestamps();

            $table->index(['school_year_id', 'start_date']);
        });

        // Tabella eventi scolastici
        Schema::create('school_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_year_id')->constrained()->onDelete('cascade');
            $table->foreignId('school_term_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->enum('type', [
                'exam',           // Esami
                'quidditch',      // Partite Quidditch
                'feast',          // Banchetti
                'ceremony',       // Cerimonie (es. Smistamento)
                'tournament',     // Tornei
                'lesson',         // Lezioni speciali
                'special'         // Eventi speciali
            ]);
            $table->text('description')->nullable();
            $table->dateTime('event_date');
            $table->integer('duration_minutes')->default(60);
            $table->json('participants')->nullable(); // array di user_ids o house_ids
            $table->json('rewards')->nullable(); // ricompense per partecipazione
            $table->boolean('is_mandatory')->default(false);
            $table->boolean('completed')->default(false);
            $table->timestamps();

            $table->index(['school_year_id', 'event_date']);
            $table->index('type');
        });

        // Tabella partecipazione eventi
        Schema::create('event_participations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_event_id')->constrained()->onDelete('cascade');
            $table->integer('user_id')->unsigned();
            $table->enum('status', ['registered', 'attended', 'missed', 'excused'])->default('registered');
            $table->integer('score')->nullable(); // punteggio ottenuto nell'evento
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['school_event_id', 'user_id']);
            $table->index('status');
        });

        // Aggiungi colonna school_year_id alla tabella users
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('current_school_year_id')->nullable()->after('team')->constrained('school_years')->onDelete('set null');
            $table->integer('school_grade')->default(1)->after('current_school_year_id'); // 1-7 (anni a Hogwarts)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['current_school_year_id']);
            $table->dropColumn(['current_school_year_id', 'school_grade']);
        });

        Schema::dropIfExists('event_participations');
        Schema::dropIfExists('school_events');
        Schema::dropIfExists('school_terms');
        Schema::dropIfExists('school_years');
    }
};
