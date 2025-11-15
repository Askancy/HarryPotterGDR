<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SchoolYear;
use App\Models\SchoolClass;
use App\Models\Subject;

class SchoolYearGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'school:generate-year {year_number?} {--theme=} {--activate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera un nuovo anno scolastico con calendario, termini ed eventi';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $yearNumber = $this->argument('year_number') ?? (SchoolYear::max('year_number') ?? 0) + 1;
        $theme = $this->option('theme');
        $activate = $this->option('activate');

        $this->info("Generazione Anno Scolastico {$yearNumber}...");

        // Seed subjects se non esistono
        if (Subject::count() === 0) {
            $this->info('Inizializzazione materie...');
            Subject::seedHogwartsSubjects();
            $this->info('✓ Materie create');
        }

        // Genera anno scolastico
        $schoolYear = SchoolYear::generateYear($yearNumber, $theme);
        $this->info("✓ Anno scolastico creato (ID: {$schoolYear->id})");

        // Genera classi
        $this->info('Generazione classi...');
        SchoolClass::generateForYear($schoolYear);
        $classesCount = $schoolYear->classes()->count();
        $this->info("✓ {$classesCount} classi create");

        // Attiva se richiesto
        if ($activate) {
            $schoolYear->activate();
            $this->info('✓ Anno scolastico attivato');
        }

        $this->info('');
        $this->line('Riepilogo:');
        $this->table(
            ['Campo', 'Valore'],
            [
                ['Anno', $schoolYear->year_number],
                ['Inizio', $schoolYear->start_date->format('d/m/Y')],
                ['Fine', $schoolYear->end_date->format('d/m/Y')],
                ['Tema', $schoolYear->theme ?? 'Nessuno'],
                ['Termini', $schoolYear->terms()->count()],
                ['Classi', $classesCount],
                ['Attivo', $schoolYear->is_active ? 'Sì' : 'No'],
            ]
        );

        return 0;
    }
}
