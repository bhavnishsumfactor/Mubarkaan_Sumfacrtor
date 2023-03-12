<?php

namespace App\Http\Controllers;

use App\Http\Controllers\YokartController;
use Illuminate\Http\Request;
use App\Http\Requests\UserCardRequest;
use App\Models\UserCard;
use App\Models\Package;
use App\Helpers\PaymentGatewayHelper;
use Illuminate\Support\Facades\Validator;
use Stripe;
use Carbon\Carbon;
use Inertia\Inertia;
use Session;
use Redirect;

class UserCardController extends YokartController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        return Inertia::render('Account/Cards/Index');
    }

    public function cartListing()
    {
        $cards = UserCard::getCardsListing($this->user->user_id);
        return jsonresponse(true, 'null', $cards);
    }
    public function cardDelete(Request $request, $cardId)
    {
        $package = Package::select('pkg_slug')->where('pkg_type', Package::PKG_TYPE['paymentGateways'])->where('pkg_card_type', config('constants.YES'))->where('pkg_publish', config('constants.YES'))->first();
        if (isset($package->pkg_slug) && !empty($package->pkg_slug)) {
            $payment = new PaymentGatewayHelper($package->pkg_slug);
            if ($payment->deleteCard($cardId, $this->user->user_id)) {
                $data =  UserCard::getCardsListing($this->user->user_id);
                return jsonresponse(true, __('NOTI_CARD_REMOVED'));
            } else {
                return jsonresponse(false, __('NOTI_SOMETHING_WENT_WRONG'));
            }
        }
    }

    public function createCard()
    {
        $error = '';
        if (Session::has('gatewayErrors')) {
            $error = Session::get('gatewayErrors');
        }
        return Inertia::render('Account/Cards/Form', ['gatewayErrors' => $error]);
    }

    public function saveCard(UserCardRequest $request)
    {
        $package = Package::select('pkg_slug')->where('pkg_type', Package::PKG_TYPE['paymentGateways'])->where('pkg_publish', config('constants.YES'))->where('pkg_card_type', config('constants.YES'))->first();

        if (isset($package->pkg_slug) && !empty($package->pkg_slug)) {
            $payment = new PaymentGatewayHelper($package->pkg_slug);
            $response = $payment->saveCard($request->toArray(), $this->user->user_id);
             if ($response['status']) {
                $data =  UserCard::getCardsListing($this->user->user_id);
                return Redirect::route('cards');
            } else {
                return Redirect::route('createCard')->with('gatewayErrors', $response['message']);
            }
        }
    }

    public function updateDefaultCard(Request $request)
    {
        $package = Package::select('pkg_slug')->where('pkg_type', Package::PKG_TYPE['paymentGateways'])->where('pkg_publish', config('constants.YES'))->where('pkg_card_type', config('constants.YES'))->first();
        if (isset($package->pkg_slug) && !empty($package->pkg_slug)) {
            $payment = new PaymentGatewayHelper($package->pkg_slug);
            if ($payment->updateDefaultCard($this->user->user_id, $request['cardId'])) {
                $data =  UserCard::getCardsListing($this->user->user_id);
                return jsonresponse(true, __('NOTI_DEFAULT_CARD_UPDATED'));
            } else {
                return jsonresponse(false, __('NOTI_SOMETHING_WENT_WRONG'));
            }
        }
    }
}
