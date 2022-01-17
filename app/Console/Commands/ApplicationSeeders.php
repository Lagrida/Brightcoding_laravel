<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ApplicationSeeders extends Command
{
    protected $signature = 'lagrida:fill_application';

    protected $description = 'Application make seeders';

    public function handle()
    {
        $bar = $this->output->createProgressBar(3);
        $bar->start();
        Artisan::call('db:seed --class=UserSeeder');
        $bar->advance();
        $this->newLine();
        $this->info('Users added successfuly');
        $this->newLine();
        Artisan::call('db:seed --class=TagSeeder');
        $bar->advance();
        $this->newLine();
        $this->info('Tags added successfuly');
        $this->newLine();
        Artisan::call('db:seed --class=PostSeeder');
        $bar->advance();
        $this->newLine();
        $this->info('Posts and comments added successfuly');
        $bar->finish();
        $this->newLine();
        return 0;
    }
}
