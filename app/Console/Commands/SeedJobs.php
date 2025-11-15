<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Job;

class SeedJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:jobs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inizializza i lavori disponibili per guadagnare soldi';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Inizializzazione lavori...');

        Job::seedDefaultJobs();

        $count = Job::count();
        $this->info("✓ {$count} lavori disponibili");

        // Mostra elenco
        $jobs = Job::all();
        $this->table(
            ['ID', 'Nome', 'Tipo', 'Pagamento', 'Livello Min', 'Anno Min', 'Cooldown (ore)'],
            $jobs->map(function ($job) {
                return [
                    $job->id,
                    $job->name,
                    $job->type,
                    $job->base_payment . ' Galleons',
                    $job->min_level,
                    $job->min_grade,
                    $job->cooldown_hours,
                ];
            })->toArray()
        );

        return 0;
    }
}
