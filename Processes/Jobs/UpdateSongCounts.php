<?php

require_once __DIR__ . '/../../Factory.php';
require_once __DIR__ . '/AccountCredentials.php';

try{
  $processor = \LOE\Factory::updateSongCounts(ACCOUNT_USER,ACCOUNT_PASSWORD);
}catch(\Exception $e){
  echo $e->getMessage() . "\n";
}

if(count($processor->exceptions)){
  print_r($processor->exceptions);
}
