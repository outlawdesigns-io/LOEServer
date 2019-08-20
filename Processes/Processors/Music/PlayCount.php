<?php namespace LOE\Music;

require_once __DIR__ . '/../../../Factory.php';
require_once __DIR__ . '/../../../Libs/WebAccessClient/WebAccessClient.php';

class PlayCount{

  const SPACEPATT = "/%20/";

  protected $_webClient;

  public function __construct($username,$password){
    try{
      $this->_webClient = new WebAccessClient(WebAccessClient::authenticate($username,$password)->token);
      $this->_updateCounts();
    }catch(\Exception $e){
      throw new \Exception($e->getMessage());
    }
  }
  protected function _updateCounts(){
    $songCounts = $this->_webClient->getLoeSongCounts();
    foreach($songCounts as $obj){
      $song = \LOE\Factory::search(\LOE\Song::TABLE,'file_path',$this->_buildPath($obj->query));
      $song->play_count = $obj->listens;
      $song->update();
    }
    return $this;
  }
  protected function _buildPath($query){
    return \LOE\LoeBase::WEBROOT . "/LOE" . preg_replace(self::SPACEPATT," ",$query);
  }
}
