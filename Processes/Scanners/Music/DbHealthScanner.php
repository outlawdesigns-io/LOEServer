<?php namespace LOE\Music;

require_once __DIR__ . '/../../../Factory.php';
require_once __DIR__ . '/../../../Libs/MessageClient/MessageClient.php';

class DbHealthScanner extends \MessageClient{

  const MSGNAME = "LOE_MUSIC_HEALTH_CHECK";
  const MSGSUBJ = "Library of Everything Database Check";
  const ASCIPATTERN = '/[^[:ascii:]]/';
  const USERNAME = 'test';
  const PASSWORD = 'test';

  public $missing = array();
  protected $_songs = array();
  protected $_msgTo;
  protected $_fileCount;
  protected $_recordCount;

  public function __construct($msgTo){
    $token = self::authenticate(self::USERNAME,self::PASSWORD)->token;
    $this->_msgTo = $msgTo;
    $this->_songs = \LOE\Song::getAll();
    $this->_recordCount = count($this->_songs);
    $this->_scan();
    try{
      print_r(json_decode(self::send($this->_buildMessage(),$token)));
    }catch(\Exception $e){
      echo $e->getMessage();
    }
  }
  protected function _scan(){
    foreach($this->_songs as $song){
      if(!file_exists(\LOE\LoeBase::WEBROOT . $song->file_path)){
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
      "subject"=>self::MSGSUBJ . " " . round($this->_calculateHealth(),2) . "%",
      "body"=>$this->_fillMessageBody(),
      "msg_name"=>self::MSGNAME,
      "flag"=>date('Y-m'),
      "sent_by"=>"LOE3:" . __FILE__
    );
  }
  protected function _fillMessageBody(){
    $files = array();
    foreach($this->missing as $song){
      if(!preg_match(self::ASCIPATTERN,$song->file_path)){
        $files[] = $song->file_path;
      }
    }
    sort($files);
    $str = "A database consitency test has been completed for:<br>";
    $str .= \LOE\Song::DB . "." . \LOE\Song::TABLE . "<br>";
    $str .= "The following files could not be located:<br>";
    $str .= print_r($files,true);
    // $str .= "<table>";
    // for($i = 0; $i < count($files); $i++){
    //   $str .= "<tr><td>" . $i . "</td><td>" . $files[$i] . "</td></tr>";
    // }
    // $str .= "</table>";
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
