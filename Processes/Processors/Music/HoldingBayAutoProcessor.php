<?php namespace LOE\Music;

require_once __DIR__ . '/../../../Factory.php';
require_once __DIR__ . '/../../../Libs/MetalArchivesClient/MetalArchivesClient.php';

class HoldingBayAutoProcessor{

  const DEBUG = false;

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
        $this->_preProcess();
      }
      unset($this->_albums[$label]);
    }
  }
  protected function _clean(){
    try{
      $this->_performAutoCover(\LOE\Factory::createHoldingBayCleaner(Song::TABLE));
      $this->_scanner = \LOE\Factory::createHoldingBayScanner(\LOE\Factory::getModel(Song::TABLE));
      $this->_albums = $this->_scanner->albums;
    }catch(\Exception $e){
      throw new \Exception($e->getMessage());
    }
    return $this;
  }
  protected function _performAutoCover($cleaner){
    foreach($cleaner->images as $img){
      if(AutoCovers::isAltName(basename($img)) || AutoCovers::isAltMatch(basename($img))){
        try{
          $this->_moveCoverImage($img);
        }catch(\Exception $e){
          throw new \Exception($e->getMessage());
        }
      }
    }
    return $this;
  }
  protected function _moveCoverImage($absolutePath){
    if(!rename($absolutePath,dirname($absolutePath) . '/cover.jpg')){
      throw new \Exception(error_get_last()['message']);
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
      $exception = array(
        "artist"=>$this->_artistSearchStr,
        "album"=>$this->_albumSearchStr,
        "reason"=>$e->getMessage()
      );
      $this->exceptions[] = $exception;
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
      $exception = array(
        "artist"=>$this->_artistSearchStr,
        "album"=>$this->_albumSearchStr,
        "reason"=>"Too much uncertainty in MA results."
      );
      $this->exceptions[] =  $exception;
    }
    return $this;
  }
  protected function _process(){
    foreach($this->_songs as $song){
      $hbCover = dirname($song->file_path) . '/cover.jpg';
      $finalCover = $song->cover_path;
      if(is_file($hbCover) || is_file($finalCover)){
        try{
          \LOE\Factory::createHoldingBayProcessor('Song',$song);
        }catch(\Exception $e){
          throw new \Exception($e->getMessage());
        }
      }
    }
    return $this;
  }
  protected function _preProcess(){
    self::DEBUG ? print_r($this->_songs):false;
    if(!self::DEBUG){
      $this->_process();
    }elseif(self::DEBUG && (readline("Approve? (y/n)") == 'y')){
      $this->_process();
    }
    $this->_songs = [];
    return $this;
  }
  protected function _parseReleaseType($albumStr,$releaseType){
    if(in_array($releaseType,self::$_releaseTypes)){
      $albumStr .= ' [' . $releaseType . ']';
    }
    return $albumStr;
  }
}
