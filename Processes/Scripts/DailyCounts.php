<?php

require_once __DIR__ . '/Factory.php';

echo \LOE\Music\Played::dailyAverage() . "\n";
exit;


function _getDates(){
  $data = array();
  $results = $GLOBALS['db']
      ->database("LOE")
      ->table("PlayedSong")
      ->select("DISTINCT CAST(playDate as DATE) as playedDate")
      ->orderBy("playDate desc")
      ->get();
  while($row = mysqli_fetch_assoc($results)){
    $data[] = $row['playedDate'];
  }
  return $data;
}
function _getCounts($key,$date = null){
  $data = array();
  $GLOBALS['db']
      ->database("LOE")
      ->table("music music")
      ->select("count(played.UID) as count,music." . $key)
      ->join("LOE.PlayedSong played","played.songId","=","music.UID");
  if(!is_null($date)){
    $GLOBALS['db']->where("CAST(played.playDate as DATE)","=","'" . $date . "'");
  }
  $results = $GLOBALS['db']->groupBy("music." . $key)->orderBy("count desc")->get();
  while($row = mysqli_fetch_assoc($results)){
    $data[] = $row;
  }
  return $data;
}

//print_r(_getCounts("genre"));
//exit;
$dates = _getDates();
$values = array();
$targetGenre = 'Rap';
foreach($dates as $date){
  $values[$date] = _getCounts("artist",$date);
}
print_r($values['2019-11-17']);
