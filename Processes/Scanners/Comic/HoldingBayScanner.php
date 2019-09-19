<?php namespace LOE\Comic;

require_once __DIR__ . "/../../../Libs/ComicVine/ComicVine.php";
require_once __DIR__ . '/../FsScanner.php';

class HoldingBayScanner extends \LOE\FsScanner{

  const ROOTDIR = '/LOE/holding_bay/comics';
  const YEARPATT = '/\(([0-9]{4})\)/';

  public static $acceptedFileTypes = array(
    "cbr",
    "cbz"
  );
  public $comics = array();
  public $exceptions = array();
  protected $_files = array();

  public function __construct(){
    $this->_scanForever(\LOE\Base::WEBROOT . self::ROOTDIR);
  }
  protected function _interpretFile($absolutePath){
    $extension = pathinfo($absolutePath)['extension'];
    if(in_array($extension,self::$acceptedFileTypes)){
      $this->_files[] = $absolutePath;
    }
    return $this;
  }
  protected function _parseFiles(){
    foreach($this->_files as $file){
      if(!preg_match(self::YEARPATT,$file,$matches)){
        $this->exceptions[] = $file;
      }else{
        $year = $matches[1];
        //todo something
      }
    }
    return $this;
  }


}
