<?php

namespace App\Console\Commands;

use Database\Seeders\FakeUsersSeeder;
use Illuminate\Console\Command;

class CreateFakeUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:fake {count=100 : The number of fake users to create}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create fake users for testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creating fake users for testing...');

        $count = (int) $this->argument('count');
        
        // Update the count in the seeder class
        $seeder = new FakeUsersSeeder();
        $seeder->count = $count;
        $seeder->command = $this;
        $seeder->run();

        $this->info("Successfully created {$count} fake users!");
        return Command::SUCCESS;
    }
}
