<?php namespace LOE\Comic;

require_once __DIR__ . '/../FsScanner.php';

class FsHealthScanner extends \LOE\FsScanner{

  const ROOTDIR = '/LOE/Comics';
  const MSGNAME = "LOE_COMIC_FS_CHECK";
  const MSGSUBJ = "Library of Everything File System Check";

  public static $acceptedFileTypes = array(
    "cbr",
    "cbz"
  );
  public $missing = array();
  public $files = array();

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
    if(in_array($fileInfo['extension'],self::$acceptedFileTypes)){
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
                ->database(\LOE\Comic::DB)
                ->table(\LOE\Comic::TABLE)
                ->select(\LOE\Comic::PRIMARYKEY)
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
      "subject"=>self::MSGSUBJ . ": " . \LOE\Comic::TABLE . " " . round($this->_calculateHealth(),2) . "%",
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
    $str .= "<pre>" . print_r($this->missing,true) . "</pre>";
    return $str;
  }
}
