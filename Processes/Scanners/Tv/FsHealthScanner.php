<?php namespace LOE\Tv;

require_once __DIR__ . '/../FsScanner.php';
require_once __DIR__ . '/../../../Factory.php';

class FsHealthScanner extends \LOE\FsScanner{

  const ROOTDIR = '/LOE/Video/Tv';
  const MSGNAME = "LOE_TV_FS_CHECK";
  const MSGSUBJ = "Library of Everything File System Check";

  public static $knownExtensions = array("mp4","MP4","avi","AVI","mkv","MKV");

  public $missing = array();
  public $files = array();
  public $msgResponse;
  protected $_msgTo;

  public function __construct($msgTo = null,$authToken = null){
    $this->_scanForever(\LOE\LoeBase::WEBROOT . self::ROOTDIR)
         ->_verifyDatabase();
    if(is_null($authToken) && !is_null($msgTo)){
      throw new \Exception(self::AUTHERR);
    }elseif(!is_null($authToken) && !is_null($msgTo)){
      $this->_msgTo = $msgTo;
      try{
        $this->msgResponse = json_decode(self::send($this->_buildMessage(),$authToken));
      }catch(\Exception $e){
        throw new \Exception($e->getMessage());
      }
    }
  }
  protected function _interpretFile($absolutePath){
    $fileInfo = pathinfo($absolutePath);
    if(in_array($fileInfo['extension'],self::$knownExtensions)){
      $this->files[] = $absolutePath;
    }
    return $this;
  }
  protected function _verifyDatabase(){
    foreach($this->files as $file){
      if(!$this->_recordExists($file)){
        $this->missing[] = preg_replace("/'/","",$file);
      }
    }
    return $this;
  }
  protected function _recordExists($absolutePath){
    $GLOBALS['db']
                ->database(\LOE\Episode::DB)
                ->table(\LOE\Episode::TABLE)
                ->select(\LOE\Episode::PRIMARYKEY)
                ->where("file_path","=","'" . preg_replace("/'/","",$absolutePath) . "'");
    try{
      $results = $GLOBALS['db']->get();
    }catch(\Exception $e){
      echo $e->getMessage() . "\n";
      echo $absolutePath . "\n";
      return false;
    }
    if(!mysqli_num_rows($results)){
      return false;
    }
    return true;
  }
  protected function _calculateHealth(){
    return ((count($this->files) - count($this->missing)) / count($this->files)) * 100;
  }
  protected function _buildMessage(){
    return array(
      "to"=>array($this->_msgTo),
      "subject"=>self::MSGSUBJ . ": " . self::ROOTDIR . " " . round($this->_calculateHealth(),2) . "%",
      "msg_name"=>self::MSGNAME,
      "body"=>$this->_fillMessageBody(),
      "flag"=>date('Y-m-d'),
      "sent_by"=>"LOE3:" . __FILE__
    );
  }
  protected function _fillMessageBody(){
    $str = "A file system consitency test has been completed for: ";
    $str .= self::ROOTDIR . "<br>";
    if(count($this->missing)){
      sort($this->missing);
      $str .= "The following files cannot be accounted for:<br>";
      $str .= "<pre>" . print_r($this->missing,true) . "</pre>";
    }else{
      $str .= "Congratulations! " . self::ROOTDIR . " is at 100% health!";
    }
    return $str;
  }
}
