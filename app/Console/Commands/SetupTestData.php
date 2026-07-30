<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SetupTestData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:test-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set up test data including users, products, and other entities';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Create symbolic link for storage
        $this->info('Creating storage link...');
        Artisan::call('storage:link');
        $this->info(Artisan::output());
        
        // Download product images
        $this->info('Downloading product images...');
        Artisan::call('products:download-images', ['count' => 20]);
        $this->info(Artisan::output());
        
        // Run the migration fresh and seed
        $this->info('Running migrations and seeders...');
        Artisan::call('migrate:fresh', ['--seed' => true]);
        $this->info(Artisan::output());
        
        $this->info('Test data setup complete!');
        $this->info('Admin login: admin@afghanpro.af / admin123');
        $this->info('Support login: support@afghanpro.af / support123');
    }
} 