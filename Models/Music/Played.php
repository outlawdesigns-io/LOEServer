<?php namespace LOE\Music;

require_once __DIR__ . '/../../Base.php';
require_once __DIR__ . '/Song.php';

class Played extends \Record{

  const DB = \LOE\Base::DB;
  const PRIMARYKEY = \LOE\Base::PRIMARYKEY;
  const TABLE = 'PlayedSong';

  public $UID;
  public $songId;
  public $ipAddress;
  public $playDate;
  public $userId;

  public function __construct($UID = null){
    parent::__construct(self::DB,self::TABLE,self::PRIMARYKEY,$UID);
  }
  public static function search($key,$value){
    $data = array();
    $ids = parent::_search(self::DB,static::TABLE,static::PRIMARYKEY,$key,$value);
    foreach($ids as $id){
        $data[] = new self($id);
    }
    return $data;
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
  public static function dailyAverage(){
    $total = 0;
    $days = 0;
    $results = $GLOBALS['db']
      ->database(self::DB)
      ->table(self::TABLE)
      ->select("cast(playDate as date) as date,count(*) as total")
      ->groupBy("cast(playDate as date)")
      ->get();
    while($row = mysqli_fetch_assoc($results)){
      $days++;
      $total += $row['total'];
    }
    return $total / $days;
  }
}
