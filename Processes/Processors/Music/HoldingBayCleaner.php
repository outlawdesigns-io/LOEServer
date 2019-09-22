<?php namespace LOE\Music;

require_once __DIR__ . '/../../../Factory.php';

class HoldingBayCleaner{

  const NONASCIIPATT = '/[^\x00-\x7F]/';
  const BADFILEPATT = '/[\\\/\:"*?<>|]/';
  const PUNCTPATT = "/['!~`*^%$#@+]/";
  const FSENCODE = 'ASCII';


  public $filesCleaned;
  public $songs;

  protected $_scanner;

  public function __construct(){
    $this->filesCleaned = 0;
    $this->_scanner = \LOE\Factory::createScanner(\LOE\Song::TABLE);
    $this->_cleanFiles();
  }
  protected function _cleanFiles(){
    foreach($this->_scanner->songs as $song){
      $updated = false;
      if(!self::isCleanPath($song->file_path)){
        $source = \LOE\LoeBase::WEBROOT . $song->file_path;
        $song->file_path = \LOE\LoeBase::WEBROOT . self::buildCleanPath($song->file_path);
        $updated = true;
      }
      if($updated && !rename($source,$song->file_path)){
        throw new \Exception(error_get_last()['message']);
      }
      $this->filesCleaned++;
      $this->songs[] = $song;
    }
    return $this;
  }
  public static function buildCleanPath($absolutePath){
    $absolutePath = preg_replace(self::NONASCIIPATT,"",$absolutePath);
    //$absolutePath = preg_replace(self::BADFILEPATT,"",$absolutePath);
    $absolutePath = preg_replace(self::PUNCTPATT,"",$absolutePath);
    return $absolutePath;
  }
  public static function isCleanPath($absolutePath){
    if(preg_match(self::NONASCIIPATT,$absolutePath) || preg_match(self::BADFILEPATT,$absolutePath) || preg_match(self::PUNCTPATT,$absolutePath)){
      return false;
    }
    return true;
  }
}
