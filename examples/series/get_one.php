<?php

use TiSinProblemas\FacturaCom;

/**
 * Get a series by its UID
 */

$API_KEY = "YOUR_API_KEY";
$SECRET_KEY = "YOUR_SECRET_KEY";
$SANDBOX_MODE = true;

$facturacom = new FacturaCom($API_KEY, $SECRET_KEY, $SANDBOX_MODE);

$series_uid = "58444";

$series = $facturacom->series->get_by_uid($series_uid);

var_dump($series);
