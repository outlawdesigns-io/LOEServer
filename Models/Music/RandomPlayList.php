<?php namespace LOE\Music;

require_once __DIR__ . '/../../Factory.php';

class RandomPlayList{

  public $songs = array();

  protected $_genre;
  protected $_maxSongs;

  public function __construct($genre = null,$maxSongs = 10){
    $this->_genre = $genre;
    $this->_maxSongs = $maxSongs;
    $this->_build();
  }
  protected function _build(){
    for($i = 0; $i < $this->_maxSongs; $i++){
      $this->songs[] = Song::getRandom($this->_genre);
    }
    return $this;
  }
}
