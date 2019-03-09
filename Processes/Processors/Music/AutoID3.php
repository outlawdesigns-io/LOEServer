<?php namespace LOE\Music;

require_once __DIR__ . '/../../../Factory.php';
require_once __DIR__ . "/../../Scanners/FsScanner.php";

class AutoID3 extends \LOE\FsScanner{

  const ROOTDIR = '/LOE/Music';

  protected $_unreadable = array();

  public function __construct(){
    //$this->_scanForever(\LOE\LoeBase::WEBROOT . self::ROOTDIR);
    $songs = \LOE\Song::getAll();
    foreach($songs as $song){
      try{
        $id3Data = $song->getMp3Tags();
      }catch(\Exception $e){
        $this->_unreadable[] = $song->file_path;
      }
    }
    print_r($this->_unreadable);
  }
  protected function _interpretFile($absolutePath){
    return $this;
  }
}
