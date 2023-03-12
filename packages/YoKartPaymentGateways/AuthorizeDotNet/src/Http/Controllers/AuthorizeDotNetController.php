<?php

namespace YoKartPaymentGateways\AuthorizeDotNet\Http\Controllers;

use App\Http\Controllers\PackageYokartController;
use YoKartTax\SystemTaxManagement\Models\SystemTax;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException; // this is for the validation
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
use App\Models\Order;
use App\Models\Package;
use App\Models\UserCard;
use App\Models\User;

class AuthorizeDotNetController extends PackageYokartController
{
    public static function makePayment($request, $orderId, $amount = 0)
    {
        $order = Order::select('order_net_amount', 'order_currency_code')->where('order_id', $orderId)->first();
        if ($amount == 0) {
            $amount = $order->order_net_amount;
        }
        return self::payment($request, $orderId, $amount);
    }

    public static function payment($request, $orderId, $amount)
    {
        $input = $request;
        try {
            $record = Package::getRecordBySlug('AuthorizeDotNet');
            $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
            $merchantAuthentication->setName($record['AUTHORIZENET_LOGINID']);
            $merchantAuthentication->setTransactionKey($record['AUTHORIZENET_TRANSACTION_KEY']);
            // Set the transaction's refId
            $refId = 'ref' . time();
            $cardNumber = preg_replace('/\s+/', '', $input['number']);
            // Create the payment data for a credit card
            $creditCard = new AnetAPI\CreditCardType();
            $creditCard->setCardNumber($cardNumber);
            $creditCard->setExpirationDate($input['exp_year'] . "-" .$input['exp_month']);
            $creditCard->setCardCode($input['cvv']);
            // Add the payment data to a paymentType object
            $paymentOne = new AnetAPI\PaymentType();
            $paymentOne->setCreditCard($creditCard);
            // Create a TransactionRequestType object and add the previous objects to it
            $transactionRequestType = new AnetAPI\TransactionRequestType();
            $transactionRequestType->setTransactionType("authCaptureTransaction");
            $transactionRequestType->setAmount($amount);
            $transactionRequestType->setPayment($paymentOne);
            $requests = new AnetAPI\CreateTransactionRequest();
            $requests->setMerchantAuthentication($merchantAuthentication);
            $requests->setRefId($refId);
            $requests->setTransactionRequest($transactionRequestType);
            $controller = new AnetController\CreateTransactionController($requests);
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
            if ($response != null) {
                if ($response->getMessages()->getResultCode() == "Ok") {
                    $tresponse = $response->getTransactionResponse();
                    if ($tresponse != null && $tresponse->getMessages() != null) {
                        $responce['amount'] = $amount;
                        $responce['id'] = $tresponse->getTransId();
                        $responce['description'] = $tresponse->getMessages()[0]->getDescription();
                        $responce['code'] = $tresponse->getResponseCode();
                        $responce['payment_method_details']['card'] = [
                            'last4' => $tresponse->getAccountNumber(),
                            'network' => $tresponse->getAccountType()
                        ];
                        $order = Order::select('order_user_id', 'order_net_amount', 'order_currency_code')->where('order_id', $orderId)->first();
                        if (isset($input['save-card']) && !empty($input['save-card']) && $input['save-card'] == 1) {
                            static::saveCard($request->only('name', 'number', 'exp_month', 'exp_year', 'cvv'), $order->order_user_id);
                        }
                        return ['status' => true, 'data' => $responce];
                    } else {
                        if ($tresponse->getErrors() != null) {
                            return ['status' => false, 'message' => $tresponse->getErrors()[0]->getErrorText()];
                        }
                    }
                } else {
                    $tresponse = $response->getTransactionResponse();
                    if ($tresponse != null && $tresponse->getErrors() != null) {
                        return ['status'=>false, 'message' => $tresponse->getErrors()[0]->getErrorText()];
                    } else {
                        return ['status'=>false, 'message' => $response->getMessages()->getMessage()[0]->getText()];
                    }
                }
            } else {
                return ['status'=>false, 'message' => "No response returned"];
            }
        } catch (Exception $e) {
            //return ['status'=>false, 'message' => $e->message];
        }
    }

