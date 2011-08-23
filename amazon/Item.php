<?php

class Item
{
    private $name;
    private $price;
    private $release_date;
    private $stock_quantity;
    private $discount_quantity;
    private $discount_percentage;

    public function __construct($name, $price, $release_date, $stock_quantity)
    {
        if (!is_string($name) || !is_string($price) || !is_string($release_date) || !is_string($stock_quantity)) {
            throw new InvalidArgumentException('Specified Title is not exists.');
        }

        $this->title = $name;
        $this->price = $price;
        $this->release_date = $release_date;
        $this->stock_quantity = $stock_quantity;
    }

    public function getName()
    {
        return $this->title;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getReleaseDate()
    {
        return $this->release_date;
    }

    public function getStockQuantity()
    {
        return $this->stock_quantity;
    }

    public function setDiscounter($discount_quantity, $discount_percentage)
    {
        if (!is_int($discount_quantity) || !is_int($discount_percentage)) {
            throw new InvalidArgumentException('Arguments is spesified integer.');
        }

        $this->discount_quantity = $discount_quantity;
        $this->discount_percentage = $discount_percentage;
    }

    public function getDiscounter()
    {
        return array($this->discount_quantity, $this->discount_percentage);
    }
}
