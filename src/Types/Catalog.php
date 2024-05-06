<?php

namespace TiSinProblemas\FacturaCom\Types;

class KeyNameObject
{
    public $key;
    public $name;

    public function __construct(string $key, string $name)
    {
        $this->key = $key;
        $this->name = $name;
    }
}
class ProductService extends KeyNameObject
{
    public $complement;

    public function __construct(string $key, string $name, string $complement)
    {
        $this->key = $key;
        $this->name = $name;
        $this->complement = $complement;
    }
}

class CustomsHouse extends KeyNameObject
{
}

class UnitOfMeasure extends KeyNameObject
{
}

class PaymentMethod extends KeyNameObject
{
}

class Tax extends KeyNameObject
{
}

class PaymentOption extends KeyNameObject
{
}
