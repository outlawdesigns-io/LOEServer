<?php namespace LOE\Music;

//todo send a pretty email
//todo embedd an access token into that email to allow one-click processing

require_once __DIR__ . '/../../../Factory.php';

class HoldingBayNotification{

  protected $_scanner;

  public function __construct(){
    $this->_scanner = \LOE\Factory::createHoldingBayScanner(\LOE\Factory::getModel('Song'));
    $keys = array_keys($this->_scanner->albums);
    $album = $this->_scanner->albums[$keys[0]];
  }
}