    public function refundTransaction()
    {
        /* Create a merchantAuthenticationType object with authentication details
            retrieved from the constants file */
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(env('MERCHANT_LOGIN_ID'));
        $merchantAuthentication->setTransactionKey(env('MERCHANT_TRANSACTION_KEY'));
        // Set the transaction's refId
        $refId = 'ref' . time();
        // Create the payment data for a credit card
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber("0015");
        $creditCard->setExpirationDate("XXXX");
        $paymentOne = new AnetAPI\PaymentType();
        $paymentOne->setCreditCard($creditCard);
        //create a transaction
        $transactionRequest = new AnetAPI\TransactionRequestType();
        $transactionRequest->setTransactionType("refundTransaction");
        $transactionRequest->setAmount($amount);
        $transactionRequest->setPayment($paymentOne);
        $transactionRequest->setRefTransId($refTransId);
 
        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setTransactionRequest($transactionRequest);
        $controller = new AnetController\CreateTransactionController($request);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

        if ($response != null) {
            if ($response->getMessages()->getResultCode() == "Ok") {
                $tresponse = $response->getTransactionResponse();
                if ($tresponse != null && $tresponse->getMessages() != null) {
                    echo " Transaction Response code : " . $tresponse->getResponseCode() . "\n";
                    echo "Refund SUCCESS: " . $tresponse->getTransId() . "\n";
                    echo " Code : " . $tresponse->getMessages()[0]->getCode() . "\n";
                    echo " Description : " . $tresponse->getMessages()[0]->getDescription() . "\n";
                } else {
                    echo "Transaction Failed \n";
                    if ($tresponse->getErrors() != null) {
                        echo " Error code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
                        echo " Error message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";
                    }
                }
            } else {
                echo "Transaction Failed \n";
                $tresponse = $response->getTransactionResponse();
                if ($tresponse != null && $tresponse->getErrors() != null) {
                    echo " Error code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
                    echo " Error message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";
                } else {
                    echo " Error code  : " . $response->getMessages()->getMessage()[0]->getCode() . "\n";
                    echo " Error message : " . $response->getMessages()->getMessage()[0]->getText() . "\n";
                }
            }
        } else {
            echo  "No response returned \n";
        }
        return $response;
    }

    public static function saveCard($request, $userId)
    {
        $customerData = UserCard::getCustomerId($userId, "AuthorizeDotNet");
        if (!empty($customerData) && $customerData->ucard_customer_id) {
            $cardSave = static::addCards($request, $customerData->ucard_customer_id);
        } else {
            $cardSave = static::createCustomerWithCard($request, $userId);
        }
        return $cardSave;
    }

