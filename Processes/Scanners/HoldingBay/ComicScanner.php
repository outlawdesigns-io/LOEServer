<?php namespace LOE;

require_once __DIR__ . "/../../../Libs/ComicVine/ComicVine.php";
require_once __DIR__ . "/../../../Libs/Archive/Archive.php";
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
    $extension = pathinfo($absolutePath)['extension'];
    if(in_array($extension,self::$acceptedFileTypes)){
      $this->comics[] = $absolutePath;
    }elseif(in_array($extension,\Archive::$archiveTypes)){
      //todo extract archive
    }
    return $this;
  }


}
