<?php

namespace App\Helpers;

use App\Helpers\YokartHelper;
use App\Models\Package;
use App\Models\Product;
use App\Models\ProductContent;

class ShippingHelper extends YokartHelper
{
    protected $activePackage;
    protected $shipping;
    protected $state;
    protected $country;

    public function __construct($country, $state)
    {
        $this->country = $country;
        $this->state = $state;
        $this->activePackage = Package::getActivePackage('shipping');
        $this->shipping = 'YoKartShipping\\'.$this->activePackage->pkg_slug.'\Http\Controllers\\'.$this->activePackage->pkg_slug.'Controller';
    }
    public function getShipmentByProduct($product)
    {
        $shippingArray[$product->prod_id] = [
                'weigth' => $this->productWeight($product),
                'productid' => $product->prod_id,
                'name' => $product->prod_name,
                'price' => $product->finalprice * $product->prod_min_order_qty,
                'shippingType' => 'shipping',
            ];
        return $this->shipping::getRates($shippingArray, $this->country, $this->state);
    }
    public function getShipments($cartCollection)
    {
        $shippingArray = [];
        foreach ($cartCollection as $key => $cart) {
         
            $product = Product::join('product_contents as pc', 'pc.pc_prod_id', 'prod_id') ->select('pc_weight', 'prod_type', 'pc_weight_unit')
        ->where('pc_prod_id', $cart['product_id'])->first();
            if ($cart['shipType'] == 'pickup' || $product->prod_type == 2) {
                continue;
            }
      

            $shippingArray[$cart['id']]['weigth'] = $this->productWeight($product) * $cart['quantity'];
            $shippingArray[$cart['id']]['productid'] = $cart['product_id'];
            $shippingArray[$cart['id']]['name'] = $cart['name'];
            $shippingArray[$cart['id']]['price'] = $cart['price'] * $cart['quantity'];
        }
           
        return $this->shipping::getRates($shippingArray, $this->country, $this->state);
    }
    public function productWeight($product)
    {
        switch ($product->pc_weight_unit) {
            case ProductContent::PRODUCT_WEIGHT_KG:
                return $product->pc_weight;
                break;
            case ProductContent::PRODUCT_WEIGHT_GM:
                return $product->pc_weight  * 0.001; //calculate weight gm to kg
                break;
        }
    }
}
