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
  \LOE\Movie\Movie::TABLE,
  \LOE\Tv\Episode::TABLE,
  \LOE\Music\Song::TABLE,
  \LOE\Anime\Anime::TABLE
);
try{
  $authToken = \LOE\Factory::authenticate(ACCOUNT_USER,ACCOUNT_PASSWORD)->token;
}catch(\Exception $e){
  echo $e->getMessage() . "\n";
  exit;
}
foreach($table as $table){
  try{
    \LOE\LoeFactory::createFsScanner($table,$msgTo,$authToken);
  }catch(\Exception $e){
    echo $e->getMessage() . "\n";
  }
}
