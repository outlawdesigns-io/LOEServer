<?php namespace LOE\Music;

require_once __DIR__ . '/../../../Factory.php';
require_once __DIR__ . '/HoldingBayCleaner.php';

/*
How to handle uuid management?
buildAlbumDir -> check database for this string
if(exists){
  apply album_uuid and artist_uuid to this song
}elseif(artistdir exists in db){
  (there's an exception here where 2 artists of the same name come into play) <-- I think this logic is sound; you're just confused because it negates the anticipated bracket approach...no it doesn't, we still need to identify and split 2 artists of the same name...
  generate album_uuid and apply artist_uuid
}else{
  generate new uuids
}

None of the above handles the two artist in the same dir problem...
*/

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
        $this->artistDir = $this->_buildArtistDir();
        $this->albumDir = $this->_buildAlbumDir();
        $this->sourceFile = $this->song->file_path;
        $this->coverPath = $this->albumDir . "cover.jpg";
        $this->targetFile = $this->albumDir . pathinfo($this->song->file_path,PATHINFO_BASENAME);
        $this->_verifyDestination()
            ->_tryCover()
            ->_transfer()
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
    protected function _buildArtistDir(){
      $artistDir = Song::buildCleanPath(self::DESTDIR . $this->song->artist . "/");
      return $artistDir;
    }
    protected function _buildAlbumDir(){
      $albumDir = $this->artistDir . $this->song->album . " (" . $this->song->year . ")/";
      return Song::buildCleanPath($albumDir);
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
