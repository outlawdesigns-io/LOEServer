<?php namespace LOE;

require_once __DIR__ . '/Factory.php';

function _getUnevenCounts(){
  $data = array();
  $results = $GLOBALS['db']->database('LOE')
                            ->table('Song s')
                            ->select('s.UID,s.title,s.artist,s.album,s.genre,s.play_count,count(ps.songId) as play_records')
                            ->join('PlayedSong ps','s.UID','=','ps.songId')
                            ->groupBy('ps.songId')
                            ->having('play_records','!=','s.play_count')
                            ->orderBy('play_records')
                            ->get();
  while($row = mysqli_fetch_assoc($results)){
    $data[] = $row;
  }
  return $data;
}
function _getPlays($songUID){
  $data = array();
  $results = $GLOBALS['db']->database('LOE')->table('PlayedSong')->select('UID')->where('songId','=',$songUID)->get();
  while($row = mysqli_fetch_assoc($results)){
    $data[] = Factory::createModel('PlayedSong',$row['UID']);
  }
  return $data;
}
function _getPotentialRequests($query){
  //this is cheating. If you save this, it should implement WebAccessClient instead.
  $data = array();
  $results = $GLOBALS['db']->database('web_access')->table('requests')->select('*')->where('query','like',"'%" . $query . "%'")->get();
  //echo $GLOBALS['db']->query . "\n";
  while($row = mysqli_fetch_assoc($results)){
    $data[] = $row;
  }
  return $data;
}
function _encode($str){
  return preg_replace("/\/LOE/","",preg_replace('/\s/','%20',$str));
}


$debug = true;
$badRecords = _getUnevenCounts();
$dupeDates = array();

foreach($badRecords as $bad){
  $plays = _getPlays($bad['UID']);
  if(count($plays) < $bad['play_count']){
    $song = Factory::createModel('Song',$bad['UID']);
    $requests = _getPotentialRequests(_encode($song->file_path));
    print_r($song);
    print_r($requests);
    continue;
    foreach($requests as $request){
      if(!Music\Played::recordExists($song->UID,$request['requestDate'])){
        if($debug){
          print_r($song);
          print_r($request);
        }else{
          $played = Factory::createModel('PlayedSong');
          $played->songId = $song->UID;
          $played->playDate = $request['requestDate'];
          $played->ipAddress = $request['ip_address'];
          $played->create();
        }
      }else{
        if(!isset($dupeDates[$song->UID])){
          $dupeDates[$song->UID] = array();
        }
        $dupeDates[$song->UID][] = $request['requestDate'];
      }
    }
  }
}
if($debug){
  //there are web_access.requests representing multiple plays at the same time
  print_r($dupeDates);
}
