<?php

class ShoppingCart
{
    const FREE_DELIVERY_OVER_PRICE = 10000;

    private $items_price    = 0;
    private $delivery_price = 0;

    private $is_possible_deliveried_hurry = true;
    private $is_possible_atm_payment      = true;
    private $delivery_types = array(
                'normal' => 0,
                'hurry'  => 200,
            );
    private $payment_types = array('card', 'cash', 'atm');

    public function addToCart($item, $specified_quantity = 1)
    {
        if (!$item instanceof Item) {
            throw new InvalidArgumentException('Argument::item is not found.');
        }

        $stock_quantity = $item->getStockQuantity();
        if ($stock_quantity === 0 || $stock_quantity < $specified_quantity) {
            $this->is_possible_deliveried_hurry = false;
        }

        $item_price = $item->getPrice() * $specified_quantity;

        list($discount_number, $discount_percentage) = $item->getDiscounter();
        if (is_int($discount_number) && $discount_number <= $specified_quantity) {
            $item_price = $item_price * $discount_percentage * 0.01;
        }

        $this->items_price += $item_price;
    }

    public function getTotalPrice()
    {
        $total_price = $this->delivery_price + $this->items_price;
        if ($total_price >= self::FREE_DELIVERY_OVER_PRICE) {
            $total_price -= $this->delivery_price;
        }

        return $total_price;
    }

    public function setDeliveryType($type)
    {
        if (!is_string($type) || !array_key_exists($type, $this->delivery_types)) {
            throw new InvalidArgumentException('Specified delivery type is not found.');
        }
        if ($type === 'hurry') {
            if (!$this->is_possible_deliveried_hurry) {
                throw new InvalidArgumentException('Cart in some item is no stocked.');
            } else {
                $this->is_possible_atm_payment = false;
            }
        }

        $this->delivery_price += $this->delivery_types[$type];
    }

    public function setPaymentType($type)
    {
        if (!is_string($type) || !in_array($type, $this->payment_types)) {
            throw new InvalidArgumentException('Specified type is not found.');
        }
        if ($type === 'atm' && !$this->is_possible_atm_payment) {
            throw new InvalidArgumentException('ATM Payment can not selected, because delivery type is "hurry"');
        }
    }

    public function placeOrder()
    {
        // initialize
        $this->items_price = 0;
        $this->delivery_price = 0;
        $this->is_possible_deliveried_hurry = true;
        $this->is_possible_atm_payment = true;
    }
}
