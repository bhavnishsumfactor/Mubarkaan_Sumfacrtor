<?php

namespace App\Helpers\Cart;

use App\Helpers\Cart\Exceptions\InvalidConditionException;
use App\Helpers\Cart\Exceptions\InvalidItemException;
use Illuminate\Database\DatabaseManager;
use App\Helpers\YokartHelper;
use App\Models\UserAddress;
use App\Helpers\TaxHelper;
use App\Helpers\ShippingHelper;
use App\Models\Product;
use App\Helpers\Cart\Validators\CartItemValidator;
use App\Helpers\Cart\CartCondition;
use Session;
use Auth;
use App\Models\UserCart;

/**
 * Class Cart
 * @package Yokart\Cart
 */
class Cart
{

    /**
     * the item storage
     *
     * @var
     */
    protected $session;

    /**
     * the event dispatcher
     *
     * @var
     */
    protected $events;

    /**
     * the cart session key
     *
     * @var
     */
    protected $instanceName;

    /**
     * the session key use for the cart
     *
     * @var
     */
    protected $sessionKey;

    /**
     * the session key use to persist cart items
     *
     * @var
     */
    protected $sessionKeyCartItems;

    /**
     * the session key use to persist cart conditions
     *
     * @var
     */
    protected $sessionKeyCartConditions;

    /**
     * Configuration to pass to ItemCollection
     *
     * @var
     */
    protected $config;

    /**
     * our object constructor
     *
     * @param $session
     * @param $events
     * @param $instanceName
     * @param $session_key
     * @param $config
     */
    public function __construct($session, $events, $instanceName, $session_key, $config)
    {
        $this->cartSession = (Session::has('cartSession')) ? Session::get('cartSession') : request()->header('sess-token');
        // $this->cartSession = Session::get('cartSession');
        $this->events = $events;
        $this->session = $session;
        $this->instanceName = $instanceName;
        $this->sessionKey = $session_key;
        $this->sessionKeyCartItems = $this->sessionKey . '_cart_items';
        $this->sessionKeyCartConditions = $this->sessionKey . '_cart_conditions';
        $this->config = $config;
        $this->fireEvent('created');
    }

    /**
     * sets the session key
     *
     * @param string $sessionKey the session key or identifier
     * @return $this|bool
     * @throws \Exception
     */
    public function session($sessionKey)
    {
        if (!$sessionKey) {
            throw new \Exception("Session key is required.");
        }

        $this->sessionKey = $sessionKey;
        $this->sessionKeyCartItems = $this->sessionKey . '_cart_items';
        $this->sessionKeyCartConditions = $this->sessionKey . '_cart_conditions';

        return $this;
    }

    /**
     * get instance name of the cart
     *
     * @return string
     */
    public function getInstanceName()
    {
        return $this->instanceName;
    }

    /**
     * get an item on a cart by item ID
     *
     * @param $itemId
     * @return mixed
     */
    public function get($itemId)
    {
        return $this->getContent()->get($itemId);
    }

    /**
     * check if an item exists by item ID
     *
     * @param $itemId
     * @return bool
     */
    public function has($itemId)
    {
        return $this->getContent()->has($itemId);
    }

