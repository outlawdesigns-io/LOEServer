<?php namespace LOE;

require_once __DIR__ . '/../../Factory.php';


abstract class HoldingBayScanner extends FsScanner{

  public $targetModels = array();
  public $possibleCovers = array();
  public $extraFiles = array();

  protected $_model;

  public function __construct($model){
    $this->_model = $model;
    $this->_scanForever(\LOE\Base::WEBROOT . $this->_model->holdingBayRoot);
  }
  protected function _interpretFile($absolutePath){
    $fileInfo = pathinfo($absolutePath);
    $model = Factory::createModel($this->_model->label);
    if(in_array($fileInfo['extension'],$this->_model->fileExtensions)){
      $model->file_path = $model->cleanFilePath($absolutePath);
      $this->targetModels[] = $model;
    }elseif(strtolower($fileInfo['extension']) == 'jpg'){
      $this->possibleCovers[] = $model->cleanFilePath($absolutePath);
    }else{
      $this->extraFiles[] = $model->cleanFilePath($absolutePath);
    }
    return $this;
  }
}
