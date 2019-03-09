<?php namespace LOE;

require_once __DIR__ . '/../../Libs/MessageClient/MessageClient.php';

abstract class FsScanner extends \MessageClient{

  abstract protected function _interpretFile($absolutePath);

  public static function isDirShortcut($relativePath){
    if($relativePath == '.' || $relativePath == '..'){
      return true;
    }
    return false;
  }

  protected function _scanForever($dir){
    $results = scandir($dir);
    foreach($results as $result){
      if(self::isDirShortcut($result)){
        continue;
      }else{
        $tester = $dir . "/" . $result;
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
  //abstract protected function _recordExists($absolutePath);
  //abstract protected function _verifyDatabase();
}
