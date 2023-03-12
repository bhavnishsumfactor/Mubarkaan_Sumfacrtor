<?php
namespace App\Helpers;

use App\Helpers\YokartHelper;
use App\Models\Configuration;

class CurrencyHelper extends YokartHelper
{
    public static function getExchangeRate($currencycode = 'USD_GBP')
    {
        $currencyUrl = 'https://free.currconv.com/api/v7/convert?q='.$currencycode
            .'&compact=ultra&apiKey='.Configuration::getValue('CURRENCY_API_KEY');
        return executeCurl($currencyUrl);
    }

    public static function fetchCurrenciesList()
    {
        $currencyUrl = 'https://free.currconv.com/api/v7/currencies?apiKey='.Configuration::getValue('CURRENCY_API_KEY');
        $currencies = executeCurl($currencyUrl);
        return $currencies['results'];
    }

    public static function convertCurrency($amount = 100)
    {
        $apikey = Configuration::getValue('CURRENCY_API_KEY');

        // $from_Currency = urlencode($from_currency);
        // $to_Currency = urlencode($to_currency);
        $query =  "USD_GBP";

        // change to the free URL if you're using the free version
        $json = file_get_contents("https://free.currencyconverterapi.com/api/v7/convert?q={$query}&compact=ultra&apiKey={$apikey}");
        $obj = json_decode($json, true);

        return $val = floatval($obj["$query"]);


        $total = $val * $amount;
        return number_format($total, 2, '.', '');
    }

    public static function getCurrencyConvertedAmount($pricepercase, $code, $exchange_rate)
    {
        $m1 = Money::USD($pricepercase);
        $converted = $m1->convert(new CurrencyConverter($code), (float)$exchange_rate);
        $converted = json_decode(json_encode($converted, true), true);
        return (float) number_format((float)$converted['amount'], 2, '.', '');
    }
}
