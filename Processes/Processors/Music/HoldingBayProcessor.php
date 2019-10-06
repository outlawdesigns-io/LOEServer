<?php namespace LOE\Music;

require_once __DIR__ . '/../../../Factory.php';
require_once __DIR__ . '/HoldingBayCleaner.php';

class HoldingBayProcessor{

    const DESTDIR = '/var/www/html/LOE/Music/';
    const WEBPATTERN = '/http:\/\//';

    public $song;
    private $albumDir;
    private $artistDir;
    private $sourceFile;
    private $coverPath;
    private $targetFile;

    public function __construct($song){
        $this->song = \LOE\Factory::createModel(Song::TABLE);
        $this->song->setFields($song);
        $this->song->file_path = Song::WEBROOT . $this->song->file_path;
        $this->artistDir = self::DESTDIR . $this->song->artist . "/";
        $this->albumDir = $this->_buildAlbumDir();
        $this->sourceFile = $this->song->file_path;
        $this->coverPath = $this->albumDir . "cover.jpg";
        $this->targetFile = $this->albumDir . pathinfo($this->song->file_path,PATHINFO_BASENAME);
        $this->_verifyDestination()
            ->_transfer()
            ->_tryCover()
            ->_cleanUp();
    }
    private function _verifyDestination(){
        if(!is_dir($this->artistDir) && !mkdir($this->artistDir)){
            $error = error_get_last();
            $exceptionStr = 'Failed to create artist Dir: ' . $error['message'];
            throw new \Exception($exceptionStr);
        }
        if(!is_dir($this->albumDir) && !mkdir($this->albumDir)){
            $error = error_get_last();
            $exceptionStr = 'Failed to create album Dir: ' . $error['message'];
            throw new \Exception($exceptionStr);
        }
        return $this;
    }
    protected function _buildAlbumDir(){
      $albumDir = $this->artistDir . $this->song->album . " (" . $this->song->year . ")/";
      return HoldingBayCleaner::buildCleanPath($albumDir);
    }
    private function _transfer(){
        if(!rename($this->sourceFile,$this->targetFile)){
            throw new \Exception('Failed to Transfer');
        }else{
            $this->song->file_path = $this->targetFile;
            $this->song->cover_path = $this->coverPath;
            $this->song->create();
        }
        return $this;
    }
    private function _tryCover(){
      if($this->song->cover_path == "" || !isset($this->song->cover_path) || is_null($this->song->cover_path)){
        $sourceFile = dirname($this->sourceFile) . "/cover.jpg";
        if(is_file($sourceFile) && !rename($sourceFile,$this->coverPath)){
          throw new \Exception(error_get_last()['message']);
        }
      }elseif(!preg_match(self::WEBPATTERN,$this->song->cover_path)){
        if(is_file($this->song->cover_path) && !rename($this->song->cover_path,$this->coverPath)){
          throw new \Exception(error_get_last()['message']);
        }
      }else{
        $file = file_get_contents($this->song->cover_path);
        if(!file_put_contents($this->coverPath,$file)){
          throw new \Exception(error_get_last()['message']);
        }
      }
      return $this;
    }
    private function _cleanUp(){
        $dir = dirname($this->sourceFile);
        if(count(scandir($dir)) == 2 && !rmdir($dir)){
            $error = error_get_last();
            $exceptionStr = "Failed To Remove Dir: " . $error['message'];
            throw new \Exception($exceptionStr);
        }
        return $this;
    }

}
