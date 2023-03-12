<?php

namespace App\Http\Controllers\Admin;

use App\Models\Currency;
use App\Models\Configuration;
use App\Models\Admin\AdminRole;
use Illuminate\Http\Request;
use App\Http\Requests\CurrencyRequest;
use App\Http\Controllers\Admin\AdminYokartController;
use Carbon\Carbon;
use App\Helpers\CurrencyHelper;
use View;

class CurrencyController extends AdminYokartController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
        $this->middleware(function ($request, $next) {
            if (!canRead(AdminRole::LOCALIZATION)) {
                return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
            }
            return $next($request);
        });
    }

    public function getListing(Request $request)
    {
        $currencies = Currency::getAddedCurrencies($request);
        $status = ($currencies->count() > 0) ? true : false;
        $currencyApiKey = Configuration::getValue('CURRENCY_API_KEY');
        return jsonresponse($status, '', ['data' => $currencies, 'currencyApiKey' => $currencyApiKey]);
    }

    public function fetchallCurrencies()
    {
        $currencies = Currency::getAllCurrencies();
        $currenciesArray = array_combine(
            array_keys($currencies),
            array_map(function ($currencyValue) {
                return $currencyValue['currencyName'];
            }, $currencies)
        );
        return jsonresponse(true, '', ['currencies' => $currencies, 'currenciesArray' => $currenciesArray]);
    }

    public function store(CurrencyRequest $request)
    {
        if (!canWrite(AdminRole::LOCALIZATION)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $requestData = $request->all();
        $defaultCurrency = Currency::select('currency_code')->where('currency_default', 1)->first();
        if (!empty($defaultCurrency->currency_code)) {
            $queryString = $defaultCurrency->currency_code . '_' . $requestData['currency_code'];
            $currentRate = CurrencyHelper::getExchangeRate($queryString);
            $requestData['currency_value'] = $currentRate[$queryString];
        } else { //if this is first record
            $requestData['currency_default'] = 1;
            $requestData['currency_value'] = 1;
        }
        $requestData['currency_updated_at'] = Carbon::now();
        Currency::create($requestData);
        return jsonresponse(true, __('NOTI_CURRENCY_ADDED'));
    }

    public function destroy($id)
    {
        if (!canWrite(AdminRole::LOCALIZATION)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        Currency::find($id)->delete();
        return jsonresponse(true, __('NOTI_CURRENCY_DELETED'));
    }

    public function updateStatus(Request $request, $currencyId)
    {
        $publishStatus = ($request->input('status') == 'true') ? 1 : 0;
        Currency::where('currency_id', $currencyId)->update(['currency_publish' => $publishStatus]);
        $message = ($request->input('status') == 'true') ? __('NOTI_CURRENCY_PUBLISHED') : __('NOTI_CURRENCY_UNPUBLISHED');
        return jsonresponse(true, $message);
    }

    public function markAsDefault(Request $request, $currencyId)
    {
        Currency::where('currency_view_default', 1)->update(['currency_view_default' => 0]);
        Currency::where('currency_id', $currencyId)->update(['currency_publish' => 1, 'currency_view_default' => 1]);
        $request->session()->forget('currency');
        return jsonresponse(true, __('NOTI_CURRENCY_MARKED_DEFAULT'));
    }
}
