<?php
require_once '../ShoppingCart.php';
require_once '../Item.php';

class ShoppingCartTest extends PHPUnit_Framework_TestCase
{
    protected $cart;

    public function setUp()
    {
        $this->cart = new ShoppingCart();
    }

    public function testAddItem()
    {
        $item = new Item('Perfect PHP', '3600', '2010-11', '2');
        $this->cart->addToCart($item);

        try {
            $invalid_item = new stdClass;
            $this->cart->addToCart($invalid_item);
        }
        catch (InvalidArgumentException $e) {
            print $e->getMessage() . PHP_EOL;
            return;
        }

        $this->fail();
    }

    public function testSetInvalidDeliveriesType()
    {
        try {
            $this->cart->setDeliveryType('hogehoge');
        }
        catch (InvalidArgumentException $e) {
            print $e->getMessage() . PHP_EOL;
            return;
        }

        $this->fail();
    }

    public function testSetDisablePaymentType()
    {
        try {
            $this->cart->setPaymentType('not_payment_type');
        }
        catch (InvalidArgumentException $e) {
            print $e->getMessage() . PHP_EOL;
            return;
        }

        $this->fail();
    }

    public function testGetTotalPrice()
    {
        $item = new Item('Perfect PHP', '3600', '2010-11', '2');
        $this->cart->addToCart($item);

        $delivery_type  = 'hurry';
        $payment_type   = 'card';
        $delivery_price = 200;

        $this->cart->setDeliveryType($delivery_type);
        $this->cart->setPaymentType($payment_type);

        $expected = $item->getPrice() + $delivery_price;

        $this->assertEquals($expected, $this->cart->getTotalPrice());
    }

    public function testUnableSelectDeriveryHurryInStockQuantity0()
    {
        $item = new Item('TEST-DRIVEN DEVELOPMENT', '3000', '2006-12', '0');
        $this->cart->addToCart($item);
        try {
            $delivery_type = 'hurry';
            $this->cart->setDeliveryType($delivery_type);
        }
        catch (InvalidArgumentException $e) {
            print $e->getMessage() . PHP_EOL;
            return;
        }

        $this->fail();
    }

    public function testUnableSelectDeriveyHurryInStockQuantityOver()
    {
        $item = new Item('Perfect PHP', '3600', '2010-11', '2');
        $this->cart->addToCart($item, 5);

        try {
            $delivery_type = 'hurry';
            $this->cart->setDeliveryType($delivery_type);
        }
        catch (InvalidArgumentException $e) {
            print $e->getMessage() . PHP_EOL;
            return;
        }

        $this->fail();
    }

    public function testUnableSelectPaymentATMInDeliveryHurry()
    {
        $item = new Item('Perfect PHP', '3600', '2010-11', '2');
        $this->cart->addToCart($item);

        $delivery_type = 'hurry';
        $this->cart->setDeliveryType($delivery_type);

        try {
            $payment_type = 'atm';
            $this->cart->setPaymentType($payment_type);
        }
        catch (Exception $e) {
            print $e->getMessage() . PHP_EOL;
            return;
        }

        $this->fail();
    }

    public function testDiscountTotalPrice()
    {
        $item = new Item('RedBull', '240', '2011-07', '50');
        $discount_number = 24;
        $discount_percentage = 20;
        $item->setDiscounter($discount_number, $discount_percentage);

        $number = 40;
        $this->cart->addToCart($item, $number);

        $expected = $item->getPrice() * $number * $discount_percentage * 0.01;
        $this->assertEquals($expected, $this->cart->getTotalPrice());
    }

    public function testDiscountTotalPriceOver10000()
    {
        $item = new Item('RedBull', '240', '2011-07', '100');
        $number = 77;
        $item_price = $item->getPrice() * $number;
        $this->cart->addToCart($item, $number);

        $delivery_price = 0;
        $this->cart->setDeliveryType('hurry');

        $expected = $item_price + $delivery_price;
        $this->assertEquals($expected, $this->cart->getTotalPrice());
    }

    public function testPlaceOrderAfterInInitialize()
    {
        $item = new Item('Perfect PHP', '3600', '2010-11', '2');
        $this->cart->addToCart($item);

        $delivery_type = 'hurry';
        $this->cart->setDeliveryType($delivery_type);

        $this->cart->placeOrder();

        $expected = 0;
        $this->assertEquals($expected, $this->cart->getTotalPrice());
    }
}
