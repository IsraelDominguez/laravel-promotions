<?php

namespace Genetsis\Promotions\Commands;

use Genetsis\Promotions\Seeds\PromotionTypesSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class InstallPromotions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'genetsis-admin:install-promotions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Promotions extension for Genetsis Admin';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {

            if ($this->confirm('Do you wish to install Promotion Extension')) {
                Artisan::call('migrate');
                Artisan::call('db:seed', ['--class' => PromotionTypesSeeder::class]);
            }

            $this->info('Instalation complete');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}