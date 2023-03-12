<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\PackageConfiguration;
use Illuminate\Database\Seeder;

class PackageTableSeeder extends Seeder
{
    public $packages = [
        [
          'pkg_id' => 1,
          'pkg_type' => 1,
          'pkg_slug' => "twilio",
          'pkg_name' => "Twilio SMS Gateway",
          'pkg_card_type' => 0,
          'pkg_publish' => 0,
          'pkg_is_deleted' => 0
        ],
        [
            'pkg_id' => 2,
            'pkg_type' => 2,
            'pkg_slug' => "SystemTax",
            'pkg_name' => "System Tax",
            'pkg_card_type' => 0,
            'pkg_publish' => 1,
            'pkg_is_deleted' => 0
        ], [
            'pkg_id' => 3,
          'pkg_type' => 3,
          'pkg_slug' => "SystemShipping",
          'pkg_name' => "System Shipping",
          'pkg_card_type' => 0,
          'pkg_publish' => 1,
          'pkg_is_deleted' => 0
        ],
        [
            'pkg_id' => 4,
          'pkg_type' => 4,
          'pkg_slug' => "Stripe",
          'pkg_name' => "Stripe (Credit Card)",
          'pkg_card_type' => 1,
          'pkg_publish' => 1,
          'pkg_is_deleted' => 0
        ],
        [
           'pkg_id' => 5,
          'pkg_type' => 4,
          'pkg_slug' => "Paypal",
          'pkg_name' => "Paypal Payment (Credit Card)",
          'pkg_card_type' => 1,
          'pkg_publish' => 0,
          'pkg_is_deleted' => 0
         ],
         [
           'pkg_id' => 6,
           'pkg_type' => 4,
           'pkg_slug' => "AuthorizeDotNet",
           'pkg_name' => "Authorize .Net",
           'pkg_card_type' => 0,
           'pkg_publish' => 0,
           'pkg_is_deleted' => 1
         ],
         [
          'pkg_id' => 7,
           'pkg_type' => 4,
           'pkg_slug' => "PaypalExpress",
           'pkg_name' => "Paypal Express Payment",
           'pkg_card_type' => 0,
           'pkg_publish' => 1,
           'pkg_is_deleted' => 0
         ],
         [
         'pkg_id' => 8,
         'pkg_type' => 5,
         'pkg_slug' => "AfterShip",
         'pkg_name' => "After Shipping Api",
         'pkg_card_type' => 0,
         'pkg_publish' => 0,
         'pkg_is_deleted' => 0
         ],
         [
          'pkg_id' => 9,
         'pkg_type' => 4,
         'pkg_slug' => "TwoCheckout",
         'pkg_name' => "Two Checkout",
         'pkg_card_type' => 0,
         'pkg_publish' => 1,
         'pkg_is_deleted' => 1
         ],[
          'pkg_id' => 10,
          'pkg_type' => 4,
          'pkg_slug' => "Bluesnap",
          'pkg_name' => "Blue Snap",
          'pkg_card_type' => 0,
          'pkg_publish' => 1,
          'pkg_is_deleted' => 1
         ],
         [
          'pkg_id' => 11,
          'pkg_type' => 4,
          'pkg_slug' => "CashFree",
          'pkg_name' => "CashFree",
          'pkg_card_type' => 0,
          'pkg_publish' => 0,
          'pkg_is_deleted' => 0
         ]
      ];
    public $packageConf = [
        [
          'pconf_pkg_id' => 1,
          'pconf_key' => "sid",
          'pconf_value' => "ACd411df810d9007537b10027ee1df1972",
        ],
        [
          'pconf_pkg_id' => 1,
          'pconf_key' => "auth_token",
          'pconf_value' => "2bca29d821b2b7d9742c58cf22580703",
        ], [
            'pconf_pkg_id' => 1,
          'pconf_key' => "number",
          'pconf_value' => "+15005550006",
        ],
        [
            'pconf_pkg_id' => 4,
            'pconf_key' => "STRIPE_KEY",
            'pconf_value' => "pk_test_fF9QOYb9bHwdjwoz9eorNYg600ZLhj3Rv8",
        ],
        [
            'pconf_pkg_id' => 4,
            'pconf_key' => "STRIPE_SECRET",
            'pconf_value' => "sk_test_5pqIx9GfsoZ6Xj6I81e5eEEd00kPR17pbL",
         ],
         [
            'pconf_pkg_id' => 5,
          'pconf_key' => "PAYPAL_USERNAME",
          'pconf_value' => "sb-fxuc46631644_api1.business.example.com",
         ],
         [
           'pconf_pkg_id' => 5,
          'pconf_key' => "PAYPAL_PASSWORD",
          'pconf_value' => "EB9F5ZDPFA333U62",
         ],
         [
            'pconf_pkg_id' => 5,
           'pconf_key' => "PAYPAL_SIGNATURE",
           'pconf_value' => "A8PiOurJ1pfGWJsUernojno.idsRAi4Y531htQ9XTMFgdYX7zeW2ZXbh",
          ],
          [
            'pconf_pkg_id' => 5,
           'pconf_key' => "PAYPAL_CLIENTID",
           'pconf_value' => "EB9F5ZDPFA333U62",
          ],
          [
            'pconf_pkg_id' => 5,
           'pconf_key' => "PAYPAL_SECRET",
           'pconf_value' => "A7YXoqkrUeg8enKgqwFp0Qp4O43EAe7ck6iDaXUAV.xZSXHNN1QIJXpI",
          ],
          [
            'pconf_pkg_id' => 6,
           'pconf_key' => "AUTHORIZENET_LOGINID",
           'pconf_value' => "566whj22UFY",
          ],
          [
            'pconf_pkg_id' => 6,
           'pconf_key' => "AUTHORIZENET_TRANSACTION_KEY",
           'pconf_value' => "58hmuZsL7SR56h9L",
          ],
          [
            'pconf_pkg_id' => 7,
           'pconf_key' => "PAYPAL_EXPRESS_USERNAME",
           'pconf_value' => "sb-fxuc46631644_api1.business.example.com",
          ],
          [
            'pconf_pkg_id' => 7,
           'pconf_key' => "PAYPAL_EXPRESS_PASSWORD",
           'pconf_value' => "EB9F5ZDPFA333U62",
          ],
          [
            'pconf_pkg_id' => 7,
           'pconf_key' => "PAYPAL_EXPRESS_SIGNATURE",
           'pconf_value' => "A8PiOurJ1pfGWJsUernojno.idsRAi4Y531htQ9XTMFgdYX7zeW2ZXbh",
          ],
         [
            'pconf_pkg_id' => 8,
            'pconf_key' => "AFTERSHIP_KEY",
            'pconf_value' => "",
          ],
          [

            'pconf_pkg_id' => 9,
            'pconf_key' => "MERCHANT_CODE",
            'pconf_value' => "250734749188",
          ],
          [
            'pconf_pkg_id' => 9,
            'pconf_key' => "SECRET_CODE",
            'pconf_value' => "qzN?(f42g=phx)dQ%oRC",
          ],
          [
            'pconf_pkg_id' => 9,
            'pconf_key' => "PUBLISHABLE_KEY",
            'pconf_value' => "62A316B5-1FCB-4757-826F-098550889701",
          ],
          [
            'pconf_pkg_id' => 9,
            'pconf_key' => "PRIVATE_KEY",
            'pconf_value' => "36E88583-5310-41AC-B3D4-5C07306E1BF3",
          ],[
           
              'pconf_pkg_id' => 10,
              'pconf_key' => "USERNAME",
              'pconf_value' => "API_16135541001942047198202",
            ],
            [
              'pconf_pkg_id' => 10,
              'pconf_key' => "PASSWORD",
              'pconf_value' => "Ably@123",
            ],
            [
              'pconf_pkg_id' => 10,
              'pconf_key' => "STORE_ID",
              'pconf_value' => "39340",
            ],
            [           
              'pconf_pkg_id' => 11,
              'pconf_key' => "CASHFREE_APPID",
              'pconf_value' => "2477275d3a8754fb656aaf13227742",
            ],
            [
              'pconf_pkg_id' => 11,
              'pconf_key' => "CASHFREE_SECRET_KEY",
              'pconf_value' => "48a7070b47423c6341c06278b950f16fff085e0e",
            ]
      ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Package::truncate();
        PackageConfiguration::truncate();
        Package::insert($this->packages);
        PackageConfiguration::insert($this->packageConf);
        /*ini_set('memory_limit', '1024M');
        factory(Option::class, 1)->create();*/
    }
}
