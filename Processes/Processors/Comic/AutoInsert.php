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
  protected static $testSeries = array(
    "New Mutants V3"
  );

  protected $scanner;
  protected $series = array();

  public function __construct(){
    $this->scanner = new FsHealthScanner();
    $this->_parse()->_buildTest();
    //print_r($this->series);
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
        $newSeries->files[] = $file;
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
  protected function _buildTest(){
    foreach($this->series as $series){
      if(in_array($series->series,self::$testSeries)){
        $results = \ComicVine::search($series->series);
        foreach($results->result->volume as $possibleVolume){
          $startYear = (int)$possibleVolume->start_year;
          if($startYear == $series->year){
            $seriesDescription = $possibleVolume->description;
            $volume = \ComicVine::followURI($possibleVolume->api_detail_url);
            $issues = $volume->results->issues->issue;
            $publisher = (string)$volume->results->publisher->name;
            foreach($issues as $issue){
              if(in_array((int)$issueDetails->results->issue_number,$series->issues)){
                $issueDetails  = \ComicVine::followURI($issue->api_detail_url);
                $comic = new \LOE\Comic();
                $comic->issue_number = (int)$issueDetails->results->issue_number;
                $comic->issue_title = (string)$issueDetails->results->name;
                $comic->$issue_cover_date = (string)$issueDetails->results->cover_date;
                $comic->series_title = (string)$issueDetails->results->volume->name;
                $comic->series_start_year = (string)$startYear;
                $comic->series_end_year = "";
                $comic->story_arc = (string)$issueDetails->results->story_arc_credits->story_arc->name;
                $comic->issue_description = strip_tags((string)$issueDetails->results->description);
                $comic->series_description = strip_tags((string)$seriesDescription);
                $comic->issue_type = "";
                $comic->publisher = $publisher;
                print_r($comic);
              }
            }
          }
        }
      }
    }
    return $this;
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
