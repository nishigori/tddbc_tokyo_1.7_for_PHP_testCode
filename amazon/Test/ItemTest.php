<?php
require_once '../Item.php';

class ItemTest extends PHPUnit_Framework_TestCase
{
    protected $item;

    public function setUp()
    {
        $this->item = new Item('Perfect PHP', '3600', '2010-11', '2');
    }

    public function testGetItemName()
    {
        $expected = 'Perfect PHP';
        $this->assertEquals($expected, $this->item->getName());
    }

    public function testGetPrice()
    {
        $expected = '3600';
        $this->assertEquals($expected, $this->item->getPrice());
    }

    public function testGetReleaseDate()
    {
        $expected = '2010-11';
        $this->assertEquals($expected, $this->item->getReleaseDate());
    }

    public function testGetStockQuantity()
    {
        $expected = '2';
        $this->assertEquals($expected, $this->item->getStockQuantity());
    }

    public function testDiscountQuantity()
    {
        $discount_quantity = 2;
        $discount_percentage = 80;
        $this->item->setDiscounter($discount_quantity, $discount_percentage);

        $expected = array($discount_quantity, $discount_percentage);
        $this->assertEquals($expected, $this->item->getDiscounter());

        try {
            $this->item->setDiscounter('string_not_int', 'string_not_int');
        }
        catch (InvalidArgumentException $e) {
            print $e->getMessage() . PHP_EOL;
            return;
        }

        $this->fail();
    }
}
