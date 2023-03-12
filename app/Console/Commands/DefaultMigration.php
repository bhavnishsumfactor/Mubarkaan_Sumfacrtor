<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class DefaultMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'defaultMigration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        /** Cms pages seeding pending */
        // Artisan::call('db:seed', ['--class' => 'BlankConfigurationTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'BlankUrlRewritesTableSeeder']);
        // Artisan::call('db:seed', ['--class' => 'AdminTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'StatesTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'CountriesTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'RegionsTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'StoreAddressTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'StoreTimingTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'BlankPagesTableSeeder']);
        // Artisan::call('db:seed', ['--class' => 'LanguageTableSeeder']);
        // Artisan::call('db:seed', ['--class' => 'CurrencyTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'PackageTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ShippingToLocationTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ShippingRateTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ShippingProfileZoneTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ShippingProfileToProductTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ShippingTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ReasonTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'EmailTemplateTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'SmsTemplateTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'NotificationTemplateTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ResourceTableSeeder']);
    }
}
