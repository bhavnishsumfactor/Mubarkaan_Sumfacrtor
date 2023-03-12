<?php

namespace App\Models;

use App\Models\YokartModel;

class OrderProductAdditionInfo extends YokartModel
{
    public $timestamps = false;
    protected $table = 'order_product_addition_info';
    protected $fillable = ['opainfo_op_id', 'opainfo_max_download_limit', 'opainfo_downloads', 'opainfo_upload_additional_files', 'opainfo_discount_amount', 'opainfo_gift_amount', 'opainfo_cat_tax_code', 'opainfo_tgtype_rate', 'opainfo_reward_rate', 'opainfo_shipping_amount'];

    public static function getProductShippingCharges($orderProductId)
    {
        return self::select('opainfo_shipping_amount')->where('opainfo_op_id', $orderProductId)->first();
    }
}
