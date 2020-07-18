<?php namespace LOE;

require_once __DIR__ . '/Factory.php';
require_once __DIR__ . '/Libs/MetalArchivesClient/MetalArchivesClient.php';
require_once __DIR__ . '/Models/Music/Script.php';

function _buildLocalMAData(){
  $data = array();
  $results = $GLOBALS['db']
    ->database('MetalArchives')
    ->table('MetalArchives.Lyrics lyrics')
    ->select('search.artist,search.album,song.title,lyrics.body')
    ->join('MetalArchives.Song song','song.id','=','lyrics.id')
    ->join('MetalArchives.Album album','song.albumId','=','album.id')
    ->join('MetalArchives.AlbumSearch search','album.id','=','search.albumId')
    ->get();
  while($row = mysqli_fetch_assoc($results)){
    $data[] = $row;
  }
  return $data;
}
function _findLOESong($title,$album,$artist){
  $id = null;
  $results = $GLOBALS['db']
    ->database('LOE')
    ->table('Song')
    ->select('UID')
    ->where('title','=',"'" . preg_replace("/'/","''",$title) . "' COLLATE utf8mb4_general_ci")
    ->andWhere('album','=',"'" . preg_replace("/'/","''",$album) . "' COLLATE utf8mb4_general_ci")
    ->andWhere('artist','=',"'" . $artist . "' COLLATE utf8mb4_general_ci")
    ->get();
  if(!mysqli_num_rows($results)){
    return $id;
  }
  while($row = mysqli_fetch_assoc($results)){
    $id = $row['UID'];
  }
  return $id;
}
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
function _createLoeFromMa(){
  $data = _buildLocalMAData();
  $counter = 0;
  foreach($data as $d){
    if(!is_null($id = _findLOESong($d['title'],$d['album'],$d['artist']))){
      $counter++;
      $obj = new Music\Script();
      $obj->songId = $id;
      $obj->body = $d['body'];
      $obj->create();
    }
  }
  echo $counter . " matches\n";
}


$data = _buildFromAll();
$ma = new \MetalArchivesClient();

for($i = 15; $i < 25; $i++){
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
