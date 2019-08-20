<?php

require_once __DIR__ . '/../../Factory.php';
require_once __DIR__ . '/AccountCredentials.php';

try{
  \LOE\Factory::updateSongCounts(ACCOUNT_USER,ACCOUNT_PASSWORD);
}catch(\Exception $e){
  echo $e->getMessage() . "\n";
}
