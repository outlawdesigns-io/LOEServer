<?php

require_once __DIR__ . '/../../Factory.php';

if(!isset($argv[1])){
  echo "Must provide message recipient\n";
  exit;
}else{
  $msgTo = $argv[1];
  $models = \LOE\Model::getAll();
}
try{
  $authToken = \LOE\Factory::authenticate(getenv('OD_ACCOUNT_USER'),getenv('OD_ACCOUNT_PASS'))->token;
}catch(\Exception $e){
  echo $e->getMessage() . "\n";
  exit;
}
foreach($models as $model){
  $startTime = microtime(true);
  $run = \LOE\Factory::createModel('FsCheck');
  $run->startTime = date("Y-m-d H:i:s");
  $run->modelId = $model->UID;
  try{
    $scanner = \LOE\Factory::createFsScanner($model,$msgTo,$authToken);
  }catch(\Exception $e){
    echo $e->getMessage() . "\n";
  }
  $endTime = microtime(true);
  $executionSeconds = $endTime - $startTime;
  $run->endTime = date("Y-m-d H:i:s");
  $run->runTime = $executionSeconds;
  $run->fileCount = count($scanner->files);
  $run->missingCount = count($scanner->missing);
  $run->create();
}
