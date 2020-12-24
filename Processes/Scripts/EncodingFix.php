<?php

require_once __DIR__ . '/../../Factory.php';

// $ids = file('uids');
$ids = array(98541,98547,98606,99046,99212,99717,99719);
foreach($ids as $id){
  $model = \LOE\Factory::createModel('Song',$id);
  try{
    $tags = $model->getMp3Tags();
  }catch(\Exception $e){
    echo $e->getMessage() . "\n";
    continue;
  }
  $model->title = html_entity_decode($tags['title']);
  // $model->artist = html_entity_decode($tags['artist']);
  //$model->album = html_entity_decode($tags['album']);
  echo $model->title . " | " . $model->album . "\n";
  $model->update();
}
