<?php

use TiSinProblemas\FacturaCom;

/**
 * Get the catalog of currencies
 */

$API_KEY = "YOUR_API_KEY";
$SECRET_KEY = "YOUR_SECRET_KEY";
$SANDBOX_MODE = true;

$facturacom = new FacturaCom($API_KEY, $SECRET_KEY, $SANDBOX_MODE);

$result = $facturacom->catalog->currencies->all();

var_dump($result);
