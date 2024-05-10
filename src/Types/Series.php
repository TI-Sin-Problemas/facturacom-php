<?php

namespace TiSinProblemas\FacturaCom\Types;

class Series
{
    public $uid;
    public $name;
    public $type;
    public $description;
    public $status;

    public function __construct(int $uid, string $name, string $type, string $description, string $status)
    {
        $this->uid = $uid;
        $this->name = $name;
        $this->type = $type;
        $this->description = $description;
        $this->status = $status;
    }
}
