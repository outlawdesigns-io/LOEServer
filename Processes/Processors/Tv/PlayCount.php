<?php namespace LOE\Tv;

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
    $episodeCounts = $this->_webClient->getLoeEpisodeCounts();
    foreach($episodeCounts as $obj){
      $episode = \LOE\Factory::search(Movie::TABLE,'file_path',$this->_buildPath($obj->query));
      if(!count($episode)){
        $this->exceptions[] = $this->_buildPath($obj->query);
        continue;
      }else{
        $episode = $episode[0];
      }
      $episode->file_path = \LOE\Base::WEBROOT . $episode->file_path;
      $episode->cover_path = \LOE\Base::WEBROOT . $episode->cover_path;
      $episode->play_count = $obj->downloads;
      $episode->update();
    }
    return $this;
  }
  protected function _buildPath($query){
    return \LOE\Base::WEBROOT . "/LOE" . preg_replace(self::SPACEPATT," ",$query);
  }
}
