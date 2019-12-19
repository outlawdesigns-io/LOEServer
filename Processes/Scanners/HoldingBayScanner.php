<?php namespace LOE;

require_once __DIR__ . '/../../Factory.php';


class HoldingBayScanner extends FsScanner{

  protected $_model;

  public function __construct($model){
    $this->$_model = $model;
  }
}
