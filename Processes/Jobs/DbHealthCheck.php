<?php

require_once __DIR__ . '/../../Factory.php';
require_once __DIR__ . '/AccountCredentials.php';

if(!isset($argv[1])){
  echo "Must provide message recipient\n";
  exit;
}else{
  $msgTo = $argv[1];
}
$tables = array(
  \LOE\Movie::TABLE,
  \LOE\Episode::TABLE,
  \LOE\Song::TABLE,
  \LOE\Anime::TABLE
);
try{
  $authToken = \LOE\Factory::authenticate(ACCOUNT_USER,ACCOUNT_PASSWORD)->token;
}catch(\Exception $e){
  echo $e->getMessage() . "\n";
  exit;
}
foreach($table as $table){
  $startTime = microtime(true);
  $model = \LOE\Model::getByLabel($table);
  $run = \LOE\Factory::createModel('DbCheck');
  $run->startTime = date("Y-m-d H:i:s");
  $run->modelId = $model->UID;
  try{
    $scanner = \LOE\Factory::createDbScanner($model,$msgTo,$authToken);
  }catch(\Exception $e){
    echo $e->getMessage() . "\n";
  }
  $endTime = microtime(true);
  $executionSeconds = $endTime - $startTime;
  $run->endTime = date("Y-m-d H:i:s");
  $run->runTime = $executionSeconds;
  $run->recordCount = $scanner->recordCount;
  $run->missingCount = count($scanner->missing);
  $run->create();
}
