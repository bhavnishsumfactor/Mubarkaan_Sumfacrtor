<?php

namespace YoKartPaymentGateways\Bluesnap\Http\Controllers;

use App\Http\Controllers\PackageYokartController;
use YoKartTax\SystemTaxManagement\Models\SystemTax;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException; // this is for the validation
use App\Models\Order;
use App\Models\UserCard;
use App\Models\User;
use Bluesnap;
use DB;
use App\Models\Package;

class BluesnapController extends PackageYokartController
{
    const BLUESNAP_SANDBOX_URL = "https://sandbox.bluesnap.com/services/2";
    const BLUESNAP_PRODUCTION_URL = "https://ws.bluesnap.com/services/2";

    public static function getApiUrl()
    {
        $record = Package::getPackageBySlug('Bluesnap');
        if ($record['pkg_environment'] == Package::PKG_ENVIRONMENT_SANDBOX) {
            return self::BLUESNAP_SANDBOX_URL;
        }
        return self::BLUESNAP_PRODUCTION_URL;
    }
    public static function generateToken()
    {
        $record = Package::getRecordBySlug('Bluesnap');
        return base64_encode($record['USERNAME'] . ":" . $record['PASSWORD']);
    }
    public static function makePayment($request, $orderId, $amount = 0)
    {
        $orderInfo = Order::getRecordById($orderId);
        $orderAddress = $orderInfo['address'][0];
        if ($amount == 0) {
            $amount = $orderInfo['order_net_amount'];
        }
        if ($amount > 0) {

            $order_actual_paid = number_format(round($amount, 2), 2, ".", "");
            $params = array(
                "cardTransactionType" => "AUTH_CAPTURE",
                "softDescriptor" => "DescTest",
                "merchantOrderId" => $orderId,
                "currency" => $orderInfo["order_currency_code"],
                "amount" => $order_actual_paid,
                "cardHolderInfo" => array(
                    "firstName" => $request['name'],
                    "lastName" => "",
                    "zipCode" => $orderAddress['oaddr_postal_code']
                ),
                "storeCard" => (isset($request['save-card']) && $request['save-card'] == 1) ? true : false,
                "creditCard" => array(
                    "cardNumber" => $request['number'],
                    "securityCode" => $request['cvv'],
                    "expirationMonth" => $request['exp_month'],
                    "expirationYear" => $request['exp_year'],
                )
            );
            try {

                $vaultedShopperId = self::getUserVaultedShopperId($orderInfo['user_id']);
                if (!$vaultedShopperId) {
                    $vaultedShopperId = self::createVaultedShopper($orderInfo['user_id']);
                    if (!$vaultedShopperId) {
                        return ['status' => false, 'message' => __('Invalid payment gateway configurations, Please contact seller.')];
                    }
                }
                $params['vaultedShopperId'] = $vaultedShopperId;
                $response = self::curlRequest('POST', 'transactions', $params);
                if (!$response || isset($response->errorCode)) {
                    return ['status' => false, 'message' => __('Invalid payment gateway configurations, Please contact seller.')];
                }
                $data = array();
                if (isset($response->processingInfo) && $response->processingInfo->processingStatus == 'success') {

                    $data['amount'] = $order_actual_paid;
                    $data['id'] = $response->transactionId;
                    $data['description'] = $response->softDescriptor;
                    $data['payment_method_details']['card'] = [
                        'last4' => $response->creditCard->cardLastFourDigits,
                        'network' => $response->creditCard->cardType
                    ];
                    return ['status' => true, 'data' => $data];
                } else {
                    return ['status' => false, 'message' => $response->message[0]->description];
                }
            } catch (Exception $e) {

                return ['status' => false, 'message' => $e->message];
            }
        }
        return ['status' => false, 'message' => __('Invalid request')];
    }
    public static function makePaymentByCardId($cardData, $orderId)
    {
        $arrCardData = explode("-", $cardData);
        if (!isset($arrCardData[0]) || !isset($arrCardData[1])) {
            return ['status' => false, 'message' => __('Invalid card details')];
        }
        $orderInfo = Order::getRecordById($orderId);
        if ($orderInfo['order_net_amount'] > 0) {
            $vaultedShopperId = self::getUserVaultedShopperId($orderInfo['user_id']);
            $order_actual_paid = number_format(round($orderInfo['order_net_amount'], 2), 2, ".", "");
            $params = array(
                "cardTransactionType" => "AUTH_CAPTURE",
                "softDescriptor" => "DescTest",
                "merchantOrderId" => $orderId,
                "currency" => $orderInfo["order_currency_code"],
                "amount" => $order_actual_paid,
                "vaultedShopperId" => $vaultedShopperId,
                "creditCard" => array(
                    "cardLastFourDigits" => $arrCardData[0],
                    "cardType" => $arrCardData[1]
                )
            );
            try {
                $response = self::curlRequest('POST', 'transactions', $params);
                if (!$response  || isset($response->errorCode)) {
                    return ['status' => false, 'message' => __('Invalid payment gateway configurations, Please contact seller.')];
                }
                $data = array();
                if ($response->processingInfo->processingStatus == 'success') {

                    $data['amount'] = $order_actual_paid;
                    $data['id'] = $response->transactionId;
                    $data['description'] = $response->softDescriptor;
                    $data['payment_method_details']['card'] = [
                        'last4' => $response->creditCard->cardLastFourDigits,
                        'network' => $response->creditCard->cardType
                    ];
                    return ['status' => true, 'data' => $data];
                } else {
                    return ['status' => false, 'data' => (array) $response];
                }
            } catch (Exception $e) {

                return ['status' => false, 'message' => $e->message];
            }
        }
        return ['status' => false, 'message' => __('Invalid request')];
    }

