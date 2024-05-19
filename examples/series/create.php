<?php

use TiSinProblemas\FacturaCom;

/**
 * Create a new series
 */

$API_KEY = "YOUR_API_KEY";
$SECRET_KEY = "YOUR_SECRET_KEY";
$SANDBOX_MODE = true;

$facturacom = new FacturaCom($API_KEY, $SECRET_KEY, $SANDBOX_MODE);

$letter = "F";
$document_type = FacturaCom\Constants\DocumentType::FACTURA;

$result = $facturacom->series->create($letter, $document_type);

var_dump($result);
