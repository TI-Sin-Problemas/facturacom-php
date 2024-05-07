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

class Currency extends KeyNameObject
{
}

class Country extends KeyNameObject
{
}

class TaxRegime extends KeyNameObject
{
}

class RelationType extends KeyNameObject
{
}

class CfdiUsage extends KeyNameObject
{
    public $use;

    public function __construct(string $key, string $name, string $use)
    {
        $this->key = $key;
        $this->name = $name;
        $this->use = $use;
    }
}
