<?php

require_once __DIR__ . '/../../Factory.php';

$models = \LOE\Model::getAll();

foreach($models as $model){
  try{
    \LOE\Factory::updateModelStorage($model);
  }catch(\Exception $e){
    echo $e->getMessage() . "\n";
  }
}
