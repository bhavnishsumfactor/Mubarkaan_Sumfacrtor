<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Configuration;
use App\Models\Currency;
use Carbon\Carbon;

class CurrencyExchangeRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currencyrates:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update currency rates';

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
        echo 'cron started<br>';
        $defaultCurrency = Currency::select('currency_code')->where('currency_default', 1)->first();
        if (!empty($defaultCurrency->currency_code)) {
            Currency::updateExchangeRates($defaultCurrency->currency_code);
        }
        echo 'Cron finished.';
    }
}
