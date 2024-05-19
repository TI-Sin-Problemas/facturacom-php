<?php

namespace TiSinProblemas;

require_once 'Constants.php';
require_once 'Exceptions/FacturaComException.php';
require_once 'Http/BaseCilent.php';
require_once 'Resources/Catalog.php';
require_once 'Resources/Cfdi.php';
require_once 'Resources/Customer.php';
require_once 'Resources/Draft.php';
require_once 'Resources/Series.php';
require_once 'Types/Catalog.php';
require_once 'Types/Cfdi.php';
require_once 'Types/Customer.php';
require_once 'Types/Series.php';

use TiSinProblemas\FacturaCom\Resources;

class FacturaCom
{
    public $draft;
    public $catalog;
    public $cfdi;
    public $customer;
    public $series;

    public function __construct($API_KEY, $SECRET_KEY, $SANDBOX_MODE = false)
    {
        $this->draft = new Resources\Draft($API_KEY, $SECRET_KEY, $SANDBOX_MODE);
        $this->catalog = new Resources\Catalog($API_KEY, $SECRET_KEY, $SANDBOX_MODE);
        $this->cfdi = new Resources\Cfdi($API_KEY, $SECRET_KEY, $SANDBOX_MODE);
        $this->customer = new Resources\Customer($API_KEY, $SECRET_KEY, $SANDBOX_MODE);
        $this->series = new Resources\Series($API_KEY, $SECRET_KEY, $SANDBOX_MODE);
    }
}
