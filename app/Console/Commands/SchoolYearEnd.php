<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SchoolYear;
use App\Models\YearlyPerformance;
use App\Models\User;

class SchoolYearEnd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'school:end-year {year_id?} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Termina l\'anno scolastico, valuta gli studenti e gestisce promozioni/bocciature';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $yearId = $this->argument('year_id');
        $force = $this->option('force');

        // Ottieni anno da valutare
        if ($yearId) {
            $schoolYear = SchoolYear::find($yearId);
        } else {
            $schoolYear = SchoolYear::getActive();
        }

        if (!$schoolYear) {
            $this->error('Anno scolastico non trovato');
            return 1;
        }

        // Verifica se l'anno è finito
        if (!$schoolYear->isFinished() && !$force) {
            $this->error("L'anno scolastico non è ancora terminato (fine: {$schoolYear->end_date->format('d/m/Y')})");
            $this->info('Usa --force per forzare la valutazione');
            return 1;
        }

        $this->info("Valutazione Anno Scolastico {$schoolYear->year_number}...");
        $this->info('');

        // Genera performance per tutti gli studenti
        $this->info('Calcolo performance studenti...');
        YearlyPerformance::generateForYear($schoolYear);

        $totalStudents = User::where('current_school_year_id', $schoolYear->id)->count();
        $this->info("✓ Performance calcolate per {$totalStudents} studenti");

        // Valuta tutti
        $this->info('Valutazione studenti...');
        $results = YearlyPerformance::evaluateAll($schoolYear);

        $this->info('✓ Valutazione completata');
        $this->info('');

        // Mostra risultati
        $this->line('Risultati:');
        $this->table(
            ['Stato', 'Numero Studenti'],
            [
                ['Promossi', $results['promoted']],
                ['Bocciati', $results['retained']],
                ['Diplomati', $results['graduated']],
                ['Totale', array_sum($results)],
            ]
        );

        // Disattiva anno
        $schoolYear->update(['is_active' => false]);
        $this->info('✓ Anno scolastico disattivato');

        // Notifica studenti
        if ($this->confirm('Inviare notifiche ai studenti?', true)) {
            $this->notifyStudents($schoolYear);
        }

        return 0;
    }

    /**
     * Invia notifiche agli studenti
     */
    protected function notifyStudents(SchoolYear $schoolYear): void
    {
        $performances = YearlyPerformance::where('school_year_id', $schoolYear->id)->get();
        $bar = $this->output->createProgressBar($performances->count());

        $bar->start();

        foreach ($performances as $performance) {
            $user = $performance->user;

            $message = match($performance->status) {
                'promoted' => "Congratulazioni! Sei stato promosso al {$performance->grade_level}° anno!",
                'retained' => "Purtroppo sei stato bocciato. Dovrai ripetere il {$performance->grade_level}° anno.",
                'graduated' => "Congratulazioni! Ti sei diplomato a Hogwarts!",
                default => "Anno scolastico terminato.",
            };

            $user->notify(
                'school_year_end',
                'Anno Scolastico Terminato',
                $message,
                $performance->status === 'graduated' ? '🎓' : ($performance->status === 'promoted' ? '✅' : '❌'),
                '/school/performance',
                [
                    'status' => $performance->status,
                    'average_grade' => $performance->average_grade,
                ]
            );

            $bar->advance();
        }

        $bar->finish();
        $this->info('');
        $this->info('✓ Notifiche inviate');
    }
}
