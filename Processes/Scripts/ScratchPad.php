<?php

/*$lines = file('/var/www/html/LOE/Video/Movies/21/movie.sub');
print_r($lines);
exit;*/


require_once __DIR__ . '/Factory.php';

/*$artist = $argv[1];
$update = $argv[2];
$songs = \LOE\Factory::search('Song','artist',$artist);
$fieldToUpdate = "artist_country";
foreach($songs as $song){
  $song->$fieldToUpdate = $update;
  $song->update();
}
exit;*/

require_once __DIR__ . '/Processes/Processors/Music/HoldingBayCleaner.php';

try{
  $p = new \LOE\Music\HoldingBayCleaner();
  $scanner = \LOE\Factory::createHoldingBayScanner(\LOE\Music\Song::TABLE);
  $data = $scanner->artists;
  $data['images'] = $scanner->possibleCovers;
  print_r($data);
}catch(\Exception $e){
  echo $e->getMessage() . "\n";
}

exit;



/*require_once __DIR__ . '/Factory.php';

$webClient = new \WebAccessClient(\WebAccessClient::authenticate('outlaw','admin')->token);
$records = \LOE\PlayedSong::getAll();
foreach($records as $record){
  if(is_null($record->ipAddress)){
    $results = $GLOBALS['db']
      ->database("web_access")
      ->table("requests")
      ->select("*")
      ->where("requestDate","=","'" . $record->playDate . "'")
      ->andWhere("host","=","'loe.outlawdesigns.io'")
      ->andWhere("port","=",80)
      ->andWhere("responseCode","in","(202,206,304)")
      ->andWhere("query","like","'%.mp3'")
      ->get();
    while($row = mysqli_fetch_assoc($results)){
      $record->ipAddress = $row['ip_address'];
    }
    $record->update();
  }
}*/
