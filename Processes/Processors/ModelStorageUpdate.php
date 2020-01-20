<?php namespace LOE;

require_once __DIR__ . '/../../Factory.php';

class ModelStorageUpdate{

  const SHELLBASE = 'du -sh ';
  const TARGETMODEL = 'ModelStorage';
  const REGPATTERN = '/^(.*?)([A-z])/';

  protected $_model;
  protected $_storageModel;

  public function __construct($model){
    $this->_model = $model;
    $this->_storageModel = Factory::createModel(self::TARGETMODEL);
    $this->_storageModel->modelId = $this->_model->id;
    $results = $this->_execShellCommand(Base::WEBROOT . $this->_model->fsRoot);
    $this->_storageModel->fs_size = $results[0];
    $this->_storageModel->fs_unit = $results[1];
    $results = $this->_execShellCommand(Base::WEBROOT . $this->_model->holdingBayRoot);
    $this->_storageModel->hb_size = $results[0];
    $this->_storageModel->hb_unit = $results[1];
    $this->_storageModel->create();
  }
  protected function _execShellCommand($dir){
    $cmd = self::SHELLBASE . $dir;
    return $this->_parseResults(shell_exec($cmd));
  }
  protected function _parseResults($resultStr){
    if(preg_match(self::REGPATTERN,$resultStr,$matches)){
      $size = $matches[1];
      $unit = $matches[2];
    }
    return array($size,$unit);
  }
}
