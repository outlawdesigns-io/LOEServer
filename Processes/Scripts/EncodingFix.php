<?php

require_once __DIR__ . '/Factory.php';

//$ids = file('uids');
$ids = array(80454,80455,80456,80457,80458,80459,80460,80461,80462,80463);
foreach($ids as $id){
  $model = \LOE\Factory::createModel('Song',$id);
  $tags = $model->getMp3Tags();
  //$model->artist = html_entity_decode($tags['artist']);
  $model->title = html_entity_decode($tags['title']);
  //$model->album = html_entity_decode($tags['album']);
  echo $model->title . " | " . $model->album . "\n";
  $model->update();
}
