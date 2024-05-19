<?php

use TiSinProblemas\FacturaCom;

/**
 * Delete a series
 */

$API_KEY = "YOUR_API_KEY";
$SECRET_KEY = "YOUR_SECRET_KEY";
$SANDBOX_MODE = true;

$facturacom = new FacturaCom($API_KEY, $SECRET_KEY, $SANDBOX_MODE);

$series_uid = "58444";

$result = $facturacom->series->delete($series_uid);

var_dump($result);
