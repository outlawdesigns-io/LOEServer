<?php namespace LOE;

require_once __DIR__ . '/Factory.php';
require_once __DIR__ . '/Libs/MetalArchivesClient/MetalArchivesClient.php';


function _buildFromPlayed(){
  $data = array();
  $results = $GLOBALS['db']
    ->database('LOE')
    ->table('Song song')
    ->select('distinct song.album,song.artist')
    ->join('LOE.PlayedSong played','song.UID','=','played.songId')
    ->where('song.genre','like',"'%metal%'")
    ->get();
  while($row = mysqli_fetch_assoc($results)){
    $data[] = $row;
  }
  return $data;
}
function _buildFromAll(){
  $data = array();
  $results = $GLOBALS['db']
    ->database('LOE')
    ->table('Song')
    ->select('distinct album,artist')
    ->where('genre','like',"'%metal%'")
    ->get();
  while($row= mysqli_fetch_assoc($results)){
    $data[] = $row;
  }
  return $data;
}
function _trimAlbum($album){
  $pattern = "/\[.*?\]/";
  return trim(preg_replace($pattern,"",$album));
}

// $data = _buildFromAll();
$data = array(
  array('artist'=>'Slayer','album'=>'Reign in Blood'),
  array('artist'=>'Cannibal Corpse','album'=>'Evisceration Plague'),
  array('artist'=>'Aborted','album'=>'Retrogore')
);
$ma = new \MetalArchivesClient();

//185 - 190 will throw "too many connections"
for($i = 0; $i < count($data); $i++){
  try{
    $album = _trimAlbum($data[$i]['album']);
    $broadSearchResults = $ma->searchAlbum($album);
    $results = $ma->searchAlbum($album,$data[$i]['artist']);
    foreach($results->songs as $song){
      try{
        $lyrics = $ma->getLyrics($song->id);
      }catch(\Exception $e){
        echo $song->id . " | " . $e->getMessage() . "\n";
      }
    }
  }catch(\Exception $e){
    echo $i . ". " . $data[$i]['album'] . '/' . $data[$i]['artist'] . ' | ' . $e->getMessage() . "\n";
  }
}
