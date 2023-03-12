<?php
namespace App\Helpers;

use Stripe;

class StripePay
{
    protected $stripeSecret;

    public function __construct()
    {
        $this->stripeSecret = '';
        \Stripe\Stripe::setApiKey($this->stripeSecret);
    }

    public static function createCustomer($userId)
    {
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
}
