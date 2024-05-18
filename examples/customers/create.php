<?php

use TiSinProblemas\FacturaCom;

/**
 * Create a new customer in your Factura.com account
 */

$API_KEY = "YOUR_API_KEY";
$SECRET_KEY = "YOUR_SECRET_KEY";
$SANDBOX_MODE = true;

$facturacom = new FacturaCom($API_KEY, $SECRET_KEY, $SANDBOX_MODE);

$rfc = "XIA190128J61";
$company_name = "XENON INDUSTRIAL ARTICLES";
$zip_code = "76343";
$email = "email@example.com";
$tax_regime_code = 601;

$new_customer = $facturacom->customer->create($rfc, $company_name, $zip_code, $email, $tax_regime_code);

var_dump($new_customer);
