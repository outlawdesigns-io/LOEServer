<?php namespace LOE\Comic;

require_once __DIR__ . "/../../Scanners/Comic/FsHealthScanner.php";
require_once __DIR__ . "/../../../Libs/ComicVine/ComicVine.php";

class AutoInsert{

  const YEARPATT = '/\(([0-9]{4})\)/';
  const SERIESPATT = '/.*(?<=\/)(.*?)(?=\()/';

  public static $issueTypes = array(
    "On-Going",
    "One-Shot",
    "Limited Series"
  );

  protected $scanner;
  protected $series = array();

  public function __construct(){
    $this->scanner = new FsHealthScanner();
    $this->_parse();
    print_r($this->series);
  }
  protected function _parse(){
    foreach($this->scanner->missing as $file){
      if(preg_match(self::YEARPATT,$file,$matches)){
        $year = $matches[1];
      }else{
        continue;
      }
      if(preg_match(self::SERIESPATT,$file,$matches)){
        $seriesTitle = trim($matches[1]);
      }
      $newSeries = new \stdClass();
      $newSeries->series = $seriesTitle;
      $newSeries->year = $year;
      $newSeries->issues = $this->_parseIssues($file);
      $newSeries->issueCount = count($newSeries->issues);
      $newSeries->files = array();
      if($this->_isNewSeries($seriesTitle) && $newSeries->issueCount){
        $this->series[] = $newSeries;
      }else{
        $this->_addIssue($seriesTitle,$file);
      }
    }
    return $this;
  }
  protected function _build(){
    foreach($this->series as $series){
      $searchResults = \ComicVine::search($series->series);
      foreach($results->results->volume as $possibleVolume){
        $startYear = (int)$possibleVolume->start_year;
        if($startYear == $series->year){
          $seriesDescription = $possibleVolume->description;
          $volume = \ComicVine::followURI($possibleVolume->api_detail_url);
          $issues = $volume->results->issues->issue;
          $publisher = (string)$volume->results->publisher->name;
          foreach($issues as $issue){}
        }
      }
    }
  }
  protected function _isNewSeries($series){
    foreach($this->series as $existing){
      if($series == $existing->series){
        return false;
      }
    }
    return true;
  }
  protected function _addIssue($series,$path){
    for($i = 0; $i < count($this->series); $i++){
      if($series == $this->series[$i]->series && !in_array($path,$this->series[$i]->files)){
        $this->series[$i]->files[] = $path;
      }
    }
    return $this;
  }
  protected function _parseIssues($path){
    $issues = array();
    $results = scandir(dirname($path));
    foreach($results as $file){
      if(!\LOE\FsScanner::isDirShortcut($file) && (int)pathinfo($file)['filename']){
        $issues[] = (int)pathinfo($file)['filename'];
      }
    }
    sort($issues);
    return $issues;
  }
}
