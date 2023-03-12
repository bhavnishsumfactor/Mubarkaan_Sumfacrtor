<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencyTableSeeder extends Seeder
{
    public function run(string $currencyCode = "")
    {
        Currency::truncate();
        $sql =  base_path('public/dummydata/yokart_currencies.sql');
        \DB::unprepared(file_get_contents($sql));

        if (!empty($currencyCode)) {
            Currency::where('currency_default', '=', config('constants.YES'))->orWhere('currency_view_default', '=', config('constants.YES'))->update([
                'currency_default' => config('constants.NO'),
                'currency_view_default' => config('constants.NO')
            ]);
            $currency = Currency::where('currency_code', $currencyCode)->first();
            $currency->currency_default = config('constants.YES');
            $currency->currency_view_default = config('constants.YES');
            $currency->save();
        }
    }
}
