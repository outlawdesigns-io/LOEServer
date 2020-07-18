<?php namespace LOE;

require_once __DIR__ . '/Factory.php';

/*$songs = Factory::search(Music\Song::TABLE,'file_path',',');
$exceptions = array();

foreach($songs as $song){
  $oldPath = Base::WEBROOT . $song->file_path;
  $newPath = Music\HoldingBayCleaner::buildCleanPath(Base::WEBROOT . $song->file_path);
  if(preg_match("/,/",dirname($oldPath))){
    if(!in_array($oldPath,$exceptions)){
      $exceptions[] = $oldPath;
    }
  }elseif(!rename($oldPath,$newPath)){
    echo "Failure: " . $path . "\n";
    exit;
  }else{
    $song->file_path = $newPath;
    $song->update();
  }
}
print_r($exceptions);*/

function _getIds(){
  $data = array();
  $results = $GLOBALS['db']->database('LOE')->table('Song')->select("UID")->where("file_path","not like","'/var/www/html/%")->get();
  while($row = mysqli_fetch_assoc($results)){
    $data[] = $row['UID'];
  }
  return $data;
}

$ids = _getIds();

foreach($ids as $id){
  $song = Factory::createModel(Music\Song::TABLE,$id);
  $song->file_path = Base::WEBROOT . $song->file_path;
  $song->cover_path = Base::WEBROOT . $song->cover_path;
  $song->update();
}