    /**
     * add item to the cart, it can be an array or multi dimensional array
     *
     * @param string|array $id
     * @param string $name
     * @param float $price
     * @param int $quantity
     * @param array $attributes
     * @param CartCondition|array $conditions
     * @return $this
     * @throws InvalidItemException
     */
    public function add($id, $name, $price, $quantity, $productcode, $shipType = 'shipping', $attributes = array(), $conditions = array(), $tax = array())
    {
        // if the first argument is an array,
        // we will need to call add again
        if (is_array($id)) {
            // the first argument is an array, now we will need to check if it is a multi dimensional
            // array, if so, we will iterate through each item and call add again
            if (YokartHelper::isMultiArray($id)) {
                foreach ($id as $item) {
                    $this->add(
                        $item['id'],
                        $item['code'],
                        $item['name'],
                        $item['price'],
                        $item['quantity'],
                        YokartHelper::issetAndHasValueOrAssignDefault($item['attributes'], array()),
                        YokartHelper::issetAndHasValueOrAssignDefault($item['conditions'], array()),
                        YokartHelper::issetAndHasValueOrAssignDefault($item['tax'], array())
                    );
                }
            } else {
                $this->add(
                    $id['id'],
                    $id['name'],
                    $id['code'],
                    $id['price'],
                    $id['quantity'],
                    YokartHelper::issetAndHasValueOrAssignDefault($id['attributes'], array()),
                    YokartHelper::issetAndHasValueOrAssignDefault($id['conditions'], array()),
                    YokartHelper::issetAndHasValueOrAssignDefault($item['tax'], array())
                );
            }

            return $this;
        }

        // validate data
        $item = $this->validate(array(
            'id' => $productcode,
            'product_id' => $id,
            'name' => $name,
            'shipType' => $shipType,
            'price' => YokartHelper::normalizePrice($price),
            'quantity' => $quantity,
            'attributes' => new ItemAttributeCollection($attributes),
            'conditions' => $conditions,
            'tax' => $tax,
        ));
        // get the cart
        $cart = $this->getContent();

        // if the item is already in the cart we will just update it
        if ($cart->has($productcode)) {
            $this->update($productcode, $item);
        } else {
            $this->addRow($productcode, $item);
        }
        return $this;
    }
    /**
     * update a cart
     *
     * @param $id
     * @param $data
     *
     * the $data will be an associative array, you don't need to pass all the data, only the key value
     * of the item you want to update on it
     * @return bool
     */
    public function update($id, $data)
    {
        if ($this->fireEvent('updating', $data) === false) {
            return false;
        }

        $cart = $this->getContent();

        $item = $cart->pull($id);

        foreach ($data as $key => $value) {
            if ($key == 'quantity') {
                if (is_array($value)) {
                    if (isset($value['relative'])) {
                        if ((bool) $value['relative']) {
                            $item = $this->updateQuantityRelative($item, $key, $value['value']);
                        } else {
                            $item = $this->updateQuantityNotRelative($item, $key, $value['value']);
                        }
                    }
                } else {
                    $item = $this->updateQuantityRelative($item, $key, $value);
                }
            } elseif ($key == 'attributes') {
                $item[$key] = new ItemAttributeCollection($value);
            } else {
                $item[$key] = $value;
            }
        }
        $cart->put($id, $item);


        $this->save($cart);

        $this->fireEvent('updated', $item);
        return true;
    }

    /**
     * add condition on an existing item on the cart
     *
     * @param int|string $productId
     * @param CartCondition $itemCondition
     * @return $this
     */
    public function addItemCondition($productId, $itemCondition)
    {
        if ($product = $this->get($productId)) {
            $conditionInstance = "App\\Helpers\\Cart\\CartCondition";

            if ($itemCondition instanceof $conditionInstance) {
                $itemConditionTempHolder = $product['conditions'];

                if (is_array($itemConditionTempHolder)) {
                    array_push($itemConditionTempHolder, $itemCondition);
                } else {
                    $itemConditionTempHolder = $itemCondition;
                }

                $this->update($productId, array(
                    'conditions' => $itemConditionTempHolder
                ));
            }
        }

        return $this;
    }

    /**
     * removes an item on cart by item ID
     *
     * @param $id
     * @return bool
     */
    public function remove($id)
    {
        $cart = $this->getContent();

        if ($this->fireEvent('removing', $id) === false) {
            return false;
        }

        $cart->forget($id);

        $this->save($cart);

        $this->fireEvent('remove', $id);
        return true;
    }

    /**
     * clear cart
     * @return bool
     */
    public function clear()
    {
        if ($this->fireEvent('clearing') === false) {
            return false;
        }

        $this->session->put(
            $this->sessionKeyCartItems,
            array()
        );
        Cart::clearCartConditions();
        $this->fireEvent('cleared');
        return true;
    }

