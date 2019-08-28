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

  public function __construct($username,$password){
    try{
      $this->_webClient = new \WebAccessClient(\WebAccessClient::authenticate($username,$password)->token);
      $this->_updatePlayHistory();
    }catch(\Exception $e){
      throw new \Exception($e->getMessage());
    }
  }
  protected function _buildPath($query){
    return \LOE\LoeBase::WEBROOT . "/LOE" . preg_replace(self::SPACEPATT," ",$query);
  }
  protected function _updatePlayHistory(){
    $results = $this->_webClient->search(self::REQEND,self::REQKEY,self::REQVAL);
    foreach($results as $reqObj){
      $song = \LOE\Factory::search(\LOE\Song::TABLE,'file_path',$this->_buildPath($reqObj->query));
      if(!count($song)){
        $this->exceptions[] = $this->_buildPath($reqObj->query);
        continue;
      }else{
        $song = $song[0];
      }
      if(!\LOE\PlayedSong::recordExists($song->UID,$reqObj->requestDate)){
        $playedSong = \LOE\Factory::create(\LOE\PlayedSong::TABLE);
        $playedSong->songId = $song->UID;
        $playedSong->playDate = $reqObj->requestDate;
        $playedSong->create();  
      }
    }
    return $this;
  }
}
