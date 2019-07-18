<?php namespace LOE\Comic;

require_once __DIR__ . "/../../Scanners/Comic/FsHealthScanner.php";

class AutoInsert{

  const YEARPATT = '/\(([0-9]{4})\)/';
  const SERIESPATT = '/.*(?<=\/)(.*?)(?=\()/';

  protected $scanner;
  protected $series = array();

  public function __construct(){
    $this->scanner = FsHealthScanner();
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
      $issueCount = count(scandir(dirname($file))) - 2;
      $newSeries = new stdClass();
      $newSeries->series = $seriesTitle;
      $newSeries->year = $year;
      $newSeries->issues = $issueCount;
      if($this->_isNewSeries($seriesTitle)){
        $series[] = $newSeries;
      }else{
        continue;
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
}