    public static function paymentRefund($transactionId, $amount)
    {
        $params = array("amount" => $amount);
        try {
            $data = array();
            $response = self::curlRequest('POST', 'transactions/refund/' . $transactionId, $params);
            if (!$response || isset($response->errorCode)) {
                return ['status' => false, 'message' => __('Invalid payment gateway configurations, Please contact seller.')];
            }
            if (isset($response->refundTransactionId)) {

                $data['id'] = $response->refundTransactionId;
                $data['amount'] = $amount;
                return ['status' => true, 'data' => $data];
            } else {
                $message = __('Invalid request');
                $error = $response->message[0]->errorName;
                switch ($error) {
                    case 'INVOICE_ALREADY_FULLY_REFUNDED':
                        $message = $response->message[0]->description;
                    case 'UNAUTHORIZED_REFUND_TRANSACTION':
                        $message = $response->message[0]->description;
                    default:
                        $message = __('Invalid request');
                }
                return ['status' => false, 'data' => array("message" => $message)];
            }
        } catch (Exception $e) {

            return ['status' => false, 'message' => $e->message];
        }
    }
    public static function defaultCard($userId)
    {
        $customerData = UserCard::getCustomerId($userId, "Bluesnap");
        if (isset($customerData->ucard_customer_id) && !empty($customerData->ucard_customer_id)) {
            $response = self::curlRequest('GET', 'vaulted-shoppers/' . $customerData->ucard_customer_id);
            if (!$response || isset($response->errorCode)) {
                return '';
            }
            if (isset($response->paymentSources->creditCardInfo)) {
                if (isset($response->chosenPaymentMethod->creditCard)) {
                    $defaultCardId = $response->chosenPaymentMethod->creditCard->cardLastFourDigits . '-' . $response->chosenPaymentMethod->creditCard->cardType;
                    return $defaultCardId;
                }
            }
        }
        return '';
    }

    public static function getAllCards($userId)
    {
        $customerData = UserCard::getCustomerId($userId, "Bluesnap");
        if (isset($customerData->ucard_customer_id) && !empty($customerData->ucard_customer_id)) {
            $response = self::curlRequest('GET', 'vaulted-shoppers/' . $customerData->ucard_customer_id);
            if (!$response || isset($response->errorCode)) {
                return [];
            }
            $cards = array();
            if (isset($response->paymentSources->creditCardInfo)) {
                $defaultCardId = '';
                if (isset($response->chosenPaymentMethod->creditCard)) {
                    $defaultCardId = $response->chosenPaymentMethod->creditCard->cardLastFourDigits . '-' . $response->chosenPaymentMethod->creditCard->cardType;
                }

                foreach ($response->paymentSources->creditCardInfo as $creditCard) {
                    $card = (object) [];
                    $card->name = isset($creditCard->billingContactInfo->firstName) ? $creditCard->billingContactInfo->firstName : '';
                    if (isset($creditCard->billingContactInfo->lastName)) $card->name .= ' ' . $creditCard->billingContactInfo->lastName;
                    $card->cardType = $creditCard->creditCard->cardType;
                    $card->last4 = $creditCard->creditCard->cardLastFourDigits;
                    $card->id = $card->cardId = $creditCard->creditCard->cardLastFourDigits . "-" . $creditCard->creditCard->cardType;
                    $card->exp_month = $creditCard->creditCard->expirationMonth;
                    $card->exp_year = $creditCard->creditCard->expirationYear;
                    $card->country = $creditCard->creditCard->issuingCountryCode;
                    $card->brand = $creditCard->creditCard->cardType;
                    $card->isDefaultCard = 0;
                    if ($defaultCardId == $card->id) {
                        $card->isDefaultCard = 1;
                    }
                    $cards[] = $card;
                }
                return $cards;
            }
        }
        return [];
    }
    public static function createVaultedShopper($userId)
    {
        $user = User::select('user_fname', 'user_lname')->where('user_id', $userId)->first();
        $params = array(
            "firstName" => $user->user_fname,
            "lastName" => $user->user_lname
        );
        try {
            $response = self::curlRequest('POST', 'vaulted-shoppers', $params);
            if (!$response || isset($response->errorCode)) {
                return false;
            }
            if (isset($response->vaultedShopperId)) {
                self::saveVaultedShopperInDb($userId, $response->vaultedShopperId);
                return $response->vaultedShopperId;
            } else {
                return false;
            }
        } catch (Exception $e) {

            return false;
        }
    }
    public static function getUserVaultedShopperId($userId)
    {
        $userCard = UserCard::where(['ucard_user_id' => $userId, "ucard_slug" => 'Bluesnap'])->first();
        if (isset($userCard->ucard_customer_id)) {
            return $userCard->ucard_customer_id;
        }
        return false;
    }
    public static function saveVaultedShopperInDb($userId, $vaultedShopperId)
    {
        UserCard::create([
            'ucard_user_id' => $userId,
            'ucard_slug' => "Bluesnap",
            'ucard_customer_id' => $vaultedShopperId
        ]);
        return true;
    }

