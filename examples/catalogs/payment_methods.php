<?php

use TiSinProblemas\FacturaCom;

/**
 * Get the catalog of payment methods (formas de pago)
 */

$API_KEY = "YOUR_API_KEY";
$SECRET_KEY = "YOUR_SECRET_KEY";
$SANDBOX_MODE = true;

$facturacom = new FacturaCom($API_KEY, $SECRET_KEY, $SANDBOX_MODE);

$result = $facturacom->catalog->payment_methods->all();

var_dump($result);
