<?php

use TiSinProblemas\FacturaCom;

/**
 * Get a list of all existing series
 */

$API_KEY = "YOUR_API_KEY";
$SECRET_KEY = "YOUR_SECRET_KEY";
$SANDBOX_MODE = true;

$facturacom = new FacturaCom($API_KEY, $SECRET_KEY, $SANDBOX_MODE);

$all_series = $facturacom->series->all();

var_dump($all_series);