    /**
     * add a condition on the cart
     *
     * @param CartCondition|array $condition
     * @return $this
     * @throws InvalidConditionException
     */
    public function condition($condition)
    {
        if (is_array($condition)) {
            foreach ($condition as $c) {
                $this->condition($c);
            }

            return $this;
        }

        if (!$condition instanceof CartCondition) {
            throw new InvalidConditionException('Argument 1 must be an instance of \'Yokart\Cart\CartCondition\'');
            throw new InvalidConditionException('Argument 1 must be an instance of \'Yokart\Cart\CartCondition\'');
        }

        $conditions = $this->getConditions();

        // Check if order has been applied
        if ($condition->getOrder() == 0) {
            $last = $conditions->last();
            $condition->setOrder(!is_null($last) ? $last->getOrder() + 1 : 1);
        }

        $conditions->put($condition->getName(), $condition);

        $conditions = $conditions->sortBy(function ($condition, $key) {
            return $condition->getOrder();
        });
        $this->saveConditions($conditions);
        return $this;
    }

    /**
     * get conditions applied on the cart
     *
     * @return CartConditionCollection
     */
    public function getConditions()
    {
        return new CartConditionCollection($this->session->get($this->sessionKeyCartConditions));
    }

    /**
     * get condition applied on the cart by its name
     *
     * @param $conditionName
     * @return CartCondition
     */
    public function getCondition($conditionName)
    {
        return $this->getConditions()->get($conditionName);
    }

    /**
     * Get all the condition filtered by Type
     * Please Note that this will only return condition added on cart bases, not those conditions added
     * specifically on an per item bases
     *
     * @param $type
     * @return CartConditionCollection
     */
    public function getConditionsByType($type)
    {
        return $this->getConditions()->filter(function (CartCondition $condition) use ($type) {
            return $condition->getType() == $type;
        });
    }


    /**
     * Remove all the condition with the $type specified
     * Please Note that this will only remove condition added on cart bases, not those conditions added
     * specifically on an per item bases
     *
     * @param $type
     * @return $this
     */
    public function removeConditionsByType($type)
    {
        $this->getConditionsByType($type)->each(function ($condition) {
            $this->removeCartCondition($condition->getName());
        });
    }


    /**
     * removes a condition on a cart by condition name,
     * this can only remove conditions that are added on cart bases not conditions that are added on an item/product.
     * If you wish to remove a condition that has been added for a specific item/product, you may
     * use the removeItemCondition(itemId, conditionName) method instead.
     *
     * @param $conditionName
     * @return void
     */
    public function removeCartCondition($conditionName)
    {
        $conditions = $this->getConditions();

        $conditions->pull($conditionName);

        $this->saveConditions($conditions);
    }

    /**
     * remove a condition that has been applied on an item that is already on the cart
     *
     * @param $itemId
     * @param $conditionName
     * @return bool
     */
    public function removeItemCondition($itemId, $conditionName)
    {
        if (!$item = $this->getContent()->get($itemId)) {
            return false;
        }

        if ($this->itemHasConditions($item)) {
            $tempConditionsHolder = $item['conditions'];
            if (is_array($tempConditionsHolder)) {
                foreach ($tempConditionsHolder as $k => $condition) {
                    if ($condition->getName() == $conditionName) {
                        unset($tempConditionsHolder[$k]);
                    }
                }

                $item['conditions'] = $tempConditionsHolder;
            } else {
                $conditionInstance = "Yokart\\Cart\\CartCondition";

                if ($item['conditions'] instanceof $conditionInstance) {
                    if ($tempConditionsHolder->getName() == $conditionName) {
                        $item['conditions'] = array();
                    }
                }
            }
        }

        $this->update($itemId, array(
            'conditions' => $item['conditions']
        ));

        return true;
    }

