<?php namespace LOE;

require_once __DIR__ . '/../../Factory.php';
require_once __DIR__ . '/../../Libs/ComicVine/ComicVine.php';

$model = Factory::getModel(Comic\Comic::TABLE);
$scanner = Factory::createHoldingBayScanner($model);

print_r($scanner->targetModels);
