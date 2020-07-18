<?php namespace LOE;

require_once __DIR__ . '/Factory.php';

$models = Music\Played::getAll();

$i = 0;
foreach($models as $played){
  $users = _getUserLabel($played->ipAddress);
  if(count($users)){
    $ids = _getUserId($users[0]);
    $played->userId = $ids[0];
    $played->update();
  }
}

function _getUserLabel($ip){
  $data = array();
  $results = $GLOBALS['db']->database('users')->table('user_locations')->select('user')->where('ip','=',"'" . $ip . "'")->get();
  while($row = mysqli_fetch_assoc($results)){
    $data[] = $row['user'];
  }
  return $data;
}

function _getUserId($userLabel){
  $data = array();
  $results = $GLOBALS['db']->database('users')->table('users')->select('UID')->where('username','=',"'" . $userLabel . "'")->get();
  while($row = mysqli_fetch_assoc($results)){
    $data[] = $row['UID'];
  }
  return $data;
}
