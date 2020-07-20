<?php namespace LOE;

require_once __DIR__ . '/../../Factory.php';


try{
  $proc = new \LOE\Music\HoldingBayAutoProcessor();
  print_r($proc->exceptions);
}catch(\Exception $e){
  echo $e->getMessage() . "\n";
  exit;
}
