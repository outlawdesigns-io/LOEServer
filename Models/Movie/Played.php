<?php namespace LOE\Movie;

require_once __DIR__ . '/../../Base.php';

class Played extends \Record{
  
  const DB = LOE\Base::DB;
  const PRIMARYKEY = LOE\Base::PRIMARYKEY;
  const TABLE = 'PlayedMovie';

  public $UID;
  public $movieId;
  public $ipAddress;
  public $playDate;

  public function __construct($UID = null){
    parent::__construct(LOE\Base::DB,self::TABLE,LOE\Base::PRIMARYKEY,$UID);
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
