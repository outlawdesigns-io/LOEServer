<?php

require_once __DIR__ . '/../../Factory.php';
require_once __DIR__ . '/AccountCredentials.php';

$models = \LOE\Model::getAll();

foreach($models as $model){
  $startTime = microtime(true);
  $run = \LOE\Factory::createModel('PlayHistoryRun');
  $run->modelId = $model->UID;
  $run->startTime = date("Y-m-d H:i:s");
  try{
    $processor = \LOE\Factory::updatePlayHistory($model,ACCOUNT_USER,ACCOUNT_PASSWORD);
    $run->exceptionCount = count($processor->exceptions);
    $run->processedCount = $processor->processedCount;
    if(count($processor->exceptions)){
      print_r($processor->exceptions);
    }
  }catch(\Exception $e){
    $run->exceptionCaught = 1;
    $run->$exceptionMessage = $e->getMessage();
  }
  $endTime = microtime(true);
  $executionSeconds = $endTime - $startTime;
  $run->endTime = date("Y-m-d H:i:s");
  $run->runTime = $executionSeconds;
  $run->create();
}
