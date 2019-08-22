<?php namespace \LOE\HoldingBay;

require_once __DIR__ . "/../../../Libs/Archive/Archive.php";
require_once __DIR__ . '/../../Scanners/FsScanner.php';

class ArchiveExtractor extends FsScanner{

  public $exceptions = array();
  protected $_rootDir;
  protected $_archives = array();

  public function __construct($rootDir){
    $this->_rootDir = $rootDir;
    $this->_scanForever($this->_rootDir)->_extract();
  }
  protected function _interpretFile($absolutePath){
    if(in_array(pathinfo($result)['extension'],Archive::$archiveTypes)){
      $this->_archives[] = $absolutePath;
    }
  }
  protected function _extract(){
    foreach($this->_archives as $archive){
      try{
        \Archive::extract($archive,$this->_rootDir);
        $this->_unlink($archive);
      }catch(\Exception $e){
        $this->exceptions[] = $e->getMessage();
      }
    }
    return $this;
  }
}
