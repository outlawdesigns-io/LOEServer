<?php namespace LOE;

require_once __DIR__ . '/../../Factory.php';

$model = Factory::getModel(COmic\Comic::TABLE);

$scanner = Factory::createHoldingBayScanner($model);

print_r($scanner->targetModels);