    /**
     * remove all conditions that has been applied on an item that is already on the cart
     *
     * @param $itemId
     * @return bool
     */
    public function clearItemConditions($itemId)
    {
        if (!$item = $this->getContent()->get($itemId)) {
            return false;
        }

        $this->update($itemId, array(
            'conditions' => array()
        ));

        return true;
    }

    /**
     * clears all conditions on a cart,
     * this does not remove conditions that has been added specifically to an item/product.
     * If you wish to remove a specific condition to a product, you may use the method: removeItemCondition($itemId, $conditionName)
     *
     * @return void
     */
    public function clearCartConditions()
    {
        $this->session->put(
            $this->sessionKeyCartConditions,
            array()
        );
    }

    /**
     * get cart sub total without conditions
     * @param bool $formatted
     * @return float
     */
    public function getSubTotalWithoutConditions($formatted = true)
    {
        $cart = $this->getContent();

        $sum = $cart->sum(function ($item) {
            return $item->getPriceSum();
        });

        return YokartHelper::formatValue(floatval($sum), $formatted, $this->config);
    }

    /**
     * get cart sub total
     * @param bool $formatted
     * @return float
     */
    public function getSubTotal($formatted = true)
    {
        $cart = $this->getContent();
        $sum = $cart->sum(function (ItemCollection $item) {
            return $item->getPriceSumWithConditions(false);
        });
        // get the conditions that are meant to be applied
        // on the subtotal and apply it here before returning the subtotal
        $conditions = $this
            ->getConditions()
            ->filter(function (CartCondition $cond) {
                return $cond->getTarget() === 'subtotal';
            });

        // if there is no conditions, lets just return the sum
        if (!$conditions->count()) {
            return YokartHelper::formatValue(floatval($sum), $formatted, $this->config);
        }
        // there are conditions, lets apply it
        $newTotal = 0.00;
        $process = 0;

        $conditions->each(function (CartCondition $cond) use ($sum, &$newTotal, &$process) {

            // if this is the first iteration, the toBeCalculated
            // should be the sum as initial point of value.
            $toBeCalculated = ($process > 0) ? $newTotal : $sum;

            $newTotal = $cond->applyCondition($toBeCalculated);

            $process++;
        });

        return YokartHelper::formatValue(floatval($newTotal), $formatted, $this->config);
    }

    /**
     * the new total in which conditions are already applied
     *
     * @return float
     */
    public function getTotal()
    {
        $subTotal = $this->getSubTotal(false);

        $newTotal = 0.00;

        $process = 0;

        $conditions = $this
            ->getConditions()
            ->filter(function (CartCondition $cond) {
                return $cond->getTarget() === 'total';
            });

        // if no conditions were added, just return the sub total
        if (!$conditions->count()) {
            return YokartHelper::formatValue($subTotal, $this->config['format_numbers'], $this->config);
        }

        $conditions
            ->each(function (CartCondition $cond) use ($subTotal, &$newTotal, &$process) {
                $toBeCalculated = ($process > 0) ? $newTotal : $subTotal;

                $newTotal = $cond->applyCondition($toBeCalculated);

                $process++;
            });

        return YokartHelper::formatValue($newTotal, $this->config['format_numbers'], $this->config);
    }

    /**
     * get total quantity of items in the cart
     *
     * @return int
     */
    public function getTotalQuantity()
    {
        $items = $this->getContent();

        if ($items->isEmpty()) {
            return 0;
        }

        $count = $items->sum(function ($item) {
            return $item['quantity'];
        });

        return $count;
    }

    /**
     * get the cart
     *
     * @return CartCollection
     */
    public function getContent()
    {
        return (new CartCollection($this->session->get($this->sessionKeyCartItems)));
    }

    /**
     * check if cart is empty
     *
     * @return bool
     */
    public function isEmpty()
    {
        $cart = new CartCollection($this->session->get($this->sessionKeyCartItems));

        return $cart->isEmpty();
    }

