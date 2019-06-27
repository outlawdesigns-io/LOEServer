<?php namespace LOE\Music;

require_once __DIR__ . '/../../../Factory.php';
require_once __DIR__ . '/../../../Libs/MessageClient/MessageClient.php';

//todo check for outreach

class DbHealthScanner extends \MessageClient{

  const MSGNAME = "LOE_MUSIC_HEALTH_CHECK";
  const MSGSUBJ = "Library of Everything Database Check";

  public $msgResponse;
  public $missing = array();
  protected $_songs = array();
  protected $_msgTo;
  protected $_fileCount;
  protected $_recordCount;

  public function __construct($msgTo = null,$authToken = null){
    $this->_msgTo = $msgTo;
    $this->_songs = \LOE\Song::getAll();
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
    foreach($this->_songs as $song){
      if(!$song->verifyLocation()){
        $this->missing[] = $song;
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
      "subject"=>self::MSGSUBJ . ": " . strtoupper(\LOE\Song::TABLE) . " " . round($this->_calculateHealth(),2) . "%",
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
    $str = "A database consitency test has been completed for:";
    $str .= \LOE\Song::DB . "." . \LOE\Song::TABLE . "<br>";
    if(count($files)){
      sort($files);
      $str .= "The following files could not be located:<br>";
      $str .= "<pre>" . print_r($files,true) . "</pre>";
    }else{
      $str .= "Congratulations! " . \LOE\Song::TABLE . " is at 100% health!";
    }
    return $str;
  }

}

// $files = array();
// $albums = array();
// $outreach = _getOutReach();
// $c = new CrashedSongs();
// foreach($c->songs as $song){
//     $files[] = $song->file_path;
//     if(!in_array($song->album,$albums)){
//         $albums[] = $song->album;
//     }
// }
// foreach($albums as $album){
//     foreach($outreach as $o){
//         $pattern = "/" . $album . "/";
//         if(preg_match($pattern,$o["title"])){
//             echo $album . " MATCHED: " . $o["title"] . " " . $o["published"] . "\n";
//         }
//     }
// }
// sort($files);
// print_r($files);
// function _getOutReach(){
//     $data = array();
//     $results = $GLOBALS['db']->database('outreach')->table('article_tracking')->select('*')->get();
//     while($row = mysqli_fetch_assoc($results)){
//         $data[] = $row;
//     }
//     return $data;
// }
