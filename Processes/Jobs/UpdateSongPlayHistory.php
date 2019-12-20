<?php

require_once __DIR__ . '/../../Factory.php';
require_once __DIR__ . '/AccountCredentials.php';

$startTime = microtime(true);
$model = \LOE\Model::getByLabel('Song');
$run = \LOE\Factory::createModel('PlayHistoryRun');
$run->modelId = $model->UID;
$run->startTime = date("Y-m-d H:i:s");

try{
  $processor = \LOE\Factory::updatePlayHistory($model,ACCOUNT_USER,ACCOUNT_PASSWORD);
  if(count($processor->exceptions)){
    print_r($processor->exceptions);
  }
}catch(\Exception $e){
  echo $e->getMessage() . "\n";
  exit;
}

$endTime = microtime(true);
$executionSeconds = $endTime - $startTime;
$run->endTime = date("Y-m-d H:i:s");
$run->runTime = $executionSeconds;
$run->create();
