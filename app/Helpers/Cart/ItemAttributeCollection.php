<?php

 namespace App\Helpers\Cart;

use Illuminate\Support\Collection;

class ItemAttributeCollection extends Collection
{
    public function __get($name)
    {
        if ($this->has($name)) {
            return $this->get($name);
        }
        return null;
    }
    public function getItems()
    {
        return $this->items;
    }
}
