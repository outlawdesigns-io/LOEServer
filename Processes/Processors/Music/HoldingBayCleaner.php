<?php namespace LOE\Music;

require_once __DIR__ . '/../../../Factory.php';

class HoldingBayCleaner{

  const NONASCIIPATT = '/[^\x00-\x7F]/';
  const BADFILEPATT = '/[\\\/:"*?<>|]+/';

  public $filesCleaned;
  public $songs;

  protected $_scanner;

  public function __construct(){
    $this->filesCleaned = 0;
    $this->_scanner = \LOE\Factory::createScanner(\LOE\Song::TABLE);
    foreach($_scanner->songs as $song){
      $updated = false;
      if(preg_match(self::NONASCIIPATT,$song->file_path) || preg_match(self::BADFILEPATT,$song->file_path)){
        $source = $song->file_path;
        $song->file_path = preg_replace(self::NONASCIIPATT,"",$song->file_path);
        $song->file_path = preg_replace(self::BADFILEPATT,"",$song->file_path);
        $updated = true;
      }
      if($updated && !rename($source,$song->file_path)){
        throw new \Exception(error_get_last());
      }
      $this->filesCleaned++;
      $this->songs[] = $song;
    }
  }
}