    public static function saveCard($request, $userId)
    {
        $vaultedShopperId = self::getUserVaultedShopperId($userId);
        $message = __('Invalid payment gateway configurations, Please contact seller.');
        if (!$vaultedShopperId) {
            $vaultedShopperId = self::createVaultedShopper($userId);
            if (!$vaultedShopperId) {
                return ['status' => false, 'message' => $message];
            }
        }
        $params['paymentSources']['creditCardInfo'][]['creditCard'] = array(
            "cardNumber" => $request['number'],
            "securityCode" => $request['cvv'],
            "expirationMonth" => $request['exp_month'],
            "expirationYear" => $request['exp_year']
        );
        $params["firstName"] = $request['name'];
        $params["lastName"] = "";
        $params["softDescriptor"] = "DescTest";
        try {
            $response = self::curlRequest('PUT', 'vaulted-shoppers/' . $vaultedShopperId, $params);
            if (!$response || isset($response->errorCode)) {
                return ['status' => false, 'message' => $message];
            }
            if (isset($response->vaultedShopperId)) {

                return ['status' => true, 'data' => (array) $response];
            } else {
                if (isset($response->message[1]->description)) {

                    $message = $response->message[1]->description;
                } else if (isset($response->message[0]->description)) {

                    $message = $response->message[0]->description;
                }
                return ['status' => false, 'message' => $message];
            }
        } catch (Exception $e) {

            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    public static function updateDefaultCard($userId, $cardData)
    {
        $arrCardData = explode("-", $cardData);
        if (!isset($arrCardData[0]) || !isset($arrCardData[1])) {
            return ['status' => false, 'message' => __('Invalid card details')];
        }
        $vaultedShopperId = self::getUserVaultedShopperId($userId);

        $params = array(
            "chosenPaymentMethod" => array(
                "chosenPaymentMethodType" => "CC",
                "creditCard" => array(
                    "cardLastFourDigits" => $arrCardData[0],
                    "cardType" => $arrCardData[1]
                )
            )
        );
        try {
            $response = self::curlRequest('PUT', 'vaulted-shoppers/' . $vaultedShopperId, $params);
            if (!$response || isset($response->errorCode)) {
                return ['status' => false, 'message' => __('Invalid payment gateway configurations, Please contact seller.')];
            }
            if (isset($response->vaultedShopperId)) {
                return ['status' => true, 'data' => (array) $response];
            } else {
                return ['status' => false, 'message' => __('Invalid payment gateway configurations, Please contact seller.')];
            }
        } catch (Exception $e) {

            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    public static function deleteCard($cardData, $userId)
    {
        $arrCardData = explode("-", $cardData);
        if (!isset($arrCardData[0]) || !isset($arrCardData[1])) {
            return ['status' => false, 'message' => __('Invalid card details')];
        }
        $vaultedShopperId = self::getUserVaultedShopperId($userId);

        $params['paymentSources']['creditCardInfo'][] = array(
            'creditCard' => array(
                "cardLastFourDigits" => $arrCardData[0],
                "cardType" => $arrCardData[1]
            ),
            "status" => "D"
        );
        try {
            $response = self::curlRequest('PUT', 'vaulted-shoppers/' . $vaultedShopperId, $params);
            if (!$response || isset($response->errorCode)) {
                return ['status' => false, 'message' => __('Invalid payment gateway configurations, Please contact seller.')];
            }
            if (isset($response->vaultedShopperId)) {
                return ['status' => true, 'data' => (array) $response];
            } else {
                return ['status' => false, 'message' => __('Invalid payment gateway configurations, Please contact seller.')];
            }
        } catch (Exception $e) {

            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    public static function curlRequest($method, $endUrl, $params = array())
    {
        $url = self::getApiUrl() . '/' . $endUrl;
        $curl = curl_init($url);
        $params = json_encode($params);
        $header = array("Authorization: Basic " . self::generateToken(), "content-type:application/json", "content-length:" . strlen($params));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        if (!empty($params)) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        }
        $response = curl_exec($curl);
        return json_decode($response);
    }
}
