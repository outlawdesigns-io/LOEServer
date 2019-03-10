<?php namespace LOE\Music;

require_once __DIR__ . '/../../../Factory.php';
require_once __DIR__ . '/../../../Libs/MessageClient/MessageClient.php';

class AutoID3 extends \MessageClient{

  const ROOTDIR = '/LOE/Music';

  protected $_unreadable = array();

  public function __construct($msgTo = null,$authToken = null){
    $this->_scan();
  }
  protected function _scan(){
    $songs = \LOE\Song::getAll();
    foreach($songs as $song){
      if($song->verifyLocation()){
        try{
          if(count($song->validateTags())){
            $this->_autoFix($song);
          }
        }catch(\Exception $e){
          $this->_unreadable[] = $song->file_path;
        }
      }
    }
    return $this;
  }
  protected function _autoFix($song){
    $id3 = $song->validateTags();
    echo $song->file_path . "\n";
    print_r($id3);
  }
}
