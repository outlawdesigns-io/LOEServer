<?php namespace LOE\Music;

require_once __DIR__ . '/../../../Factory.php';
require_once __DIR__ . '/../../../Libs/WebAccessClient/WebAccessClient.php';

class PlayCount{

  protected $_webClient;
  protected $_songCounts = array();

  public function __construct($username,$password){
    $this->_webClient = new WebAccessClient(WebAccessClient::authenticate($username,$password)->token);
    $this->_getCounts();
  }
  protected function _getCounts(){
    $this->_songCounts = $this->_webClient->getLoeSongCounts();
    return $this;
  }
  protected function _updateCounts(){
    foreach($this->_songCounts as $obj){
      $song = \LOE\Factory::search(\LOE\Song::TABLE,'file_path',$obj->query);
    }
  }
}