    /**
     * validate Item data
     *
     * @param $item
     * @return array $item;
     * @throws InvalidItemException
     */
    protected function validate($item)
    {
        $rules = array(
            'id' => 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric|min:1',
            'name' => 'required',
        );

        $validator = CartItemValidator::make($item, $rules);

        if ($validator->fails()) {
            throw new InvalidItemException($validator->messages()->first());
        }

        return $item;
    }

    /**
     * add row to cart collection
     *
     * @param $id
     * @param $item
     * @return bool
     */
    protected function addRow($id, $item)
    {
        if ($this->fireEvent('adding', $item) === false) {
            return false;
        }

        $cart = $this->getContent();

        $cart->put($id, new ItemCollection($item, $this->config));
        $this->save($cart);

        $this->fireEvent('added', $item);


        return true;
    }

    /**
     * save the cart
     *
     * @param $cart CartCollection
     */
    protected function save($cart)
    {
        $this->session->put($this->sessionKeyCartItems, $cart);
    }

    /**
     * save the cart conditions
     *
     * @param $conditions
     */
    protected function saveConditions($conditions)
    {
        $this->session->put($this->sessionKeyCartConditions, $conditions);
    }

    /**
     * check if an item has condition
     *
     * @param $item
     * @return bool
     */
    protected function itemHasConditions($item)
    {
        if (!isset($item['conditions'])) {
            return false;
        }

        if (is_array($item['conditions'])) {
            return count($item['conditions']) > 0;
        }

        $conditionInstance = "App\\Helpers\\Cart\\CartCondition";

        if ($item['conditions'] instanceof $conditionInstance) {
            return true;
        }

        return false;
    }

    /**
     * update a cart item quantity relative to its current quantity
     *
     * @param $item
     * @param $key
     * @param $value
     * @return mixed
     */
    protected function updateQuantityRelative($item, $key, $value)
    {
        if (preg_match('/\-/', $value) == 1) {
            $value = (int) str_replace('-', '', $value);

            // we will not allowed to reduced quantity to 0, so if the given value
            // would result to item quantity of 0, we will not do it.
            if (($item[$key] - $value) > 0) {
                $item[$key] -= $value;
            }
        } elseif (preg_match('/\+/', $value) == 1) {
            $item[$key] += (int) str_replace('+', '', $value);
        } else {
            $item[$key] += (int) $value;
        }

        return $item;
    }

    /**
     * update cart item quantity not relative to its current quantity value
     *
     * @param $item
     * @param $key
     * @param $value
     * @return mixed
     */
    protected function updateQuantityNotRelative($item, $key, $value)
    {
        $item[$key] = (int) $value;
        return $item;
    }

    /**
     * Setter for decimals. Change value on demand.
     * @param $decimals
     */
    public function setDecimals($decimals)
    {
        $this->decimals = $decimals;
    }

    /**
     * Setter for decimals point. Change value on demand.
     * @param $dec_point
     */
    public function setDecPoint($dec_point)
    {
        $this->dec_point = $dec_point;
    }

    public function setThousandsSep($thousands_sep)
    {
        $this->thousands_sep = $thousands_sep;
    }
    public function cartItemsCount()
    {
        return Cart::session($this->cartSession)->getContent()->count();
    }

    public function getCartItems($identifier)
    {
        if (! $this->storedCartWithIdentifierExists($identifier)) {
            return;
        }

        $stored = $this->getConnection()->table($this->getTableName())
            ->where('ucart_user_id', $identifier)->first();
            
        return unserialize($stored->ucart_details);
    }

    /**
     * @param $identifier
     * @return bool
     */
    private function storedCartWithIdentifierExists($identifier)
    {
        return $this->getConnection()->table($this->getTableName())->where('ucart_user_id', $identifier)->exists();
    }

    /**
     * Get the database connection.
     *
     * @return \Illuminate\Database\Connection
     */
    private function getConnection()
    {
        $connectionName = $this->getConnectionName();

        return app(DatabaseManager::class)->connection($connectionName);
    }

