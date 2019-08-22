<?php

require_once __DIR__ . '/../../Factory.php';

try{
  $obj = \LOE\Factory::extractArchives(\LOE\LoeBase::WEBROOT . "/LOE/holding_bay/music");
  print_r($obj->exceptions);
}catch(\Exception $e){
  echo $e->getMessage() . "\n";
}
