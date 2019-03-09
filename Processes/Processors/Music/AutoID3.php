<?php namespace LOE\Music;

require_once __DIR__ . '/../../../Factory.php';
require_once __DIR__ . '/../../../Libs/MessageClient/MessageClient.php';

class AutoID3 extends \MessageClient{

  const ROOTDIR = '/LOE/Music';

  protected $_unreadable = array();

  public function __construct(){
    $this->_scan();
  }
  protected function _scan(){
    $songs = \LOE\Song::getAll();
    foreach($songs as $song){
      if($song->verifyLocation()){
        try{
          $data = $song->validateTags();
          print_r($data);
        }catch(\Exception $e){
          $this->_unreadable[] = $song->file_path;
        }
      }
    }
    return $this;
  }
}
