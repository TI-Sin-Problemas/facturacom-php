<?php

namespace TiSinProblemas\FacturaCom\Types;

class ProductService
{
    public $key;
    public $name;
    public $complement;

    public function __construct(string $key, string $name, string $complement)
    {
        $this->key = $key;
        $this->name = $name;
        $this->complement = $complement;
    }
}
