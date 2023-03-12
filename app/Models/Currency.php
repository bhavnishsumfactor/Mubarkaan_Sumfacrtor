<?php

namespace App\Models;

use App\Models\YokartModel;
use App\Helpers\CurrencyHelper;
use DB;

class Currency extends YokartModel
{
    public $timestamps = false;

    protected $primaryKey = 'currency_id';

    protected $fillable = [
        'currency_id', 'currency_name', 'currency_code', 'currency_symbol', 'currency_position',
        'currency_value', 'currency_publish', 'currency_default', 'currency_view_default', 'currency_updated_at'
    ];

    public const CURRENCIES = [
        [
            'currency_id' => 1,
            'currency_name' => 'United States Dollar',
            'currency_code' => 'USD',
            'currency_symbol' => '$',
            'currency_position' => 0,
            'currency_value' => '1',
            'currency_publish' => 0,
            'currency_default' =>  0,
            'currency_view_default' => 0
        ],
        [
            'currency_id' => 2,
            'currency_name' => 'Indian Rupee',
            'currency_code' => 'INR',
            'currency_symbol' => '₹',
            'currency_position' => 0,
            'currency_value' => '1',
            'currency_publish' => 0,
            'currency_default' =>  0,
            'currency_view_default' => 0
        ],
        [
            'currency_id' => 3,
            'currency_name' => 'Iranian Rial',
            'currency_code' => 'IRR',
            'currency_symbol' => '﷼',
            'currency_position' => 0,
            'currency_value' => '1',
            'currency_publish' => 0,
            'currency_default' =>  0,
            'currency_view_default' => 0
        ],
        [
            'currency_id' => 4,
            'currency_name' => 'Mexican Peso',
            'currency_code' => 'MXN',
            'currency_symbol' => '$',
            'currency_position' => 0,
            'currency_value' => '1',
            'currency_publish' => 0,
            'currency_default' =>  0,
            'currency_view_default' => 0
        ],
        [
            'currency_id' => 5,
            'currency_name' => 'Euro',
            'currency_code' => 'EUR',
            'currency_symbol' => '€',
            'currency_position' => 0,
            'currency_value' => '1',
            'currency_publish' => 0,
            'currency_default' =>  0,
            'currency_view_default' => 0
        ],
        [
            'currency_id' => 6,
            'currency_name' => 'Japanese Yen',
            'currency_code' => 'JPY',
            'currency_symbol' => '¥',
            'currency_position' => 0,
            'currency_value' => '1',
            'currency_publish' => 0,
            'currency_default' =>  0,
            'currency_view_default' => 0
        ],
        [
            'currency_id' => 7,
            'currency_name' => 'Chinese Yuan',
            'currency_code' => 'CNY',
            'currency_symbol' => '¥',
            'currency_position' => 0,
            'currency_value' => '1',
            'currency_publish' => 0,
            'currency_default' =>  0,
            'currency_view_default' => 0
        ],
        [
            'currency_id' => 8,
            'currency_name' => 'Australian Dollar',
            'currency_code' => 'AUD',
            'currency_symbol' => '$',
            'currency_position' => 0,
            'currency_value' => '1',
            'currency_publish' => 0,
            'currency_default' =>  0,
            'currency_view_default' => 0
        ],
        [
            'currency_id' => 9,
            'currency_name' => 'South African Rand',
            'currency_code' => 'ZAR',
            'currency_symbol' => 'R',
            'currency_position' => 0,
            'currency_value' => '1',
            'currency_publish' => 0,
            'currency_default' =>  0,
            'currency_view_default' => 0
        ],
        [
            'currency_id' => 10,
            'currency_name' => 'New Zealand Dollar',
            'currency_code' => 'NZD',
            'currency_symbol' => '$',
            'currency_position' => 0,
            'currency_value' => '1',
            'currency_publish' => 0,
            'currency_default' =>  0,
            'currency_view_default' => 0
        ],
        [
            'currency_id' => 11,
            'currency_name' => 'UAE Dirham',
            'currency_code' => 'AED',
            'currency_symbol' => 't',
            'currency_position' => 0,
            'currency_value' => '1',
            'currency_publish' => 0,
            'currency_default' =>  0,
            'currency_view_default' => 0
        ]
    ];

    public static function getAddedCurrencies($request)
    {
        return Currency::select(DB::raw("currency_id, currency_name, currency_code, currency_symbol, currency_position,
        currency_value, currency_publish, currency_default, currency_view_default, FORMAT(currency_value, 2) as currency_value_round"))
            ->latest('currency_default')->get();
    }

    public static function getActiveCurrencies()
    {
        return Currency::select('currency_id', 'currency_name', 'currency_value', 'currency_code', 'currency_symbol')
        ->where('currency_publish', 1)
        ->latest('currency_view_default')->get();
    }

    public static function getCurrencies($fields, $conditions)
    {
        $query = Currency::select($fields);
        $query->where($conditions);
        return $query->get();
    }

    public static function getDefault()
    {
        return Currency::where('currency_view_default', config('constants.YES'))->first();
    }

    public static function getSystemDefault()
    {
        return Currency::where('currency_default', config('constants.YES'))->first();
    }

    public static function getRecordByCode($code)
    {
        return Currency::where('currency_publish', config('constants.YES'))
        ->where('currency_code', $code)->first();
    }

    public static function getRecordById($id)
    {
        return Currency::where('currency_publish', config('constants.YES'))
        ->where('currency_id', $id)->first();
    }

    public static function getAllCurrencies()
    {
        $allcurrencies = CurrencyHelper::fetchCurrenciesList();
        $existingCurrencies = Currency::pluck('currency_code')->toArray();
        return array_diff_key($allcurrencies, array_flip($existingCurrencies));
    }

    public static function updateExchangeRates($fromCode)
    {
        $currencylist= Currency::select(DB::raw("currency_id, CONCAT('".$fromCode."_', currency_code) as query_string"))
        ->where('currency_code', '<>', $fromCode)->get()->toArray();
        if (empty($currencylist)) {
            return false;
        }
        $currencyString = array_map(function ($key, $value) {
            return $value['query_string'];
        }, array_keys($currencylist), $currencylist);

        $currencyString = implode(",", $currencyString);
        $currencyString = "USD_INR";
        $newRates = CurrencyHelper::getExchangeRate($currencyString);
        if (empty($newRates)) {
            return false;
        }
        array_map(function ($currencyValue) use ($newRates) {
            array_map(function ($rateKey, $rateValue) use ($newRates, $currencyValue) {
                if ($currencyValue['query_string'] == $rateKey) {
                    Currency::where('currency_id', $currencyValue['currency_id'])
                        ->update(['currency_value' => $rateValue]);
                }
            }, array_keys($newRates), $newRates);
        }, $currencylist);
    }

    public static function disabledAllCurrencyExpectDefault()
    {
        $currencylist = Currency::where('currency_default', '<>', config('constants.YES'))->update(['currency_publish' => config('constants.NO')]);
    }

    public static function getDefaultCurrency()
    {
        return self::CURRENCIES;
    }
}
