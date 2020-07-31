<?php namespace LOE;

require_once __DIR__ . '/../../Factory.php';
require_once __DIR__ . '/../../Libs/ComicVine/ComicVine.php';


$model = Factory::getModel(Comic\Comic::TABLE);
$scanner = Factory::createHoldingBayScanner($model);

$target = $scanner->targetModels[count($scanner->targetModels) - 1];

$processor = Factory::createHoldingBayProcessor('Comic',$target);

print_r($processor->comic);
