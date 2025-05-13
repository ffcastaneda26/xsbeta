<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class OptimizeAllCommand extends Command
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
    protected $description = 'Run all optimization commands (optimize, optimize:clear, filament:optimize, filament:optimize-clear)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info(__('Running all optimization commands...'));

        // Run php artisan optimize
        $this->comment(__('Executing: php artisan optimize'));
        Artisan::call('optimize');
        $this->info(Artisan::output());

        // Run php artisan optimize:clear
        $this->comment(__('Executing: php artisan optimize:clear'));
        Artisan::call('optimize:clear');
        $this->info(Artisan::output());

        // Run php artisan filament:optimize
        $this->comment(__('Executing: php artisan filament:optimize'));
        Artisan::call('filament:optimize');
        $this->info(Artisan::output());

        // Run php artisan filament:optimize-clear
        $this->comment(__('Executing: php artisan filament:optimize-clear'));
        Artisan::call('filament:optimize-clear');
        $this->info(Artisan::output());

        $this->info(__('All optimization commands executed successfully!'));

        return 0;
    }
}