    /**
     * Get the database connection name.
     *
     * @return string
     */
    private function getConnectionName()
    {
        $connection = config('cart.database.connection');

        return is_null($connection) ? config('database.default') : $connection;
    }

    /**
    * Get the database table name.
    *
    * @return string
    */
    private function getTableName()
    {
        return config('cart.database.table', 'user_cart');
    }

    /**
     * @param $name
     * @param $value
     * @return mixed
     */
    protected function fireEvent($name, $value = [])
    {
        return $this->events->dispatch($this->getInstanceName() . '.' . $name, array_values([$value, $this]));
    }
    public function taxUpdate($countryId = '', $stateId = '')
    {
        if ($tax = Cart::session($this->cartSession)->getCondition('tax')) {
            Cart::taxCalculate($tax->getAttributes()['countryId'], $tax->getAttributes()['stateId']);
        } else {
            Cart::taxCalculate($countryId, $stateId);
        }
    }
    public function getSummary()
    {
        $messages = new \stdClass();
        if ($giftExist = Cart::session($this->cartSession)->getCondition('gift')) {
            $messages = $giftExist->getAttributes()['gift']['message'];
        }
     
        return [
            'subtotal' => Cart::session($this->cartSession)->getSubTotal(),
            'total' => Cart::session($this->cartSession)->getTotal(),
            'tax' => (Cart::session($this->cartSession)->getCondition('tax')) ? abs(Cart::session($this->cartSession)->getCondition('tax')->getValue()) : 0,
            'shipping' => (Cart::session($this->cartSession)->getCondition('shipping')) ? Cart::session($this->cartSession)->getCondition('shipping')->getValue() : 0,
            'giftPrice' => (Cart::session($this->cartSession)->getCondition('gift')) ? Cart::session($this->cartSession)->getCondition('gift')->getValue() : 0,
            'rewards' => (Cart::session($this->cartSession)->getCondition('rewardpoints')) ? Cart::session($this->cartSession)->getCondition('rewardpoints')->getValue() : 0,
            'coupon' => (Cart::session($this->cartSession)->getCondition('coupon')) ? Cart::session($this->cartSession)->getCondition('coupon')->getValue() : 0,
            'couponError' => (Cart::session($this->cartSession)->getCondition('coupon')) ? Cart::session($this->cartSession)->getCondition('coupon')->getError() : false,
            'couponName' => (Cart::session($this->cartSession)->getCondition('coupon')) ? Cart::session($this->cartSession)->getCondition('coupon')->getCode() : '',
            'giftInfo' => $messages,
            'cartCount' => Cart::session($this->cartSession)->getContent()->count()
        ];
    }
    public function getCount()
    {
        return ($this->cartSession) ? Cart::session($this->cartSession)->getContent()->count() : 0;
    }
    public function getShippingAttributes()
    {
        $attributes = [];
        if ($shipAttr = Cart::session($this->cartSession)->getCondition('shipping')) {
            $attributes = $shipAttr->getAttributes();
        }
        return $attributes;
    }
    public function totalShippingProducts()
    {
        $total = 0;

        if ($totalShipping = Cart::session($this->cartSession)->getCondition('shipping')) {
            $total = $totalShipping->getTotalProducts();
        }
        return $total;
    }
    public function shippingErrors()
    {
        $error = false;
        if ($isError = Cart::session($this->cartSession)->getCondition('shipping')) {
            $error = $isError->getError();
        }
        return $error;
    }
    public function taxCalculate($countryId, $stateId, $digital = false)
    {
        $taxDiscount = getConfigValueByName('IS_DISCOUNT_ON_TAX');
        $order = 2;
        if ($taxDiscount != 1) {
            $order = 3;
        }

        $cartCollection = Cart::session($this->cartSession)->getContent();

        $totalTax = [];
        foreach ($cartCollection as $key => $cart) {
            $fields =  'prod_taxcat_id,prod_type,' . Product::getPrice();
            $variantCode = $key;
            if ($cart['product_id'] ===  $key) {
                $variantCode = 0;
            }

            $product = Product::productById($cart['product_id'], $fields, [], $variantCode);

            if ($digital == true && $product->prod_type  != Product::DIGITAL_PRODUCT) {
                $calculatedTax = $cart->tax;
                $cartPrice = $cart->price;
                $totalTax[] =  $calculatedTax;
                $this->update($cart['id'], array(
                        'tax' => $calculatedTax,
                        'price' => priceFormat($cartPrice, false),
                    ));
                continue;
            }
           
            $tax = new TaxHelper($countryId, $stateId);
            $tax = $tax->getRates($product->prod_taxcat_id);
            $tax = new ItemAttributeCollection($tax);

            $cartPrice = priceFormat($product->finalprice, false);

            $taxInclude = getConfigValueByName('PRICE_INCLUDING_TAX');
            if ($taxInclude == 1) {
                $cartPrice = round($cartPrice / (1 + ($tax['value'] / 100)), 2);
            }
            $calculatedTax = $cartPrice * $tax['value'] / 100 * $cart->quantity;
           
            $totalTax[] =  $calculatedTax;
            $this->update($cart['id'], array(
                'tax' => $calculatedTax,
                'price' => priceFormat($cartPrice, false),
            ));
        }
       
        $taxCondition = new CartCondition(array(
            'name' => 'tax',
            'type' => 'tax',
            'target' => 'total', // this condition will be applied to cart's subtotal when getSubTotal() is called.
            'value' => '+' . priceFormat(array_sum($totalTax), false),
            'order' => $order,
            'attributes' => ['countryId' => $countryId, 'stateId' => $stateId]
        ));
        Cart::session($this->cartSession)->condition($taxCondition);
    }
    public function shippingCalculate($countryId, $stateId)
    {
        $cartCollection = Cart::session($this->cartSession)->getContent();
        $shipping = new ShippingHelper($countryId, $stateId);
        $shippingResults = $shipping->getShipments($cartCollection);

        $errors = false;
        if (isset($shippingResults['error'])) {
            $errors = true;
            unset($shippingResults['error']);
        }
        $totalShipping = 0;
        $shippingAttributes = [];
        if ($shipping = Cart::session($this->cartSession)->getCondition('shipping')) {
            $totalShipping = $shipping->getValue();
            $shippingAttributes = $shipping->getAttributes();
        }
        $shippingCondition = new CartCondition(array(
            'name' => 'shipping',
            'type' => 'shipping',
            'totalProducts' => count($shippingResults),
            'error' => $errors,
            'target' => 'total', // this condition will be applied to cart's subtotal when getSubTotal() is called.
            'value' => priceFormat($totalShipping, false),
            'order' => 4,
            'attributes' => $shippingAttributes
        ));
        Cart::session($this->cartSession)->condition($shippingCondition);
        //$this->shippingTaxUpdate();
        return $shippingResults;
    }

