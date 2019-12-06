<?php namespace LOE\Tv;

require_once __DIR__ . '/../../../Factory.php';
require_once __DIR__ . '/../../../Libs/WebAccessClient/WebAccessClient.php';

class PlayHistory{

  const SPACEPATT = "/%20/";
  const REQEND = 'request';
  const REQKEY = 'query';

  protected $_webClient;
  public $exceptions = array();
  public static $responseCodes = array(202,206,304);
  public static $searchVals = array('.mp4','.mkv','.avi');

  public function __construct($username,$password){
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
        $episode = \LOE\Factory::search(Movie::TABLE,'file_path',$this->_buildPath($reqObj->query));
        if(!count($episode)){
          $this->exceptions[] = $this->_buildPath($reqObj->query);
          continue;
        }else{
          $episode = $episode[0];
        }
        if(!Played::recordExists($episode->UID,$reqObj->requestDate) && in_array($reqObj->responseCode,self::$responseCodes)){
          $played = \LOE\Factory::createModel(Played::TABLE);
          $played->episodeId = $episode->UID;
          $played->playDate = $reqObj->requestDate;
          $played->ipAddress = $reqObj->ip_address;
          $played->create();
        }
      }
    }
    return $this;
  }
}
