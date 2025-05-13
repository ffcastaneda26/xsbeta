<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class MigrateResetSeedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:reset-seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run migrate:rollback followed by migrate --seed';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info(__('Running migration reset and seed commands...'));

        // Run php artisan migrate:rollback
        $this->comment(__('Executing: php artisan migrate:rollback'));
        Artisan::call('migrate:rollback');
        $this->info(Artisan::output());

        // Run php artisan migrate --seed
        $this->comment(__('Executing: php artisan migrate --seed'));
        Artisan::call('migrate', ['--seed' => true]);
        $this->info(Artisan::output());

        $this->info(__('Migration rollback and seeding completed successfully!'));

        return 0;
    }
}
