<?php
    namespace App\Helpers\Cart;


use Illuminate\Support\Collection;

class CartConditionCollection extends Collection {

    public function getItems($name)
    {
        if ($this->has($name)) {
            return $this->get($name);
        }
        return null;
    }
}
