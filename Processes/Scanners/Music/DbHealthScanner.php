<?php namespace LOE\Music;

require_once __DIR__ . '/../../Factory.php';

class DbHealthScanner extends \MessageClient{

  const PREFIX = '/var/www/html';
  public $missing = array();
  protected $_songs = array();
  protected $_fileCount;
  protected $_recordCount;

  public function __construct(){
    $this->_songs = Song::getAll();
    $this->_recordCount = count($this->_songs);
    $this->_scan();
    echo "DataBase health is: " . $this->_calculateHealth() . "%\n";
  }
  protected function _scan(){
    foreach($this->_songs as $song){
      if(!file_exists(self::PREFIX . $song->file_path)){
        $this->missing[] = $song;
      }
    }
    $this->_fileCount = $this->_recordCount - count($this->missing);
    return $this;
  }
  protected function _calculateHealth(){
    return ($this->_fileCount / $this->_recordCount) * 100;
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
