<?php namespace LOE\Music;

require_once __DIR__ . '/../../../Factory.php';
require_once __DIR__ . '/../../../Libs/WebAccessClient/WebAccessClient.php';

class PlayCount{

  const SPACEPATT = "/%20/";

  protected $_webClient;
  public $exceptions = array();

  public function __construct($username,$password){
    try{
      $this->_webClient = new \WebAccessClient(\WebAccessClient::authenticate($username,$password)->token);
      $this->_updateCounts();
    }catch(\Exception $e){
      throw new \Exception($e->getMessage());
    }
  }
  protected function _updateCounts(){
    $songCounts = $this->_webClient->getLoeSongCounts();
    foreach($songCounts as $obj){
      $song = \LOE\Factory::search(\LOE\Song::TABLE,'file_path',$this->_buildPath($obj->query));
      if(!count($song)){
        $this->exceptions[] = $this->_buildPath($obj->query);
        continue;
      }else{
        $song = $song[0];
      }
      $song->file_path = \LOE\Base::WEBROOT . $song->file_path;
      $song->cover_path = \LOE\Base::WEBROOT . $song->cover_path;
      $song->play_count = $obj->listens;
      $song->update();
    }
    return $this;
  }
  protected function _buildPath($query){
    return \LOE\Base::WEBROOT . "/LOE" . preg_replace(self::SPACEPATT," ",$query);
  }
}
