<?php namespace LOE\Music;

require_once __DIR__ . '/../../../Factory.php';
require_once __DIR__ . '/../../../Libs/MetalArchivesClient/MetalArchivesClient.php';

//todo: send the results as an email.

class DuplicateArtistScanner{

  public $finalResults = array();

  protected $_metalArchivesClient;

  public function __construct(){
    $this->_metalArchivesClient = new \MetalArchivesClient();
    $this->_run();
    print_r($this->finalResults);
  }
  protected function _run(){
    $data = $this->_getSongs();
    foreach($data as $artist => $albums){
      $artistIds = array();
      $possibleArtists = $this->_buildPossibleArtists($artist);
      if(count($possibleArtists) <= 1){
        continue;
      }
      foreach($albums as $album){
        try{
          $albumResult = $this->_metalArchivesClient->searchAlbum($this->_cleanAlbumStr($album['album']),$artist);
        }catch(\Exception $e){
          continue;
        }
        $artistId = $this->_matchToArtistId($albumResult->id,$possibleArtists);
        if(!in_array($artistId,$artistIds)){
          $artistIds[] = $artistId;
        }
      }
      if(count($artistIds) > 1){
        if(!isset($finalResults[$artist])){
          $finalResults[$artist] = $artistIds;
        }
      }
    }
  }
  protected function _getSongs(){
    $data = array();
    $GLOBALS['db']->database(\LOE\Music\Song::DB)->table(\LOE\Music\Song::TABLE)->select("distinct artist,album")->where("genre","like","'%metal%'");
    $results = $GLOBALS['db']->get();
    while($row = mysqli_fetch_assoc($results)){
      $data[] = $row;
    }
    return $this->_sortResults($data);
  }
  protected function _sortResults($data){
    $sorted = array();
    foreach($data as $key=>$item){
      $sorted[$item['artist']][$key] = $item;
    }
    return $sorted;
  }
  protected function _cleanAlbumStr($str){
    $pattern = '/\s\[.*/';
    return trim(preg_replace($pattern,'',$str));
  }
  protected function _buildPossibleArtists($artistStr){
    try{
      $artists = $this->_metalArchivesClient->searchArtist($artistStr);
    }catch(\Exception $ex){
      return array();
    }
    if(!is_array($artists)){
      return array($artists);
    }
    for($i = 0; $i < count($artists); $i++){
      $artists[$i]->discog = $this->_metalArchivesClient->getDiscography($artists[$i]->id);
    }
    return $artists;
  }
  protected function _matchToArtistId($albumId,$possibleArtists){
    foreach($possibleArtists as $possibleArtist){
      foreach($possibleArtist->discog as $disc){
        if($disc->id == $albumId){
          return $possibleArtist->id;
        }
      }
    }
    return 0;
  }

}
