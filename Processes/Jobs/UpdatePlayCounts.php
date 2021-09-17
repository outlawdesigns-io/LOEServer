<?php

require_once __DIR__ . '/../../Factory.php';

$models = \LOE\Model::getAll();

foreach($models as $model){
  $startTime = microtime(true);
  $run = \LOE\Factory::createModel('PlayCountRun');
  $run->modelId = $model->UID;
  $run->startTime = date("Y-m-d H:i:s");
  try{
    $processor = \LOE\Factory::updatePlayCounts($model,getenv('OD_ACCOUNT_USER'),getenv('OD_ACCOUNT_PASS'));
    $run->searchResultCount = $processor->searchResultCount;
    $run->exceptionCount = count($processor->exceptions);
    $run->processedCount = $processor->processedCount;
    if(count($processor->exceptions)){
      print_r($processor->exceptions);
    }
  }catch(\Exception $e){
    $run->exceptionCaught = 1;
    $run->exceptionMessage = $e->getMessage();
  }
  $endTime = microtime(true);
  $executionSeconds = $endTime - $startTime;
  $run->endTime = date("Y-m-d H:i:s");
  $run->runTime = $executionSeconds;
  $run->create();
}
