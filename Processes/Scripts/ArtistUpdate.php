<?php

require_once __DIR__ . '/Libs/MetalArchivesClient/MetalArchivesClient.php';
require_once __DIR__ . '/Factory.php';

$maClient = new \MetalArchivesClient();

$artist = file('/tmp/artists');

foreach($artists as $artist){
  try{
    $results = $maClient->searchArtist($artist);
  }catch(\Exception $e){
    echo $e->getMessage() . "\n";
    continue;
  }
  if(!is_array($results)){
    _update($results->country,$artist);
    //print_r($results);
  }
}



function _update($country,$artist){
  $data = array('artist_country'=>$country);
  $results = $GLOBALS['db']
      ->database('LOE')
      ->table('Song')
      ->update($data)
      ->where("artist","=","'" . $artist . "'")
      ->put();
}
