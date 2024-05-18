<?php

use TiSinProblemas\FacturaCom;

/**
 * Delete a customer
 */

$API_KEY = "YOUR_API_KEY";
$SECRET_KEY = "YOUR_SECRET_KEY";
$SANDBOX_MODE = true;

$facturacom = new FacturaCom($API_KEY, $SECRET_KEY, $SANDBOX_MODE);

$customer_uid = "662ecb2e03d1e";

$result = $facturacom->customer->delete($customer_uid);
