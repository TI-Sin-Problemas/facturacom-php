<?php

use TiSinProblemas\FacturaCom;

/**
 * Get all customers from your Factura.com account
 */

$API_KEY = "YOUR_API_KEY";
$SECRET_KEY = "YOUR_SECRET_KEY";
$SANDBOX_MODE = true;

$facturacom = new FacturaCom($API_KEY, $SECRET_KEY, $SANDBOX_MODE);

$customers = $facturacom->customer->all();

var_dump($customers);
