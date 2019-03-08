<?php namespace LOE;

require_once __DIR__ . '/../../Libs/MessageClient/MessageClient.php';

abstract class FsScanner extends \MessageClient{

  const ROOTERR = 'call _root(rootdir) to begin forever scan';
  protected $_scanForeverRoot;
  abstract protected function _interpretFile($absolutePath);

  protected function _scanForever($dir){
    if(empty($this->_scanForeverRoot)){
      throw new \Exception(self::ROOTERR);
    }
    $results = scandir($dir);
    foreach($results as $result){
      if($result == '.' || $result == '..'){
        continue;
      }else{
        $tester = ($dir == $this->_scanForeverRoot) ? $dir . $result : $dir . "/" . $result;
      }
      if(is_file($tester)){
        $this->_interpretFile($tester);
      }elseif(is_dir($tester)){
        $this->_scanForever($tester);
      }else{
        continue;
      }
    }
    return $this;
  }
  protected function _root($dir){
    $this->_scanForeverRoot = $dir;
    return $this;
  }
  //abstract protected function _recordExists($absolutePath);
  //abstract protected function _verifyDatabase();
}
