<?php

namespace TiSinProblemas\FacturaCom\Types;

class Series
{
    public $id;
    public $name;
    public $type;
    public $description;
    public $status;

    public function __construct(int $id, string $name, string $type, string $description, string $status)
    {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->description = $description;
        $this->status = $status;
    }
}
