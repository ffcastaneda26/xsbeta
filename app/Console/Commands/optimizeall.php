<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class OptimizeAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'optimize:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run all optimization and clear commands for Laravel and Filament';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Running optimization and clear commands...');

        // Run php artisan optimize
        $this->comment('Executing: php artisan optimize');
        Artisan::call('optimize');
        $this->info(Artisan::output());

        // Run php artisan optimize:clear
        $this->comment('Executing: php artisan optimize:clear');
        Artisan::call('optimize:clear');
        $this->info(Artisan::output());

        // Clear the terminal screen
        $this->comment('Clearing terminal screen...');
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // For Windows
            system('cls');
        } else {
            // For Unix-like systems (Linux, macOS)
            system('clear');
        }

        $this->info('All optimization commands executed successfully!');

        return Command::SUCCESS;
    }
}
