<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\Job;
use App\Models\SchoolYear;
use App\Models\SchoolClass;

class SchoolEconomySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Inizializzazione Sistema Scolastico ed Economico...');

        // 1. Seed materie
        $this->command->info('Creazione materie...');
        Subject::seedHogwartsSubjects();
        $this->command->info('✓ ' . Subject::count() . ' materie create');

        // 2. Seed lavori
        $this->command->info('Creazione lavori...');
        Job::seedDefaultJobs();
        $this->command->info('✓ ' . Job::count() . ' lavori creati');

        // 3. Genera primo anno scolastico
        $this->command->info('Generazione anno scolastico...');
        $schoolYear = SchoolYear::generateYear(1, 'Anno Inaugurale di Hogwarts');
        $this->command->info('✓ Anno scolastico creato');

        // 4. Genera classi
        $this->command->info('Generazione classi...');
        SchoolClass::generateForYear($schoolYear);
        $classCount = SchoolClass::count();
        $this->command->info('✓ ' . $classCount . ' classi create');

        // 5. Attiva anno scolastico
        $schoolYear->activate();
        $this->command->info('✓ Anno scolastico attivato');

        $this->command->info('');
        $this->command->line('========================================');
        $this->command->info('Sistema inizializzato con successo!');
        $this->command->line('========================================');
        $this->command->info('Materie: ' . Subject::count());
        $this->command->info('Lavori: ' . Job::count());
        $this->command->info('Classi: ' . $classCount);
        $this->command->info('Anno Scolastico Attivo: ' . $schoolYear->year_number);
    }
}
