<?php namespace LOE\Music;

require_once __DIR__ . '/../../../Factory.php';
require_once __DIR__ . '/../../../Libs/WebAccessClient/WebAccessClient.php';

class PlayHistory{

  const SPACEPATT = "/%20/";
  const REQEND = 'request';
  const REQKEY = 'query';
  const REQVAL = '.mp3';

  protected $_webClient;
  public $exceptions = array();
  public static $responseCodes = array(202,206,304);

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
    $results = $this->_webClient->search(self::REQEND,self::REQKEY,self::REQVAL);
    foreach($results as $reqObj){
      $song = \LOE\Factory::search(Song::TABLE,'file_path',$this->_buildPath($reqObj->query));
      if(!count($song)){
        $this->exceptions[] = $this->_buildPath($reqObj->query);
        continue;
      }else{
        $song = $song[0];
      }
<<<<<<< HEAD
      if(!\LOE\PlayedSong::recordExists($song->UID,$reqObj->requestDate) && in_array($reqObj->responseCode,self::$responseCodes)){
        $playedSong = \LOE\Factory::create(\LOE\PlayedSong::TABLE);
=======
      if(!PlayedSong::recordExists($song->UID,$reqObj->requestDate)){
        $playedSong = \LOE\Factory::createModel(Played::TABLE);
>>>>>>> development
        $playedSong->songId = $song->UID;
        $playedSong->playDate = $reqObj->requestDate;
        $playedSong->ipAddress = $reqObj->ip_address;
        $playedSong->create();
      }
    }
    return $this;
  }
}
