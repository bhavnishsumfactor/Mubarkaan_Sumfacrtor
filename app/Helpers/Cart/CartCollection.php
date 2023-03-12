<?php

namespace App\Helpers\Cart;

use Illuminate\Support\Collection;

class CartCollection extends Collection {

    public function getKeys()
    {
        if(!empty($this->items)) {
            $productids = array_map (function ($ar) { 
                return $ar['product_id']; 
            }, 
            $this->items 
        );
            return array_values($productids);
        } else {
             return [];
        }
    }
    public function byKey($key)
    {
        if (isset($this->items[$key])) {
            return $this->items[$key]->items;
        } else {
            return [];
       }
       
    }
   
}