    public static function getAllCards($userId)
    {
        $customerData = UserCard::getCustomerId($userId, "AuthorizeDotNet");
        $record = Package::getRecordBySlug('AuthorizeDotNet');
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName($record['AUTHORIZENET_LOGINID']);
        $merchantAuthentication->setTransactionKey($record['AUTHORIZENET_TRANSACTION_KEY']);

        if (isset($customerData->ucard_customer_id) && !empty($customerData->ucard_customer_id)) {
            $request = new AnetAPI\GetCustomerProfileRequest();
            $request->setMerchantAuthentication($merchantAuthentication);
            $request->setCustomerProfileId($customerData->ucard_customer_id);
            $controller = new AnetController\GetCustomerProfileController($request);
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
            if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
                $profileSelected = $response->getProfile();
                $paymentProfilesSelected = $profileSelected->getPaymentProfiles();
                return $paymentProfilesSelected;
            }
        } else {
            return [];
        }
    }

    public static function deleteCard($cardId, $userId)
    {
        $customerData = UserCard::getCustomerId($userId, "AuthorizeDotNet");
        $record = Package::getRecordBySlug('AuthorizeDotNet');
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName($record['AUTHORIZENET_LOGINID']);
        $merchantAuthentication->setTransactionKey($record['AUTHORIZENET_TRANSACTION_KEY']);
        if (isset($customerData->ucard_customer_id) && !empty($customerData->ucard_customer_id)) {
            $request = new AnetAPI\DeleteCustomerPaymentProfileRequest();
            $request->setMerchantAuthentication($merchantAuthentication);
            $request->setCustomerProfileId($customerData->ucard_customer_id);
            $request->setCustomerPaymentProfileId($cardId);
            $controller = new AnetController\DeleteCustomerPaymentProfileController($request);
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
            if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
                return true;
            }
        }
        return false;
    }

    public static function createCustomerWithCard($request, $userId)
    {
        $record = Package::getRecordBySlug('AuthorizeDotNet');
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName($record['AUTHORIZENET_LOGINID']);
        $merchantAuthentication->setTransactionKey($record['AUTHORIZENET_TRANSACTION_KEY']);

        $refId = 'ref' . time();
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($request['number']);
        $creditCard->setExpirationDate($request['exp_year'] . "-" . $request['exp_month']);
        $creditCard->setCardCode($request['cvv']);
        $paymentCreditCard = new AnetAPI\PaymentType();
        $paymentCreditCard->setCreditCard($creditCard);

        $billTo = new AnetAPI\CustomerAddressType();
        $billTo->setFirstName($request['name']);

        $paymentProfile = new AnetAPI\CustomerPaymentProfileType();
        $paymentProfile->setCustomerType('individual');
        $paymentProfile->setBillTo($billTo);
        $paymentProfile->setPayment($paymentCreditCard);
        if (!empty($request['is_default']) && $request['is_default'] == 1) {
            $paymentProfile->setDefaultPaymentProfile(true);
        }
        $paymentProfiles[] = $paymentProfile;
        $customerProfile = new AnetAPI\CustomerProfileType();
        $customerProfile->setMerchantCustomerId("M_" . time());
        $customerProfile->setpaymentProfiles($paymentProfiles);

        // Assemble the complete transaction request
        $request = new AnetAPI\CreateCustomerProfileRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setProfile($customerProfile);
        $controller = new AnetController\CreateCustomerProfileController($request);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
            UserCard::create([
                'ucard_user_id'=>$userId,
                'ucard_slug'=>"AuthorizeDotNet",
                'ucard_customer_id'=>$response->getCustomerProfileId()
            ]);
            return ['status' => true];
        } else {
            $errorMessages = $response->getMessages()->getMessage();
            return ['status' => false, 'message' => $errorMessages[0]->getText()]; //
        }
    }

    public static function addCards($request, $profileId)
    {
        $record = Package::getRecordBySlug('AuthorizeDotNet');
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName($record['AUTHORIZENET_LOGINID']);
        $merchantAuthentication->setTransactionKey($record['AUTHORIZENET_TRANSACTION_KEY']);

        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($request['number']);
        $creditCard->setExpirationDate($request['exp_year'] . "-" . $request['exp_month']);
        $creditCard->setCardCode($request['cvv']);
        $paymentCreditCard = new AnetAPI\PaymentType();
        $paymentCreditCard->setCreditCard($creditCard);

        $billTo = new AnetAPI\CustomerAddressType();
        $billTo->setFirstName($request['name']);

        $paymentprofile = new AnetAPI\CustomerPaymentProfileType();
        $paymentprofile->setPayment($paymentCreditCard);
        $paymentprofile->setCustomerType('individual');
        $paymentprofile->setBillTo($billTo);
        if (!empty($request['is_default']) && $request['is_default'] == 1) {
            $paymentprofile->setDefaultPaymentProfile(true);
        }

        $paymentprofilerequest = new AnetAPI\CreateCustomerPaymentProfileRequest();
        $paymentprofilerequest->setMerchantAuthentication($merchantAuthentication);
        $paymentprofilerequest->setCustomerProfileId($profileId);
        $paymentprofilerequest->setPaymentProfile($paymentprofile);
        //$paymentprofilerequest->setValidationMode("Test");
        $controller = new AnetController\CreateCustomerPaymentProfileController($paymentprofilerequest);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
            return ['status' => true];
        } else {
            $errorMessages = $response->getMessages()->getMessage();
            return ['status' => false, 'message' => $errorMessages[0]->getText()];
        }
    }

    public static function updateDefaultCard($userId, $cardId)
    {
        $customerData = UserCard::getCustomerId($userId, "AuthorizeDotNet");
        $record = Package::getRecordBySlug('AuthorizeDotNet');
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName($record['AUTHORIZENET_LOGINID']);
        $merchantAuthentication->setTransactionKey($record['AUTHORIZENET_TRANSACTION_KEY']);
        $refId = 'ref' . time();

        $request = new AnetAPI\GetCustomerPaymentProfileRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setCustomerProfileId($customerData->ucard_customer_id);
        $request->setCustomerPaymentProfileId($cardId);
      
        $controller = new AnetController\GetCustomerPaymentProfileController($request);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
            $creditCard = new AnetAPI\CreditCardType();
            $creditCard->setCardNumber($response->getPaymentProfile()->getPayment()->getCreditCard()->getCardNumber());
            $creditCard->setExpirationDate($response->getPaymentProfile()->getPayment()->getCreditCard()->getExpirationDate());
            $paymentCreditCard = new AnetAPI\PaymentType();
            $paymentCreditCard->setCreditCard($creditCard);
            $paymentprofile = new AnetAPI\CustomerPaymentProfileExType();
            $paymentprofile->setCustomerPaymentProfileId($response->getPaymentProfile()->getCustomerPaymentProfileId());
            $paymentprofile->setPayment($paymentCreditCard);
            $paymentprofile->setDefaultPaymentProfile(true);

            $request = new AnetAPI\UpdateCustomerPaymentProfileRequest();
            $request->setMerchantAuthentication($merchantAuthentication);
            $request->setCustomerProfileId($customerData->ucard_customer_id);
            $request->setPaymentProfile($paymentprofile);

            $controller = new AnetController\UpdateCustomerPaymentProfileController($request);
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
            if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
