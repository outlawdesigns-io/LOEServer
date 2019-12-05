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
    $movieCounts = $this->_webClient->getLoeMovieCounts();
    foreach($movieCounts as $obj){
      $movie = \LOE\Factory::search(Movie::TABLE,'file_path',$this->_buildPath($obj->query));
      if(!count($movie)){
        $this->exceptions[] = $this->_buildPath($obj->query);
        continue;
      }else{
        $movie = $movie[0];
      }
      $movie->file_path = \LOE\Base::WEBROOT . $movie->file_path;
      $movie->cover_path = \LOE\Base::WEBROOT . $movie->cover_path;
      $movie->play_count = $obj->downloads;
      $movie->update();
    }
    return $this;
  }
  protected function _buildPath($query){
    return \LOE\Base::WEBROOT . "/LOE" . preg_replace(self::SPACEPATT," ",$query);
  }
}
