<?php namespace LOE\Music;

require_once __DIR__ . "/../../Scanners/FsScanner.php";


class AutoCovers extends \LOE\FsScanner{

    /*RECURSIVE SCAN OF ROOT MUSIC DIR TO IDENTIFY ALBUM DIRS WITH NO 'cover.jpg'
    1) IDENTIFY DIRS
    2) ATTEMPT TO AUTO FIX DIRS WITH IMAGE FILES MATCHING A KNOWN FORMAT
    3) RETURN USER UNFIXED DIRS WITH ANY POSSIBLE IMAGES

    NOTE IS AUTO FIX ATTEMPTS TO FIX A FILE AND FAILS IT WILL THROW AN EXCEPTION CONTAINING THE DIR IN QUESTION
    */

    const ROOTDIR = '/LOE/Music';
    const OPENPATTERN = '/\(/';
    const CLOSEPATTERN = '/\)/';

    public $possibleCovers = array();
    public $missing = array();
    public $fixedDirs = array();
    public $autoFixCount;
    protected $_hasCover = array();
    public static $altNames = array('00-cover.jpg','Cover.jpg');

    public function __construct($attempt = false){
        $this->autoFixCount = 0;
        $this->_scanForever(\LOE\LoeBase::WEBROOT . self::ROOTDIR)
             ->_prunePossible();
        if($attempt){
          $this->_autoFix();
        }
    }
    protected function _interpretFile($absolutePath){
      $fileInfo = pathinfo($absolutePath);
      $dir = $fileInfo['dirname'];
      if($fileInfo["basename"] == "cover.jpg" && !in_array($dir,$this->_hasCover)){
        $this->_hasCover[] = $dir;
        $this->_pruneMissing($dir);
      }elseif($fileInfo['extension'] == 'jpg'){
        $this->possibleCovers[] = $absolutePath;
      }
      if(!in_array($dir,$this->_hasCover) && !in_array($dir,$this->missing) && preg_match(self::OPENPATTERN,$dir)){
        $this->missing[] = $dir;
      }
      return $this;
    }
    protected function _pruneMissing($dir){
      if(!$index = array_search($dir,$this->missing)){
        return false;
      }
      unset($this->missing[$index]);
      $this->missing = array_values($this->missing);
      return $this;
    }
    protected function _prunePossible(){
      foreach($this->possibleCovers as $possible){
        if(!in_array(pathinfo($possible)['dirname'],$this->missing)){
          unset($this->possibleCovers[array_search($possible,$this->possibleCovers)]);
        }
      }
      $this->possibleCovers = array_values($this->possibleCovers);
      return $this;
    }
    protected function _autoFix(){
      foreach($this->possibleCovers as $possible){
        $pathInfo = pathinfo($possible);
        if(in_array($pathInfo['dirname'],$this->missing) && !in_array($pathInfo['dirname'],$this->fixedDirs) && in_array($pathInfo['basename'],self::$altNames) && copy($possible,$pathInfo['dirname'] . "/cover.jpg")){
          $this->fixedDirs[] = $pathInfo['dirname'];
          $this->autoFixCount++;
          $this->_unlink($possible);
        }
      }
      return $this;
    }
}
