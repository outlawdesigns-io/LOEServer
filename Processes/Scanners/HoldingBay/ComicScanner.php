<?php namespace LOE;

require_once __DIR__ . '/../FsScanner.php';

class ComicScanner extends FsScanner{

  const ROOTDIR = '/LOE/holding_bay/comics';

  public static $acceptedFileTypes = array(
    "cbr",
    "cbz"
  );
  public $comics = array();

  public function __construct(){
    $this->_scanForever(\LOE\LoeBase::WEBROOT . self::ROOTDIR);
  }
  protected function _interpretFile($absolutePath){
    if(in_array(pathinfo($absolutePath)['extension'],self::$acceptedFileTypes)){
      $this->comics[] = $absolutePath;
    }
    return $this;
  }


}
