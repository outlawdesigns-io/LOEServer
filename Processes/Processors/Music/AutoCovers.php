<?php namespace LOE\Music;

require_once __DIR__ . "/../../Scanners/FsScanner.php";


class AutoCovers extends \LOE\FsScanner{

    const ROOTDIR = '/LOE/Music';
    const OPENPATTERN = '/\(/';
    const CLOSEPATTERN = '/\)/';
    const MSGNAME = "LOE_MUSIC_AUTO_COVER";
    const MSGSUBJ = "Library of Everything Auto Covers";

    public $possibleCovers = array();
    public $missing = array();
    public $fixedDirs = array();
    public $msgResponse;
    public $autoFixCount = 0;
    public $attempted = false;
    protected $_msgTo;
    protected $_hasCover = array();
    public static $altNames = array(
      '00-cover.jpg',
      'Cover.jpg',
      'Front.jpg',
      'front.jpg'
    );
    public static $altPatterns = array(
      "/AlbumArt_.*?_Large\.jpg/",
      "/00-.*?-cover/",
      "/00-.*?-front/"
    );

    public function __construct($msgTo = null,$authToken = null){
        $this->_scanForever(\LOE\Base::WEBROOT . self::ROOTDIR)
             ->_prunePossible();
        if(is_null($authToken) && !is_null($msgTo)){
          throw new \Exception(self::AUTHERR);
        }elseif(!is_null($authToken) && !is_null($msgTo)){
          $this->_msgTo = $msgTo;
          try{
            $this->_autoFix();
            $this->msgResponse = json_decode(self::send($this->_buildMessage(),$authToken));
          }catch(\Exception $e){
            throw new \Exception($e->getMessage());
          }
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
      $this->attempted = true;
      foreach($this->possibleCovers as $possible){
        $pathInfo = pathinfo($possible);
        if(in_array($pathInfo['dirname'],$this->missing) && !in_array($pathInfo['dirname'],$this->fixedDirs)){
          //todo perhaps to reveserse this (isAltName || isAltMatch) && !copy{throw exception}else{ficedDirs;autoCount++;_unlink}
          if(($this->isAltName($pathInfo['basename']) || $this->isAltMatch($pathInfo['basename'])) && copy($possible,$pathInfo['dirname'] . "/cover.jpg")){
            $this->fixedDirs[] = $pathInfo['dirname'];
            $this->autoFixCount++;
            $this->_unlink($possible);
          }
        }
      }
      return $this;
    }
    protected function _calculateHealth(){
      return (count($this->_hasCover) / (count($this->missing) + count($this->_hasCover))) * 100;
    }
    protected function _buildMessage(){
      return array(
        "to"=>array($this->_msgTo),
        "subject"=>self::MSGSUBJ . " " . round($this->_calculateHealth(),2) . "%",
        "msg_name"=>self::MSGNAME,
        "body"=>$this->_fillMessageBody(),
        "flag"=>date('Y-m-d'),
        "sent_by"=>"LOE3:" . __FILE__
      );
    }
    protected function _fillMessageBody(){
      $str = "An AutoCovers attempt has been made for " . self::ROOTDIR . "<br>";
      $str .= "The following directories were determined to be missing cover files:<br>";
      $str .= "<pre>" . preg_replace("/\'/","",print_r($this->missing,true)) . "</pre><br><br>";
      if($this->attempted){
        $str .= "The following directories were automatically fixed:<br>";
        $str .= "<pre>" . preg_replace("/\'/","",print_r($this->fixedDirs,true)) . "</pre><br>";
      }else{
        $str .= "No correction attempt has been made.<br>";
      }
      return $str;
    }
    public function autoFix(){
      return $this->_autoFix();
    }
    public static function isAltName($filename){
      return in_array($filename,self::$altNames);
    }
    public static function isAltMatch($filename){
      foreach(self::$altPatterns as $pattern){
        if(preg_match($pattern,$filename)){
          return true;
        }
      }
      return false;
    }
}
