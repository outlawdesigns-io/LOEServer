<?php namespace LOE\Music;

require_once __DIR__ . '/../../Base.php';
require_once __DIR__ . '/Song.php';

class Played extends \LOE\Base{

  const TABLE = 'PlayedSong';

  public $UID;
  public $songId;
  public $ipAddress;
  public $playDate;

  public function __construct($UID = null){
    parent::__construct(self::DB,self::TABLE,self::PRIMARYKEY,$UID);
  }
  public static function recordExists($songId,$playDate){
    $results = $GLOBALS['db']
        ->database(self::DB)
        ->table(self::TABLE)
        ->select(self::PRIMARYKEY)
        ->where("songId","=",$songId)
        ->andWhere("playDate","=","'" . $playDate . "'")
        ->get();
    if(!mysqli_num_rows($results)){
      return false;
    }
    return true;
  }
  public static function dates(){
    $data = array();
    $results = $GLOBALS['db']
        ->database(self::DB)
        ->table(self::TABLE)
        ->select("DISTINCT CAST(playDate as DATE) as playedDate")
        ->orderBy("playDate desc")
        ->get();
    while($row = mysqli_fetch_assoc($results)){
      $data[] = $row['playDate'];
    }
    return $data;
  }
  public static function counts($key,$date = null){
    $data = array();
    $GLOBALS['db']
        ->database(self::DB)
        ->table(Song::TABLE . " music")
        ->select("count(played.UID) as count,music." . $key)
        ->join(self::TABLE . " played","played.songId","=","music.UID");
    if(!is_null($date)){
      $GLOBALS['db']->where("CAST(played.playDate as DATE)","=","'" . $date . "'");
    }
    $results = $GLOBALS['db']->groupBy("music." . $key)->orderBy("count desc")->get();
    while($row = mysqli_fetch_assoc($results)){
      $data[] = $row;
    }
    return $data;
  }
  public static function count(){
    return parent::count(self::TABLE);
  }
  public static function countOf($key){
    return parent::countOf(self::TABLE,$key);
  }
  public static function getAll(){
      $data = array();
      $ids = parent::getAll(self::DB,self::TABLE,self::PRIMARYKEY);
      foreach($ids as $id){
          $data[] = new self($id);
      }
      return $data;
  }
}
