<?php namespace LOE\Music;

require_once __DIR__ . '/../FsScanner.php';
require_once __DIR__ . '/../../../Factory.php';

class FsHealthScanner extends \LOE\FsScanner{

  const ROOTDIR = '/LOE/Music/';
  const MSGNAME = "LOE_MUSIC_FS_CHECK";
  const MSGSUBJ = "Library of Everything File System Check";

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

  protected function _scanForever($dir){
    $results = scandir($dir);
    foreach($results as $result){
      if($result == '.' || $result == '..'){
        continue;
      }else{
        $tester = ($dir == \LOE\LoeBase::WEBROOT . self::ROOTDIR) ? $tester = $dir . $result : $tester = $dir . "/" . $result;
      }
      if(is_file($tester)){
        $this->_interpretFile($tester);
      }elseif(is_dir($tester)){
        $this->_scanForever($tester);
      }else{
        continue;
      }
    }
    return $this;
  }
  protected function _interpretFile($absolutePath){
    $fileInfo = pathinfo($absolutePath);
    if($fileInfo['extension'] == "mp3"){
      $this->files[] = $absolutePath;
    }
    return $this;
  }
  protected function _verifyDatabase(){
    foreach($this->files as $file){
      if(!$this->_recordExists($file)){
        $this->missing[] = $file;
      }
    }
    return $this;
  }
  protected function _recordExists($absolutePath){
    $GLOBALS['db']
                ->database(\LOE\Song::DB)
                ->table(\LOE\Song::TABLE)
                ->select(\LOE\Song::PRIMARYKEY)
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
    return ((count($this->files) - count($this->missing)) / $files) * 100;
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
    $str = "A file system consitency test has been completed for: ";
    $str .= self::ROOTDIR . "<br>";
    $str .= "The following files cannot be accounted for:<br>";
    $str .= print_r($this->missing,true);
    return $str;
  }
}