    public function shippingUpdate($amount, $attributes = [])
    {
        $totalShippings = 0;
        $shippingError = 0;
        if (Cart::session($this->cartSession)->getCondition('shipping')) {
            $totalShippings = Cart::session($this->cartSession)->getCondition('shipping')->getTotalProducts();
            $shippingError = Cart::session($this->cartSession)->getCondition('shipping')->getError();
        }

        $shippingCondition = new CartCondition(array(
            'name' => 'shipping',
            'type' => 'shipping',
            'target' => 'total', // this condition will be applied to cart's subtotal when getSubTotal() is called.
            'totalProducts' => $totalShippings,
            'error' => $shippingError,
            'value' => priceFormat($amount, false),
            'order' => 4,
            'attributes' => $attributes
        ));
        Cart::session($this->cartSession)->condition($shippingCondition);
        // $this->shippingTaxUpdate();
    }
    public function shippingTaxUpdate()
    {
        $shippingTax = getConfigValues(['IS_TAX_ON_SHIPPING', 'SHIPPING_TAX_CHARGES']);
        $shipping = (Cart::session($this->cartSession)->getCondition('shipping')) ? Cart::session($this->cartSession)->getCondition('shipping')->getValue() : 0;
        if ($shippingTax['IS_TAX_ON_SHIPPING'] == 1 && $shipping != 0) {
            $chargeTax = 0;
            if ($shippingTax['SHIPPING_TAX_CHARGES'] != 0) {
                $chargeTax = $shipping * $shippingTax['SHIPPING_TAX_CHARGES'] / 100;
            }
            $shippingTaxCondition = new CartCondition(array(
                'name' => 'Tax on shipping',
                'type' => 'shippingTax',
                'target' => 'total',
                'value' => '+' . priceFormat($chargeTax, false),
                'order' => 5
            ));
            Cart::session($this->cartSession)->condition($shippingTaxCondition);
        }
    }

