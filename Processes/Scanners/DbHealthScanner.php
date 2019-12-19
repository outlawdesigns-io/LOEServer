<?php namespace LOE;

require_once __DIR__ . '/../../Factory.php';
require_once __DIR__ . '/../../Libs/MessageClient/MessageClient.php';


class DbHealthScanner extends \MessageClient{

  const MSGSUBJ = "Library of Everything Database Check";

  public $msgResponse;
  public $recordCount;
  public $missing = array();
  protected $_objects = array();
  protected $_msgTo;
  protected $_fileCount;
  protected $_recordCount;
  protected $_model;

  public function __construct($model,$msgTo = null,$authToken = null){
    $this->_model = $model;
    $this->_msgTo = $msgTo;
    $this->_buildObjects()->_scan();
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
  protected function _buildObjects(){
    $className = $this->_model->namespace . $this->_model->label;
    $this->_objects = $className::getAll();
    $this->_recordCount = count($this->_objects);
    $this->recordCount = $this->_recordCount;
    return $this;
  }
  protected function _buildMessageName(){
    return 'LOE_' . strtoupper($this->_model->label) . '_DB_CHECK';
  }
  protected function _scan(){
    foreach($this->_objects as $object){
      if(!$object->verifyLocation()){
        $this->missing[] = $object;
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
      "subject"=>self::MSGSUBJ . ": " . strtoupper(\LOE\Movie::TABLE) . " " . round($this->_calculateHealth(),2) . "%",
      "body"=>$this->_fillMessageBody(),
      "msg_name"=>$this->_buildMessageName(),
      "flag"=>date('Y-m-d'),
      "sent_by"=>"LOE3:" . __FILE__
    );
  }
  protected function _fillMessageBody(){
    $className = $this->_model->namespace . $this->_model->label;
    $files = array();
    foreach($this->missing as $song){
      $files[] = $song->file_path;
    }
    $str = "A database consitency test has been completed for:";
    $str .= $className::DB . "." . $className::TABLE . "<br>";
    if(count($files)){
      sort($files);
      $str .= "The following files could not be located:<br>";
      $str .= "<pre>" . print_r($files,true) . "</pre>";
    }else{
      $str .= "Congratulations! " . $className::TABLE . " is at 100% health!";
    }
    return $str;
  }

}
