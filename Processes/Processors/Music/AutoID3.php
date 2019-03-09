<?php namespace LOE\Music;

require_once __DIR__ . '/../../../Factory.php';
require_once __DIR__ . "/../../Scanners/FsScanner.php";

class AutoID3 extends \LOE\FsScanner{

  const ROOTDIR = '/LOE/Music';

  public function __construct(){
    $this->_scanForever(\LOE\LoeBase::WEBROOT . self::ROOTDIR);
  }
  protected function _interpretFile($absolutePath){
    return $this;
  }
}
