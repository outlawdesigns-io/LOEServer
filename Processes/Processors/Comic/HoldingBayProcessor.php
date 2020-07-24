<?php namespace LOE\Comic;

require_once __DIR__ . '/../../../Factory.php';

class HoldingBayProcessor{

  /*
  Create/verify Publisher Directory
  Create/Verify Volume Directory
  source file becomes /LOE/Comics/$publisher/$volume/{3 digit version of $this->comic->issue_number}
  */

    const DESTDIR = '/LOE/Comics/';
    const WEBPATTERN = '/http:\/\//';

    public $comic;
    private $publisherDir; //todo publisher dir
    private $seriesDir; //series (volume) dir == cleaned series title (YYYY)
    private $sourceFile;
    private $coverPath;
    private $targetFile;

    public function __construct($comic){
        $this->comic = \LOE\Factory::createModel(Comic::TABLE);
        $this->comic->setFields($comic);
        $this->comic->file_path = Comic::WEBROOT . $this->comic->file_path;
        $this->publisherDir = Comic::buildCleanPath(Comic::WEBROOT . self::DESTDIR . $this->comic->publisher . '/');
        $this->seriesDir = $this->_buildVolumeDir();
        $this->sourceFile = $this->comic->file_path;
        //$this->coverPath = $this->albumDir . "cover.jpg";
        $this->targetFile = $this->seriesDir . $this->_buildFileName();
        $this->_verifyDestination()
            //->_tryCover()
            ->_transfer()
            ->_cleanUp();
    }
    private function _verifyDestination(){
        if(!is_dir($this->publisherDir) && !mkdir($this->publisherDir)){
            $error = error_get_last();
            $exceptionStr = 'Failed to create publisher Dir: ' . $error['message'];
            throw new \Exception($exceptionStr);
        }
        if(!is_dir($this->seriesDir) && !mkdir($this->seriesDir)){
            $error = error_get_last();
            $exceptionStr = 'Failed to create volume Dir: ' . $error['message'];
            throw new \Exception($exceptionStr);
        }
        return $this;
    }
    protected function _buildVolumeDir(){
      $volumeDir = $this->publisherDir . $this->comic->series_title . " (" . $this->comic->series_start_year . ")/";
      return Comic::buildCleanPath($volumeDir);
    }
    protected function _buildFileName(){
      $fileName = '';
      $pathInfo = pathinfo($this->comic->file_path);
      $numZeros = 3 - strlen($this->comic->issue_number);
      for($i = 0; $i < $numZeros; $i++){
        $fileName .= '0';
      }
      $fileName .= $this->comic->issue_number;
      return $fileName . '.' . $pathInfo['extension'];
    }
    private function _transfer(){
        if(!rename($this->sourceFile,$this->targetFile)){
            throw new \Exception('Failed to Transfer');
        }else{
            $this->comic->file_path = $this->targetFile;
            //$this->song->cover_path = $this->coverPath;
            //$this->comic->create();
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
        $this->song->cover_path = Song::WEBROOT . $this->song->cover_path;
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
