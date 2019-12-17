<?php namespace LOE\Movie;

require_once __DIR__ . '/../../../Factory.php';
require_once __DIR__ . '/../../../Libs/WebAccessClient/WebAccessClient.php';

class PlayHistory{

  const SPACEPATT = "/%20/";
  const REQEND = 'request';
  const REQKEY = 'query';

  protected $_webClient;
  public $exceptions = array();
  public $processedCount;
  public static $responseCodes = array(202,206,304);
  public static $searchVals = array('.mp4','.mkv','.avi');

  public function __construct($username,$password){
    $this->processedCount = 0;
    try{
      $this->_webClient = new \WebAccessClient(\WebAccessClient::authenticate($username,$password)->token);
      $this->_updatePlayHistory();
    }catch(\Exception $e){
      throw new \Exception($e->getMessage());
    }
  }
  protected function _buildPath($query){
    return \LOE\Base::WEBROOT . "/LOE" . preg_replace(self::SPACEPATT," ",$query);
  }
  protected function _updatePlayHistory(){
    foreach(self::$searchVals as $searchVal){
      $results = $this->_webClient->search(self::REQEND,self::REQKEY,$searchVal);
      foreach($results as $reqObj){
        $movie = \LOE\Factory::search(Movie::TABLE,'file_path',$this->_buildPath($reqObj->query));
        if(!count($movie)){
          $this->exceptions[] = $this->_buildPath($reqObj->query);
          continue;
        }else{
          $movie = $movie[0];
        }
        if(!Played::recordExists($movie->UID,$reqObj->requestDate) && in_array($reqObj->responseCode,self::$responseCodes)){
          $playedMovie = \LOE\Factory::createModel(Played::TABLE);
          $playedMovie->movieId = $movie->UID;
          $playedMovie->playDate = $reqObj->requestDate;
          $playedMovie->ipAddress = $reqObj->ip_address;
          $playedMovie->create();
          $this->processedCount++;
        }
      }
    }
    return $this;
  }
}
