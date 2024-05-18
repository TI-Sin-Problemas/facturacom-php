<?php

use TiSinProblemas\FacturaCom;

/**
 * Get a customer from your Factura.com account by its RFC or UID
 */

$API_KEY = "YOUR_API_KEY";
$SECRET_KEY = "YOUR_SECRET_KEY";
$SANDBOX_MODE = true;

$facturacom = new FacturaCom($API_KEY, $SECRET_KEY, $SANDBOX_MODE);

$customer_by_rfc = $facturacom->customer->get_by_id("XAXX010101000");
var_dump($customer_by_rfc);

$customer_by_uid = $facturacom->customer->get_by_id("662ecb2e03d1e");
var_dump($customer_by_uid);
