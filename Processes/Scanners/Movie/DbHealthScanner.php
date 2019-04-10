<?php namespace LOE\Movie;

require_once __DIR__ . '/../../../Factory.php';
require_once __DIR__ . '/../../../Libs/MessageClient/MessageClient.php';


class DbHealthScanner extends \MessageClient{

  const MSGNAME = "LOE_MOVIE_HEALTH_CHECK";
  const MSGSUBJ = "Library of Everything Database Check";

  public $msgResponse;
  public $missing = array();
  protected $_movies = array();
  protected $_msgTo;
  protected $_fileCount;
  protected $_recordCount;

  public function __construct($msgTo = null,$authToken = null){
    $this->_msgTo = $msgTo;
    $this->_movies = \LOE\Movie::getAll();
    $this->_recordCount = count($this->_songs);
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
    foreach($this->_movies as $movie){
      if(!$movie->verifyLocation()){
        $this->missing[] = $movie;
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
      "subject"=>self::MSGSUBJ . " " . round($this->_calculateHealth(),2) . "%",
      "body"=>$this->_fillMessageBody(),
      "msg_name"=>self::MSGNAME,
      "flag"=>date('Y-m-d'),
      "sent_by"=>"LOE3:" . __FILE__
    );
  }
  protected function _fillMessageBody(){
    $files = array();
    foreach($this->missing as $song){
      $files[] = $song->file_path;
    }
    sort($files);
    $str = "A database consitency test has been completed for:";
    $str .= \LOE\Movie::DB . "." . \LOE\Movie::TABLE . "<br>";
    $str .= "The following files could not be located:<br>";
    $str .= "<pre>" . print_r($files,true) . "</pre>";
    return $str;
  }

}
