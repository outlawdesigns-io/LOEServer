<?php namespace LOE\Comic;

require_once __DIR__ . '/../HoldingBayScanner.php';
require_once __DIR__ . '/../../../Libs/ComicVine/ComicVine.php';

class HoldingBayScanner extends \LOE\HoldingBayScanner{

  const TITLEPAT = '/(([A-Z])(.*?))\(/';
  const YEARPAT = '/\(([0-9]{4})\)/';
  const ISSUEPAT = '/([0-9]{3})/';

  public $albums = array();
  public $artists = array();
  public $unknownAlbum = array();
  public $unknownArtist = array();

  public function __construct($model){
    parent::__construct($model);
    $this->_buildFromPath();
  }
  protected function _parseIssueName($fileName){
    if(preg_match(self::TITLEPAT,$fileName,$matches)){
      return $matches[1];
    }
    return false;
  }
  protected function _parseIssueYear($fileName){
    if(preg_match(self::YEARPAT,$fileName,$matches)){
      return $matches[1];
    }
    return false;
  }
  protected function _parseIssueNumber($fileName){
    if(preg_match(self::ISSUEPAT,$fileName,$matches)){
      return (int)$matches[0];
    }
    return false;
  }
  protected function _buildFromPath(){
    for($i = 0; $i < count($this->targetModels); $i++){
      $fileName = pathinfo($this->targetModels[$i]->file_path)['filename'];
      $this->targetModels[$i]->UID = $i;
      $this->targetModels[$i]->issue_title = $this->_parseIssueName($fileName);
      $this->targetModels[$i]->issue_number = $this->_parseIssueNumber($this->targetModels[$i]->issue_title);
      $this->targetModels[$i]->issue_cover_date = $this->_parseIssueYear($fileName);
    }
    return $this;
  }
  protected function _appendFromComicVine(){
    foreach($this->targetModels as $comic){
      if(!empty($comic->issue_title) && !empty($comic->issue_cover_date)){
        $results = \ComicVine::search($comic->issue_title);
      }
    }
    return $this;
  }
}

/*
foreach target Model
  parse Title and Year
  Search for Title.
  Loop through results.
  Use Year to confirm correct result.


  public $UID;
  public $issue_number;
  public $issue_title;
  public $issue_cover_date;
  public $series_title;
  public $series_start_year;
  public $series_end_year;
  public $publisher;
  public $story_arc;
  public $issue_description;
  public $series_description;
  public $issue_type;
  public $file_path;

*/
