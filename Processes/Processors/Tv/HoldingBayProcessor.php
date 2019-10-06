<?php namespace LOE\Tv;

require_once __DIR__ . '/../../../Factory.php';

class HoldingBayProcessor{

    const DESTDIR = '/var/www/html/LOE/Video/Tv/';

    public $episode;
    private $showDir;
    private $seasonDir;
    private $sourceFile;
    private $targetFile;

    public function __construct($episode){
        $this->episode = \LOE\Factory::createModel(Episode::TABLE);
        $this->episode->setFields($episode);
        $this->genreDir = self::DESTDIR . $this->episode->genre . '/';
        $this->showDir = $this->genreDir . $this->episode->show_title . '/';
        $this->seasonDir = $this->showDir . "Season " . $this->episode->season_number . "/";
        $this->sourceFile = $this->episode->file_path;
        $this->_buildTargetFile()
            ->_verifyDestination()
            ->_transfer()
            ->_tryCover()
            ->_cleanUp();
    }
    private function _buildTargetFile(){
        $pathInfo = pathinfo($this->sourceFile);
        $extension = $pathInfo['extension'];
        $this->targetFile = $this->seasonDir . $this->episode->ep_number . ' - ' . $this->episode->ep_title . '.' . $extension;
        return $this;
    }
    private function _verifyDestination(){
        if(!is_dir($this->genreDir) && !mkdir($this->genreDir)){
            $error = error_get_last();
            $exceptionStr = 'Failed to mkdir: ' . $error['message'];
            throw new \Exception($exceptionStr);
        }
        if(!is_dir($this->showDir) && !mkdir($this->showDir)){
            $error = error_get_last();
            $exceptionStr = 'Failed to mkdir: ' . $error['message'];
            throw new \Exception($exceptionStr);
        }
        if(!is_dir($this->seasonDir) && !mkdir($this->seasonDir)){
            $error = error_get_last();
            $exceptionStr = 'Failed to mkdir: ' . $error['message'];
            throw new \Exception($exceptionStr);
        }
        return $this;
    }
    private function _transfer(){
        if(!rename($this->sourceFile,$this->targetFile)){
            $error = error_get_last();
            $exceptionStr = 'Failed to move: ' . $error['message'];
            throw new \Exception($exceptionStr);
        }else{
            $this->episode->file_path = $this->targetFile;
            if($this->episode->season_number <= 9){
                $this->episode->cover_path = $this->showDir . 'covers/S0' . $this->episode->season_number . 'cover.jpg';
            }else{
                $this->episode->cover_path = $this->showDir . 'covers/S' . $this->episode->season_number . 'cover.jpg';
            }
            $this->episode->create();
        }
        return $this;
    }
    private function _tryCover(){
        $showDir = dirname($this->episode->cover_path);
        $sourceFile = dirname($this->sourceFile) . "/cover.jpg";
        if(!is_dir($showDir) && !mkdir($showDir)){
            $error = error_get_last();
            $exceptionStr = 'Failed to mkdir: ' . $error['message'];
            throw new \Exception($exceptionStr);
        }
        if(is_file($sourceFile) && !copy($sourceFile,$this->episode->cover_path)){
            $error = error_get_last();
            $exceptionStr = 'Failed copying cover: ' . $error['message'];
            throw new \Exception($exceptionStr);
        }elseif(is_file($sourceFile) && !unlink($sourceFile)){
            $error = error_get_last();
            $exceptionStr = 'Failed to cleanup: ' . $error['message'];
            throw new \Exception($exceptionStr);
        }
        return $this;
    }
    private function trySubtitles(){
        return $this;
    }
    private function _cleanUp(){
        $seasonDir = dirname($this->sourceFile);
        $showDir = dirname($seasonDir);
        if(count(scandir($dir)) == 2 && !rmdir($seasonDir)){
          throw new \Exception(error_get_last()['message']);
        }
        if(count(scandir($dir)) == 2 && !rmdir($showDir)){
          throw new \Exception(error_get_last()['message']);
        }
        return $this;
    }
}
