<?php namespace LOE\Music;

require_once __DIR__ . '/../FsScanner.php';
require_once __DIR__ . '/../../../Factory.php';


//todo scan LOE for all mp3s
//todo foreach mp3 is this mp3 in the database?

class FsHealthScanner extends \LOE\FsScanner{

  const ROOTDIR = '/LOE/Music/';
  const MSGNAME = "LOE_MUSIC_FS_CHECK";
  const MSGSUBJ = "Library of Everything File System Check";

  public $missing = array();
  protected $_files = array();

  public function __construct(){
    $this->_scanForever(\LOE\LoeBase::WEBROOT . self::ROOTDIR)
         ->_verifyDatabase();
  }

  protected function _scanForever($dir){
    $results = scandir($dir);
    foreach($results as $result){
      if($result == '.' || $result == '..'){
        continue;
      }else{
        $tester = ($dir == \LOE\LoeBase::WEBROOT . self::ROOTDIR) ? $tester = $dir . $result : $tester = $dir . "/" . $result;
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
  protected function _interpretFile($absolutePath){
    $fileInfo = pathinfo($absolutePath);
    if($fileInfo['extension'] == "mp3"){
      $this->_files[] = $absolutePath;
    }
    return $this;
  }
  protected function _verifyDatabase(){
    foreach($this->_files as $file){
      if(!$this->_recordExists($file)){
        $this->missing[] = $file;
      }
    }
    return $this;
  }
  protected function _recordExists($absolutePath){
    $GLOBALS['db']
                ->database(\LOE\Song::DB)
                ->table(\LOE\Song::TABLE)
                ->select(\LOE\Song::PRIMARYKEY)
                ->where("file_path","=","'" . preg_replace("/'/","",$absolutePath) . "'");
    try{
      $results = $GLOBALS['db']->get();
    }catch(\Exception $e){
      echo $e->getMessage() . "\n";
      echo $absolutePath . "\n";
      echo $GLOBALS['db']->query . "\n";
      exit;
    }
    if(!mysqli_num_rows($results)){
      return false;
    }
    return true;
  }
}
