<?php

namespace App\Models;

use App\Models\YokartModel;
use App\Models\Package;
use App\Helpers\PaymentGatewayHelper;

class UserCard extends YokartModel
{
    public $timestamps = false;
    
    protected $primaryKey = 'ucard_id';

    protected $fillable = [
        'ucard_user_id', 'ucard_slug', 'ucard_customer_id'
    ];
    
    public static function getCustomerId($user_id, $type)
    {
        return UserCard::select('ucard_id', 'ucard_customer_id')->where('ucard_user_id', $user_id)->where('ucard_slug', $type)->first();
    }

    public static function getCardsListing($userId)
    {
        $data = [];
        $data['defaultCardId'] = "";
        $data['cards'] = [];
        $data['type'] = '';
        $package = Package::select('pkg_slug')->where('pkg_type', Package::PKG_TYPE['paymentGateways'])->where('pkg_publish', config('constants.YES'))->where('pkg_card_type', config('constants.YES'))->first();
        if (isset($package->pkg_slug) && !empty($package->pkg_slug)) {
            $payment = new PaymentGatewayHelper($package->pkg_slug);
            $data['cards'] = $payment->getCards($userId);
            $data['type'] = $package->pkg_slug;
            if (($data['type'] == 'Stripe' || $data['type'] == 'Bluesnap') && !empty($data['cards'])) {
                $data['defaultCardId'] = $payment->getDefaultCard($userId);
            }
        }
        return $data;
    }
    public static function prepareCardData($packageSlug, $card, $defaultCardId = "")
    {
        $updatedCard = [];
        switch ($packageSlug) {
            case 'Stripe':
                $updatedCard = [
                    'card_name' => $card->name,
                    'card_type' => $card->brand,
                    'card_number' => $card->last4,
                    'card_id' => $card->id,
                    'card_expire' => $card->exp_month . '/' . $card->exp_year,
                    'is_default_card' => (!empty($data['default_card_id']) && $data['default_card_id'] == $card->id) ? true : ""
                ];
                break;
            case 'AuthorizeDotNet':
                $updatedCard = [
                    'card_name' => $card->getBillTo()->getFirstName(),
                    'card_type' => $card->getPayment()->getCreditCard()->getCardType(),
                    'card_number' => $card->getPayment()->getCreditCard()->getCardNumber(),
                    'card_id' => $card->getCustomerPaymentProfileId(),
                    'card_expire' => '',
                    'is_default_card' => $card->getDefaultPaymentProfile()
                ];
                break;
            case 'Bluesnap':
                $updatedCard = [
                    'card_name' => $card->name,
                    'card_type' => $card->cardType,
                    'card_number' => $card->last4,
                    'card_id' => $card->cardId,
                    'card_expire' => $card->exp_month . '/' . $card->exp_year,
                    'is_default_card' => ($card->isDefaultCard == 1) ? true : ""
                ];
                break;
        }
        return $updatedCard;
    }
    public static function getCardsForApp($userId)
    {
        $data = [];
        $data['default_card_id'] = "";
        $data['cards'] = [];
        $data['type'] = '';
        $package = Package::select('pkg_slug')->where('pkg_type', Package::PKG_TYPE['paymentGateways'])->where('pkg_publish', config('constants.YES'))->where('pkg_card_type', config('constants.YES'))->first();
        if (isset($package->pkg_slug) && !empty($package->pkg_slug)) {
            $payment = new PaymentGatewayHelper($package->pkg_slug);
            $cards = $payment->getCards($userId);
            $data['type'] = $package->pkg_slug;
            if (($data['type'] == 'Stripe' || $data['type'] == 'Bluesnap') && !empty($cards)) {
                $data['default_card_id'] = $payment->getDefaultCard($userId);
            }
            $updatedCards = [];
            foreach ($cards as $key => $card) {
                $updatedCards[] = UserCard::prepareCardData($package->pkg_slug, $card, $data['default_card_id']);
            }
            $data['cards'] = $updatedCards;
        }
        return $data;
    }
}
