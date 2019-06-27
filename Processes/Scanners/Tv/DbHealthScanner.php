<?php namespace LOE\Tv;

require_once __DIR__ . '/../../../Factory.php';
require_once __DIR__ . '/../../../Libs/MessageClient/MessageClient.php';


class DbHealthScanner extends \MessageClient{

  const MSGNAME = "LOE_TV_HEALTH_CHECK";
  const MSGSUBJ = "Library of Everything Database Check";

  public $msgResponse;
  public $missing = array();
  protected $_episodes = array();
  protected $_msgTo;
  protected $_fileCount;
  protected $_recordCount;

  public function __construct($msgTo = null,$authToken = null){
    $this->_msgTo = $msgTo;
    $this->_episodes = \LOE\Episode::getAll();
    $this->_recordCount = count($this->_episodes);
    $this->_scan();
    if(is_null($authToken) && !is_null($msgTo)){
      throw new \Exception(self::AUTHERR);
    }elseif(!is_null($authToken) && !is_null($msgTo)){
      try{
        $this->msgResponse = json_decode(self::send($this->_buildMessage(),$authToken));
      }catch(\Exception $e){
        throw new \Exception($e->getMessage());
      }
    }
  }
  protected function _scan(){
    foreach($this->_episodes as $episode){
      if(!$episode->verifyLocation()){
        $this->missing[] = $episode;
      }
    }
    $this->_fileCount = $this->_recordCount - count($this->missing);
    return $this;
  }
  protected function _calculateHealth(){
    return ($this->_fileCount / $this->_recordCount) * 100;
  }
  protected function _buildMessage(){
    return array(
      "to"=>array($this->_msgTo),
      "subject"=>self::MSGSUBJ . ": " . strtoupper(\LOE\Episode::TABLE) . " " . round($this->_calculateHealth(),2) . "%",
      "body"=>$this->_fillMessageBody(),
      "msg_name"=>self::MSGNAME,
      "flag"=>date('Y-m-d'),
      "sent_by"=>"LOE3:" . __FILE__
    );
  }
  protected function _fillMessageBody(){
    $files = array();
    foreach($this->missing as $episode){
      $files[] = $episode->file_path;
    }
    $str = "A database consitency test has been completed for:";
    $str .= \LOE\Episode::DB . "." . \LOE\Episode::TABLE . "<br>";
    if(count($files)){
      sort($files);
      $str .= "The following files could not be located:<br>";
      $str .= "<pre>" . print_r($files,true) . "</pre>";
    }else{
      $str .= "Congratulations! " . \LOE\Episode::TABLE . " is at 100% health!";
    }
    return $str;
  }

}