    public function applyCoupon($couponCode, $amount, $histroyId, $attributes = [], $error = false, $getCoupnType = '')
    {
        $taxDiscount = getConfigValueByName('IS_DISCOUNT_ON_TAX');
        $order = 3;
        if ($taxDiscount != 1) {
            $order = 2;
        }
        $couponCondition = new CartCondition(array(
            'name' => 'coupon',
            'type' => 'coupon',
            'getCoupnType' => $getCoupnType,
            'code' => $couponCode,
            'id' => $histroyId,
            'target' => 'total',
            'error' => $error,
            'value' => '-' . priceFormat($amount, false),
            'order' => $order,
            'attributes' => $attributes
        ));
        Cart::session($this->cartSession)->condition($couponCondition);
    }
    public function getPickups($keyword)
    {
        $cartCollection = Cart::session($this->cartSession)->getContent();
        $pickups = [];
        foreach ($cartCollection as $key => $cart) {
            if ($cart['shipType'] == 'pickup') {
                if (!empty($keyword)) {
                    $pickups[$key] = $cart[$keyword];
                } else {
                    $pickups[$key] = $cart;
                }
            }
        }
        return $pickups;
    }
    public function giftWrapUpdate($amount, $totalPrice, $attributes)
    {
        $giftCondition = new CartCondition(array(
            'name' => 'gift',
            'type' => 'gift',
            'target' => 'total',
            'totalProducts' => priceFormat($totalPrice, false),
            'value' => $amount,
            'order' => 6,
            'attributes' => $attributes
        ));
        Cart::session($this->cartSession)->condition($giftCondition);
    }
    public function updateRewardPoints($amount, $points)
    {
        $rewardPoints = new CartCondition(array(
            'name' => 'rewardpoints',
            'type' => 'rewardpoints',
            'target' => 'total',
            'points' => $points,
            'value' => '-' . priceFormat($amount, false),
            'order' => 7
        ));
        Cart::session($this->cartSession)->condition($rewardPoints);
    }

    public function isDigitalProduct($digitalOnly = true)
    {
        $productIds = Cart::session($this->cartSession)->getContent()->getKeys();
        if (count($productIds) > 0) {
            $digitalCount = Product::where("prod_type", Product::DIGITAL_PRODUCT)->whereIn("prod_id", $productIds)->count();
            if ($digitalOnly) {
                if ($digitalCount == count($productIds)) {
                    return true;
                }
            } elseif ($digitalCount != 0) {
                return true;
            }
        }
        return false;
    }

    public function isOutOfStockProducts()
    {
        $cartCollection = Cart::session($this->cartSession)->getContent();

        if (count($cartCollection) > 0) {
            $cartFields =  'prod_id,prod_min_order_qty, prod_stock_out_sellable, ' . Product::getPrice();

            foreach ($cartCollection as $key => $collection) {
                $code = $key;
                if ($collection->product_id ===  $key) {
                    $code = 0;
                }
                $product = Product::productById($collection->product_id, $cartFields, [], $code);
                $outOFStock = productStockVerify($product, $collection->quantity, $product->pov_code);
                if ($outOFStock == false) {
                    return true;
                    break;
                }
            }
        }
        return false;
    }
}
