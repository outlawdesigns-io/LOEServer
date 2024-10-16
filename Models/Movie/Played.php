<?php namespace LOE\Movie;

require_once __DIR__ . '/../../Base.php';

class Played extends \Record{

  const DB = \LOE\Base::DB;
  const PRIMARYKEY = \LOE\Base::PRIMARYKEY;
  const TABLE = 'PlayedMovie';

  public $UID;
  public $movieId;
  public $ipAddress;
  public $playDate;

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
  public static function recordExists($movieId,$playDate){
    $results = $GLOBALS['db']
        ->database(self::DB)
        ->table(self::TABLE)
        ->select(self::PRIMARYKEY)
        ->where("movieId","=",$movieId)
        ->andWhere("playDate","=","'" . $playDate . "'")
        ->get();
    if(!mysqli_num_rows($results)){
      return false;
    }
    return true;
  }
}
