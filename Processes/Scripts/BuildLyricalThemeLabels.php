<?php

require_once __DIR__ . '/Factory.php';


function _getLabels(){
  $data = array();
  $results = $GLOBALS['db']->database('MetalArchives')->table('Artist')->select('lyricalThemes')->get();
  while($row = mysqli_fetch_assoc($results)){
    $data[] = $row['lyricalThemes'];
  }
  return $data;
}

$data = _getLabels();
$labels = array();
foreach($data as $row){
  $pieces = explode(',',$row);
  foreach($pieces as $piece){
    $piece = preg_replace("/\(.*?\)/","",$piece);
    $piece = trim(strtolower($piece));
    if(!array_key_exists($piece,$labels)){
      $labels[$piece] = 1;
    }else{
      $labels[$piece]++;
    }
  }
}
// arsort($labels);
// print_r($labels);

foreach($labels as $label=>$count){
  if($count > 1){
    echo $label . ",";
  }
}
