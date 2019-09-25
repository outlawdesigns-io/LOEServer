<?php namespace LOE\Music;

//todo how to clean up files and dirs???

require_once __DIR__ . '/../../../Factory.php';
require_once __DIR__ . '/AutoCovers.php';

class HoldingBayCleaner{

  const NONASCIIPATT = '/[^\x00-\x7F]/';
  const BADFILEPATT = '/[\:"*?<>|]/';
  const PUNCTPATT = "/['!~`*^%$#@+,]/";

  public $cleanedFiles;
  public $songs = array();
  public $images = array();
  public $extraFiles = array();

  protected $_scanner;
  protected $_sourceDirs = array();

  public function __construct(){
    $this->cleanedFiles = 0;
    $this->_scanner = \LOE\Factory::createScanner(\LOE\Song::TABLE);
    $this->_cleanSongs()->_cleanImages()->_cleanUp();
  }
  protected function _cleanSongs(){
    foreach($this->_scanner->songs as $song){
      if(!self::isCleanPath($song->file_path)){
        $source = \LOE\LoeBase::WEBROOT . $song->file_path;
        $song->file_path = \LOE\LoeBase::WEBROOT . self::buildCleanPath($song->file_path);
        $this->_verifyPath(dirname($song->file_path));
        if(!rename($source,$song->file_path)){
          throw new \Exception(error_get_last()['message']);
        }
        $this->cleanedFiles++;
        $this->songs[] = $song;
        $this->_sourceDirs[] = dirname($source);
      }
    }
    return $this;
  }
  protected function _cleanImages(){
    foreach($this->_scanner->possibleCovers as $image){
      if(!self::isCleanPath($image)){
        $source = \LOE\LoeBase::WEBROOT . $image;
        $destination = \LOE\LoeBase::WEBROOT . self::buildCleanPath($image);
        $this->_verifyPath(dirname($destination));
        if(!rename($source,$destination)){
          throw new \Exception(error_get_last()['message']);
        }
        $this->cleanedFiles++;
        $this->images[] = $destination;
        $this->_sourceDirs[] = dirname($source);
      }
    }
    return $this;
  }
  protected function _cleanExtraFiles(){
    foreach($this->_scanner->extraFiles as $file){
      if(!self::isCleanPath($file)){
        $source = \LOE\LoeBase::WEBROOT . $file;
        $destination = \LOE\LoeBase::WEBROOT . self::buildCleanPath($file);
        $this->_verifyPath(dirname($destination));
        if(!rename($source,$destination)){
          throw new \Exception(error_get_last()['message']);
        }
        $this->cleanedFiles++;
        $this->extraFiles[] = $destination;
        $this->_sourceDirs[] = dirname($source);
      }
    }
  }
  protected function _cleanUp(){
    foreach($this->_sourceDirs as $dir){
      if($this->_scanner->isDirEmpty($dir)){
        $this->_scanner->cleanUp($dir);
      }
    }
    return $this;
  }
  protected function _verifyPath($dir){
    $pieces = explode(DIRECTORY_SEPARATOR,$dir);
    $pathStr = "";
    for($i = 0; $i < count($pieces); $i++){
      if(!empty($pieces[$i]) && !is_null($pieces[$i])){
        $pathStr .= DIRECTORY_SEPARATOR . $pieces[$i];
        if(!is_dir($pathStr) && !mkdir($pathStr)){
          throw new \Exception(error_get_last()['message']);
        }
      }
    }
    return $this;
  }
  public static function buildCleanPath($absolutePath){
    $absolutePath = preg_replace(self::NONASCIIPATT,"",$absolutePath);
    $absolutePath = preg_replace(self::BADFILEPATT,"",$absolutePath);
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
