<?php

use TiSinProblemas\FacturaCom;

/**
 * Update a customer company name
 */

$API_KEY = "YOUR_API_KEY";
$SECRET_KEY = "YOUR_SECRET_KEY";
$SANDBOX_MODE = true;

$facturacom = new FacturaCom($API_KEY, $SECRET_KEY, $SANDBOX_MODE);

$customer_uid = "662ecb2e03d1e";
$new_company_name = "XENON INDUSTRIAL ARTICLES 2";

$result = $facturacom->customer->update($customer_uid, company_name: $new_company_name);

var_dump($result);
