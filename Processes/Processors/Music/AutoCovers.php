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
    protected $_hasCover = array();
    public static $altNames = array('00-cover.jpg','Cover.jpg');

    public function __construct($msgTo = null,$authToken = null){
        $this->_scanForever(\LOE\LoeBase::WEBROOT . self::ROOTDIR)
             ->_prunePossible();
        if(is_null($authToken) && !is_null($msgTo)){
          throw new \Exception(self::AUTHERR);
        }elseif(!is_null($authToken) && !is_null($msgTo)){
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
        if(in_array($pathInfo['dirname'],$this->missing) && !in_array($pathInfo['dirname'],$this->fixedDirs) && in_array($pathInfo['basename'],self::$altNames) && copy($possible,$pathInfo['dirname'] . "/cover.jpg")){
          $this->fixedDirs[] = $pathInfo['dirname'];
          $this->autoFixCount++;
          $this->_unlink($possible);
        }
      }
      return $this;
    }
    protected function _buildMessage(){
      return array(
        "to"=>array(),
        "subject"=>self::MSGSUBJ,
        "msg_name"=>self::MSGNAME,
        "body"=>$this->_fillMessageBody(),
        "flag"=>date('Y-m-d'),
        "sent_by"=>"LOE3:" . __FILE__
      );
    }
    protected function _fillMessageBody(){
      $str = "An AutoCovers attempt has been made for " . self::ROOTDIR . "<br>";
      $str .= "The following directories were determined to be missing cover files:<br>";
      $str .= print_r($this->missing,true) . "<br><br>";
      if($this->attempted){
        $str .= "The following directories were automatically fixed:<br>";
        $str .= print_r($this->fixedDirs,true) . "<br>";
      }else{
        $str .= "No correction attempt has been made.<br>";
      }
      return $str;
    }
    public function autoFix(){
      return $this->_autoFix();
    }
}
