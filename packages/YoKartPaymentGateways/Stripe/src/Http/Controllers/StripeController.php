<?php

namespace YoKartPaymentGateways\Stripe\Http\Controllers;

use App\Http\Controllers\PackageYokartController;
use YoKartTax\SystemTaxManagement\Models\SystemTax;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException; // this is for the validation
use App\Models\Order;
use App\Models\UserCard;
use App\Models\User;
use Stripe;
use DB;
use App\Models\Package;

class StripeController extends PackageYokartController
{
    public static function makePayment($request, $orderId, $amount = 0)
    {
        $records = Package::getRecordBySlug('Stripe');
        $token = static::generateToken($request, $records['STRIPE_KEY']);

        if (!empty($token->message)) {
            return ['status' => false, 'message' => $token->message];
        }
        try {
            $order = Order::select('order_net_amount', 'order_net_amount', 'order_user_id', 'order_currency_code')->where('order_id', $orderId)->first();
            if (isset($request['save-card']) && !empty($request['save-card']) && $request['save-card'] == "1") {
                static::saveCard($request->only('name', 'number', 'exp_month', 'exp_year', 'cvv'), $order->order_user_id);
            }

            Stripe\Stripe::setApiKey($records['STRIPE_SECRET']);

            if ($amount == 0) {
                $amount  = $order->order_net_amount;
            }
            $responce = Stripe\Charge::create([
                "amount" => round($amount, 2) * 100,
                "currency" => $order->order_currency_code,
                "source" => $token,
                "description" => 'yokart Payment'
            ]);
            $responce['amount'] = $responce['amount'] / 100;
            return ['status' => true, 'data' => $responce];
        } catch (\Stripe\Exception\CardException $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        } catch (\Stripe\Exception\RateLimitException $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        } catch (\Stripe\Exception\AuthenticationException $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        } catch (Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

        
    public static function makePaymentByCardToken($token, $orderId,$isSave = 0){
        $records = Package::getRecordBySlug('Stripe');

        
        try {

            if($isSave == 1 ){
                $order = Order::select('order_net_amount', 'order_net_amount', 'order_user_id', 'order_currency_code')->where('order_id', $orderId)->first();
                $customerData = UserCard::getCustomerId($order->order_user_id, "Stripe");
                if (!empty($customerData) && $customerData->ucard_customer_id) {
                    $cardSave = static::saveCardInformation($token, $customerData->ucard_customer_id);
                } else {
                    $customer = static::createCustomer($order->order_user_id);
                    $cardSave = static::saveCardInformation($token, $customer->id);
                }

              
                $cardId = $cardSave['message']['id'];
                return static::makePaymentByCardId($cardId, $orderId);
            }
            $order = Order::select('order_net_amount', 'order_net_amount', 'order_user_id', 'order_currency_code')->where('order_id', $orderId)->first();
            Stripe\Stripe::setApiKey($records['STRIPE_SECRET']);
            $amount  = $order->order_net_amount;
            $responce = Stripe\Charge::create([
                "amount" => round($amount, 2) * 100,
                "currency" => $order->order_currency_code,
                "source" => $token,
                "description" => 'yokart Payment'
            ]);
            $responce['amount'] = $responce['amount'] / 100;
            return ['status' => true, 'data' => $responce];
        } catch (\Stripe\Exception\CardException $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        } catch (\Stripe\Exception\RateLimitException $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        } catch (\Stripe\Exception\AuthenticationException $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        } catch (Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }


    public static function makePaymentByCardId($cartId, $orderId)
    {
        $records = Package::getRecordBySlug('Stripe');
        try {
            $order = Order::select('order_net_amount', 'order_currency_code', 'order_user_id')->where('order_id', $orderId)->first();
            $customerId = UserCard::where('ucard_slug', "Stripe")->where('ucard_user_id', $order->order_user_id)->first()->ucard_customer_id;
            Stripe\Stripe::setApiKey($records['STRIPE_SECRET']);
            try {
                $responce = \Stripe\Charge::create([
                    'amount' => round($order->order_net_amount, 2) * 100,
                    'currency' => $order->order_currency_code,
                    'customer' => $customerId,
                    'source' => $cartId,
                ]);
            } catch (\Stripe\Exception\CardException $e) {
                return $e->getError();
            } catch (\Stripe\Exception\RateLimitException $e) {
                return $e->getError();
            } catch (\Stripe\Exception\InvalidRequestException $e) {
                return $e->getError();
            } catch (\Stripe\Exception\AuthenticationException $e) {
                return $e->getError();
            } catch (\Stripe\Exception\ApiConnectionException $e) {
                return $e->getError();
            } catch (\Stripe\Exception\ApiErrorException $e) {
                return $e->getError();
            } catch (Exception $e) {
                return $e->getError();
            }
            $responce['amount'] = $responce['amount'] / 100;
            return ['status' => true, 'data' => $responce];
        } catch (Exception $e) {
            return ['status' => false, 'message' => $e->message];
        }
    }
    public static function generateToken($request, $stripeKey)
    {
        Stripe\Stripe::setApiKey($stripeKey);
        try {
            $token = Stripe\Token::create([
                'card' => [
                    'name' => isset($request['name'])?$request['name']:'',
                    'number' => $request['number'],
                    'exp_month' => $request['exp_month'],
                    'exp_year' => $request['exp_year'],
                    'cvc' => $request['cvv'],
                ],
            ]);
            return $token['id'];
        } catch (\Stripe\Exception\CardException $e) {
            return $e->getError();
        } catch (\Stripe\Exception\RateLimitException $e) {
            return $e->getError();
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            return $e->getError();
        } catch (\Stripe\Exception\AuthenticationException $e) {
            return $e->getError();
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            return $e->getError();
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return $e->getError();
        } catch (Exception $e) {
            return $e->getError();
        }
    }
    public static function paymentRefund($transactionId, $amount)
    {
        $records = Package::getRecordBySlug('Stripe');
        try {
            Stripe\Stripe::setApiKey($records['STRIPE_SECRET']);
            $response = \Stripe\Refund::create([
                'amount' => round($amount, 2) * 100,
                'charge' => $transactionId,
            ]);
            $response['amount'] = $response['amount'] / 100;
            return ['status' => true, 'data' => $response];
        } catch (Exception $e) {
            return ['status' => false, 'message' => $e->message];
        }
    }

    public static function saveCard($request, $userId)
    {
        $records = Package::getRecordBySlug('Stripe');
        $token = static::generateToken($request, $records['STRIPE_KEY']); 
        $customerData = UserCard::getCustomerId($userId, "Stripe");
        if (!empty($customerData) && $customerData->ucard_customer_id) {
            $cardSave = static::saveCardInformation($token, $customerData->ucard_customer_id);
        } else {
            $customer = static::createCustomer($userId);
            $cardSave = static::saveCardInformation($request, $customer->id);
        }
        return $cardSave;
    }

    public static function createCustomer($userId)
    {
        $user = User::select('user_id', DB::raw('CONCAT(user_fname, " ", user_lname) as user_name'), 'user_email')->where('user_id', $userId)->get()->first();
        $records = Package::getRecordBySlug('Stripe');
        $stripe = \Stripe\Stripe::setApiKey($records['STRIPE_SECRET']);
        $customer = \Stripe\Customer::create(
            [
                'name' => $user->user_name,
                'email' => $user->user_email
            ]
        );
        UserCard::create([
            'ucard_user_id' => $userId,
            'ucard_slug' => "Stripe",
            'ucard_customer_id' => $customer->id
        ]);
        return $customer;
    }

    public static function saveCardInformation($token, $customerId)
    {
        $records = Package::getRecordBySlug('Stripe');
        $stripe = \Stripe\Stripe::setApiKey($records['STRIPE_SECRET']);
        try {
            // $cardSave  = \Stripe\Customer::createSource(
            //     $customerId,
            //     [
            //         'source' => [
            //             'object' => 'card',
            //             'name' => $request['name'],
            //             'number' => $request['number'],
            //             'exp_month' => $request['exp_month'],
            //             'exp_year' => $request['exp_year'],
            //             'cvc' => $request['cvv'],
            //         ]
            //     ]
            // );
            $cardSave  = \Stripe\Customer::createSource(
                $customerId,
                ['source' => $token]
            );
            
            if (!empty($request['is_default']) && $request['is_default'] == 1) {
                $customer = \Stripe\Customer::retrieve($customerId);
                $customer->default_source = $cardSave['id'];
                $customer->save();
            }
            return ['status' => true, 'message' => $cardSave];
        } catch (\Stripe\Exception\CardException $e) {
            $error = $e->getError();
        } catch (\Stripe\Exception\RateLimitException $e) {
            $error = $e->getError();
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            $error = $e->getError();
        } catch (\Stripe\Exception\AuthenticationException $e) {
            $error = $e->getError();
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            $error = $e->getError();
        } catch (\Stripe\Exception\ApiErrorException $e) {
            $error = $e->getError();
        } catch (Exception $e) {
            $error = $e->getError();
        }
        return ['status' => false, 'message' => $error->message];
    }

    public static function getAllCards($userId)
    {
        $customerData = UserCard::getCustomerId($userId, "Stripe");
        $records = Package::getRecordBySlug('Stripe');
        $stripe = \Stripe\Stripe::setApiKey($records['STRIPE_SECRET']);
        if (isset($customerData->ucard_customer_id) && !empty($customerData->ucard_customer_id)) {
            $cards = \Stripe\Customer::allSources(
                $customerData->ucard_customer_id,
                ['object' => 'card']
            );
            return $cards->data;
        }
        return [];
    }

    public static function defaultCard($userId)
    {
        $customerData = UserCard::getCustomerId($userId, "Stripe");
        $customer = \Stripe\Customer::retrieve($customerData->ucard_customer_id);
        return !empty($customer->default_source) ? $customer->default_source : '';
    }

    public static function updateDefaultCard($userId, $cardId)
    {
        $customerData = UserCard::getCustomerId($userId, "Stripe");
        $records = Package::getRecordBySlug('Stripe');
        $stripe = \Stripe\Stripe::setApiKey($records['STRIPE_SECRET']);
        $customer = \Stripe\Customer::retrieve($customerData->ucard_customer_id);
        $customer->default_source = $cardId;
        $customer->save();
        return true;
    }

    public static function deleteCard($cardId, $userId)
    {
        $customerData = UserCard::getCustomerId($userId, "Stripe");
        $records = Package::getRecordBySlug('Stripe');
        $stripe = \Stripe\Stripe::setApiKey($records['STRIPE_SECRET']);
        if (isset($customerData->ucard_customer_id) && !empty($customerData->ucard_customer_id)) {
            try {
                $card =  \Stripe\Customer::deleteSource(
                    $customerData->ucard_customer_id,
                    $cardId,
                    []
                );
                return $card->deleted;
            } catch (Exception $e) {
                return false;
            }
        }
        return false;
    }
}
