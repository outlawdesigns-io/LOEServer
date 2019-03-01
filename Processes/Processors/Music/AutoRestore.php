<?php

//todo unlink src archive


// try{
//   $m = new MusicRestore();
// }catch(Exception $e){
//   echo $e->getMessage() . "\n";
// }

class RestoreTarget{
  public $artist;
  public $album;
  public $absolutePath;

  public function __construct($artist,$album,$abosulePath){
    $this->artist = $artist;
    $this->album = $album;
    $this->absolutePath = $abosulePath . '/';
  }
}

class MusicRestore{

  const RESTOREPATH = '/var/www/html/LOE/Recovery/music/';
//  const RESTOREPATH = '/var/www/html/LOE/Recovery/test/';
  const MUSICPATH = '/var/www/html/LOE/Music/';
  const EMPTYCMD = 'find /var/www/html/LOE/Music -type d -empty';
  const YEARPATT = '/(\(.*)/';
  const WSPATT = '/\s/';
  const NEWLN = "\n";
  const DIRSEP = '/';
  protected $archiveTypes = array('rar','zip','7z');
  protected $unpackMethods = array('unp','unzip','p7zip -d');
  protected $restoreArchives = array();
  protected $restoreTargets = array();

  public function __construct(){
    $this->_buildRecoveryArchives()
    ->_buildRestoreTargets()
    ->_parseRecoveryArchives();
  }

  protected function _buildRecoveryArchives(){
    $results = scandir(self::RESTOREPATH);
    foreach($results as $result){
      $this->_parseRecoveryFile(self::RESTOREPATH . $result);
    }
    return $this;
  }
  protected function _parseRecoveryFile($abosulePath){
    $extension = pathinfo($abosulePath)['extension'];
    if(in_array($extension,$this->archiveTypes) && !in_array($abosulePath,$this->restoreArchives)){
      $this->restoreArchives[] = $abosulePath;
    }
    return $this;
  }
  protected function _buildRestoreTargets(){
    $output = explode(self::NEWLN,shell_exec(self::EMPTYCMD));
    foreach($output as $ln){
      $this->restoreTargets[] = new RestoreTarget(
        explode(self::DIRSEP,$ln)[count(explode(self::DIRSEP,$ln)) - 2],
        preg_replace(self::YEARPATT,'',explode(self::DIRSEP,$ln)[count(explode(self::DIRSEP,$ln)) - 1]),
        $ln
      );
    }
    return $this;
  }
  protected function _parseRecoveryArchives(){
    $counter = 0;
    foreach($this->restoreArchives as $archive){
      foreach($this->restoreTargets as $target){
        if($this->_scanForMatches($target->album,$archive) && $this->_isDirEmpty($target->absolutePath)){
          try{
            $this->_restoreArchive($archive,$target->absolutePath);
            $this->_cleanResults($target->absolutePath);
            $this->_unlink($archive);
          }catch(Exception $e){
            throw new Exception($e->getMessage());
          }
        }
      }
    }
    return $this;
  }
  protected function _isDirEmpty($absolutePath){
    if (!is_readable($absolutePath)) return NULL;
    return (count(scandir($absolutePath)) == 2);
  }
  protected function _isDirShortcut($str){
    if($str != "." && $str != ".."){
      return false;
    }
    return true;
  }
  protected function _scanForMatches($testStr,$archivePath){
    $pattern = self::DIRSEP . $testStr . self::DIRSEP;
    if(preg_match($pattern,$archivePath)) return true;
    $pattern = self::DIRSEP . preg_replace(self::WSPATT,"_",trim($testStr)) . self::DIRSEP;
    if(preg_match($pattern,$archivePath)) return true;
    return false;
  }
  protected function _restoreArchive($recoveryArchive,$destinationDir){
    $destination = $destinationDir . pathinfo($recoveryArchive)['basename'];
    if(!copy($recoveryArchive,$destination)){
      $error = error_get_last()['message'];
      throw new Exception($error);
    }
    try{
      $this->_unpackArchive($destination);
    }catch(Exception $e){
      throw new Exception($e->getMessage());
    }
    return $this;
  }
  protected function _unpackArchive($absolutePath){
    $unpackMethod = $this->unpackMethods[array_search(pathinfo($absolutePath)['extension'],$this->archiveTypes)];
    $cmd = $unpackMethod . " " . escapeshellarg($absolutePath);
    if($unpackMethod == $this->unpackMethods[1]){
      $cmd .= " -d " . escapeshellarg(pathinfo($absolutePath)['dirname']) . '/';
    }elseif($unpackMethod == $this->unpackMethods[0]){
      $cmd .= " " . escapeshellarg(pathinfo($absolutePath)['dirname']) . '/';
    }else{
      throw new Exception('No support for ' . $unpackMethod);
    }
    exec($cmd,$output,$exitCode);
    if($exitCode){
      $error = implode(self::NEWLN,$output);
      throw new Exception($error);
    }elseif(!unlink($absolutePath)){
      $error = error_get_last()['message'];
      throw new Exception($error);
    }
    return $this;
  }
  protected function _cleanResults($destinationDir){
    $results = scandir($destinationDir);
    foreach($results as $result){
      if(!$this->_isDirShortcut($result) && is_dir($destinationDir . $result) && $this->_isDirEmpty($destinationDir . $result) && !rmdir($destinationDir . $result)){
        $error = error_get_last()['message'];
        throw new Exception($error);
      }elseif(!$this->_isDirShortcut($result) && is_dir($destinationDir . $result)){
        try{
          $this->_shiftDir($destinationDir . $result);
        }catch(Exception $e){
          throw new Exception($e->getMessage());
        }
      }
    }
    return $this;
  }
  protected function _shiftDir($targetDir){
    $destinationDir = pathinfo($targetDir)['dirname'] . self::DIRSEP;
    $files = scandir($targetDir);
    foreach($files as $file){
      $file_absolutePath = $targetDir . self::DIRSEP . $file;
      echo $file_absolutePath . "\n";
      if(!$this->_isDirShortcut($file) && is_file($file_absolutePath)){
        try{
          $this->_shiftUp($file_absolutePath,$destinationDir);
        }catch(Exception $e){
          throw new Exception($e->getMessage());
        }
      }
    }
    try{
        $this->_cleanup($targetDir);
    }catch(\Exception $e){
        throw new \Exception($e->getMessage());
    }
    return $this;
  }
  protected function _shiftUp($sourceFile,$destinationDir){
    if(!rename($sourceFile,$destinationDir . pathinfo($sourceFile)['basename'])){
      $error = error_get_last()['message'];
      throw new Exception($error);
    }
    return $this;
  }
  protected function _cleanup($dir){
      if(is_dir($dir)){
          $results = scandir($dir);
          foreach($results as $result){
              if(!$this->_isDirShortcut($result)){
                  if(is_dir($dir . self::DIRSEP . $result)){
                      $this->_cleanup($dir . self::DIRSEP . $result);
                  }elseif(!unlink($dir . self::DIRSEP . $result)){
                      $error = error_get_last()['message'];
                      throw new \Exception($error);
                  }
              }
          }
      }
      if(!rmdir($dir)){
          $error = error_get_last()['message'];
          throw new \Exception($error);
      }
      return $this;
  }
  protected function _unlink($absolutePath){
      if(!unlink($absolutePath)){
          $error = error_get_last()['message'];
          throw new \Exception($error);
      }
      return $this;
  }

}
