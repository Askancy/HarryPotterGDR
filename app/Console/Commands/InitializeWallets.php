<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Wallet;

class InitializeWallets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'economy:init-wallets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inizializza i wallet per tutti gli utenti esistenti';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Inizializzazione wallet utenti...');

        $users = User::whereDoesntHave('wallet')->get();
        $bar = $this->output->createProgressBar($users->count());

        $bar->start();

        foreach ($users as $user) {
            Wallet::getOrCreateForUser($user);
            $bar->advance();
        }

        $bar->finish();
        $this->info('');

        $totalWallets = Wallet::count();
        $this->info("✓ {$totalWallets} wallet inizializzati");

        return 0;
    }
}
