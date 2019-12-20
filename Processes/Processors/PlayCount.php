<?php namespace LOE;

require_once __DIR__ . '/../../Factory.php';
require_once __DIR__ . '/../../Libs/WebAccessClient/WebAccessClient.php';

class PlayCount{

  const SPACEPATT = "/%20/";

  public $exceptions = array();
  public $processedCount;
  protected $_webClient;
  protected $_model;
  protected $_modelCounts = array();

  public function __construct($model,$username,$password){
    $this->_model = $model;
    $this->processedCount = 0;
    try{
      $this->_webClient = new \WebAccessClient(\WebAccessClient::authenticate($username,$password)->token);
      $this->_getModels()->_updateCounts();
    }catch(\Exception $e){
      throw new \Exception($e->getMessage());
    }
  }
  protected function _getModels(){
    switch($this->_model->label){
      case 'Movie':
        $this->_modelCounts = $this->_webClient->getLoeMovieCounts();
      break;
      case 'Song':
        $this->_modelCounts = $this->_webClient->getLoeSongCounts();
      break;
      case 'Episode':
        $this->_modelCounts = $this->_webClient->getLoeEpisodeCounts();
      break;
      default:
        throw new \Exception('Invalid Model');
    }
    return $this;
  }
  protected function _updateCounts(){
    foreach($this->_modelCounts as $obj){
      $model = Factory::search($this->_model->label,'file_path',$this->_buildPath($obj->query));
      if(!count($model)){
        $this->exceptions[] = $this->_buildPath($obj->query);
        continue;
      }else{
        $model = $model[0];
      }
      $model->file_path = Base::WEBROOT . $model->file_path;
      $model->cover_path = Base::WEBROOT . $model->cover_path;
      $model->play_count = $obj->listens;
      $model->update();
      $this->processedCount++;
    }
    return $this;
  }
  protected function _buildPath($query){
    return Base::WEBROOT . "/LOE" . preg_replace(self::SPACEPATT," ",$query);
  }
}
