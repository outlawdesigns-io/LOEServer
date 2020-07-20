<?php namespace LOE\Music;

require_once __DIR__ . '/../../Scanners/Music/HoldingBayNotification.php';
require_once __DIR__ . '/../../../Libs/MetalArchivesClient/MetalArchivesClient.php';

class HoldingBayAutoProcessor{

  const DEBUG = true;

  protected $_maClient;
  protected $_scanner;
  protected $_albumSearchStr;
  protected $_artistSearchStr;
  protected $_songs = array();
  protected $_albums = array();
  public static $_releaseTypes = array('Demo','EP');
  public $exceptions = array();

  public function __construct(){
    $this->_maClient = new \MetalArchivesClient();
    $this->_clean();
    foreach($this->_albums as $label=>$songs){
      $this->_parseSearchStrings();
      $this->_searchMetalArchives();
      if(count($this->_songs)){
        $this->_process();
      }
      unset($this->_albums[$label]);
    }
  }
  protected function _clean(){
    try{
      \LOE\Factory::createHoldingBayCleaner(Song::TABLE);
      $this->_scanner = \LOE\Factory::createHoldingBayScanner(\LOE\Factory::getModel(Song::TABLE));
      $this->_albums = $this->_scanner->albums;
    }catch(\Exception $e){
      throw new \Exception($e->getMessage());
    }
    return $this;
  }
  protected function _parseSearchStrings(){
    $keys = array_keys($this->_albums);
    $this->_albumSearchStr = $keys[0];
    $this->_artistSearchStr = $this->_albums[$keys[0]][0]->artist;
    return $this;
  }
  protected function _searchMetalArchives(){
    try{
      $album = $this->_maClient->searchAlbum($this->_albumSearchStr,$this->_artistSearchStr);
      $artist = $this->_maClient->searchArtist($this->_artistSearchStr);
    }catch(\Exception $e){
      $this->exceptions[] = $this->_albumSearchStr . " - " . $this->_artistSearchStr;
      return $this;
    }
    if(!is_array($artist) && !is_array($album)){
      foreach($this->_scanner->albums[$this->_albumSearchStr] as $song){
        $song->publisher = $album->recordLabel;
        $song->genre = preg_replace("/\//",".",$artist->genre);
        $song->artist_country = $artist->country;
        $song->cover_path = dirname($song->file_path) . '/cover.jpg';
        $song->track_number = (int)$song->track_number;
        $song->album = $this->_parseReleaseType($song->album,$album->releaseType);
        $this->_songs[] = $song;
      }
    }else{
      $this->exceptions[] = $this->_albumSearchStr . " - " . $this->_artistSearchStr;
      // throw new \Exception('Too much uncertainty. Doing nothing');
    }
    return $this;
  }
  protected function _process(){
    self::DEBUG ? print_r($this->_songs):false;
    if(!self::DEBUG){
      foreach($this->_songs as $song){
        try{
          \LOE\Factory::createHoldingBayProcessor('Song',$song);
        }catch(\Exception $e){
          throw new \Exception($e->getMessage());
        }
      }
    }elseif(self::DEBUG && (readline("Approve? (y/n)") == 'y')){
      foreach($this->_songs as $song){
        try{
          \LOE\Factory::createHoldingBayProcessor('Song',$song);
        }catch(\Exception $e){
          throw new \Exception($e->getMessage());
        }
      }
    }else{
      exit;
    }
    $this->_songs = [];
    return $this;
  }
  protected function _parseReleaseType($albumStr,$releaseType){
    if(in_array($releaseType,self::$_releaseTypes)){
      $albumStr .= '[' . $releaseType . ']';
    }
    return $albumStr;
  }
}
