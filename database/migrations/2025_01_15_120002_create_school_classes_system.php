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
        // Tabella materie (definizione unificata)
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // es. "Difesa contro le Arti Oscure"
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            // Campi per school_classes_system
            $table->integer('min_grade')->default(1); // anno minimo
            $table->integer('max_grade')->default(7); // anno massimo
            $table->boolean('is_core')->default(true); // materia obbligatoria

            // Campi per lessons_system
            $table->string('professor_name')->nullable();
            $table->string('classroom')->nullable();
            $table->enum('difficulty', ['beginner', 'intermediate', 'advanced', 'expert'])->default('beginner');
            $table->integer('min_level')->default(1);
            $table->string('color')->default('#6c757d');
            $table->integer('base_exp')->default(50);
            $table->integer('base_house_points')->default(5);
            $table->string('primary_skill')->nullable();
            $table->string('secondary_skill')->nullable();

            $table->boolean('is_active')->default(true);
            $table->string('icon')->nullable();
            $table->timestamps();

            $table->index('slug');
        });

        // Tabella classi (corso specifico)
        Schema::create('school_classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('school_year_id')->constrained()->onDelete('cascade');
            $table->integer('professor_id')->unsigned()->nullable();
            $table->integer('grade_level'); // 1-7
            $table->string('section')->nullable(); // es. "A", "B" per dividere classi
            $table->integer('max_students')->default(30);
            $table->string('classroom')->nullable(); // es. "Torre Nord, Piano 3"
            $table->json('schedule')->nullable(); // orario lezioni
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('professor_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['subject_id', 'school_year_id']);
            $table->index('grade_level');
        });

        // Tabella iscrizioni alle classi
        Schema::create('class_enrollments', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->foreignId('school_class_id')->constrained()->onDelete('cascade');
            $table->foreignId('school_year_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['enrolled', 'dropped', 'completed', 'failed'])->default('enrolled');
            $table->date('enrollment_date');
            $table->date('completion_date')->nullable();
            $table->integer('attendance_count')->default(0);
            $table->integer('absence_count')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['user_id', 'school_class_id', 'school_year_id'], 'user_class_year_unique');
            $table->index('status');
        });

        // Tabella voti
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained('class_enrollments')->onDelete('cascade');
            $table->integer('user_id')->unsigned();
            $table->foreignId('school_class_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['homework', 'quiz', 'midterm', 'final', 'project', 'participation']);
            $table->enum('grade_letter', ['O', 'E', 'A', 'P', 'D', 'T']); // Outstanding, Exceeds Expectations, Acceptable, Poor, Dreadful, Troll
            $table->integer('grade_numeric')->nullable(); // 0-100
            $table->integer('weight')->default(1); // peso del voto
            $table->text('feedback')->nullable();
            $table->integer('graded_by')->unsigned()->nullable();
            $table->date('assignment_date')->nullable();
            $table->date('graded_date');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('graded_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['user_id', 'school_class_id']);
            $table->index('grade_letter');
        });

        // Tabella performance annuale (per promozione/bocciatura)
        Schema::create('yearly_performances', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->foreignId('school_year_id')->constrained()->onDelete('cascade');
            $table->integer('grade_level'); // anno scolastico (1-7)
            $table->decimal('average_grade', 5, 2)->nullable(); // media voti
            $table->integer('total_classes')->default(0);
            $table->integer('passed_classes')->default(0);
            $table->integer('failed_classes')->default(0);
            $table->integer('total_absences')->default(0);
            $table->enum('status', ['in_progress', 'promoted', 'retained', 'graduated'])->default('in_progress');
            $table->text('notes')->nullable();
            $table->date('evaluation_date')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['user_id', 'school_year_id'], 'user_year_performance_unique');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yearly_performances');
        Schema::dropIfExists('grades');
        Schema::dropIfExists('class_enrollments');
        Schema::dropIfExists('school_classes');
        Schema::dropIfExists('subjects');
    }
};
