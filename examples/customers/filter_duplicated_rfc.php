<?php

use TiSinProblemas\FacturaCom;

/**
 * Filter duplicated customers by their RFC
 */

$API_KEY = "YOUR_API_KEY";
$SECRET_KEY = "YOUR_SECRET_KEY";
$SANDBOX_MODE = true;

$facturacom = new FacturaCom($API_KEY, $SECRET_KEY, $SANDBOX_MODE);

$customers = $facturacom->customer->filter_duplicated_by_rfc("XAXX010101000");

var_dump($customers);
