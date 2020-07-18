<?php

require_once __DIR__ . '/Factory.php';

$songs = \LOE\Music\Song::getAll();
$exceptions = array();
$unreadable = array();
foreach($songs as $song){
  try{
    $tags = $song->getMp3Tags();
  }catch(\Exception $e){
    $unreadable[] = $song->file_path;
    continue;
  }
  if(isset($tags['bpm'])){
    $song->bpm = $tags['bpm'];
    $song->file_path = \LOE\Base::WEBROOT . $song->file_path;
    $song->cover_path = \LOE\Base::WEBROOT . $song->cover_path;
    $song->update();
  }else{
    $exceptions[] = $song->file_path;
  }
}

print_r($exceptions);
