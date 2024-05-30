<?php namespace LOE\Comic;

require_once __DIR__ . '/../../Base.php';

class Played extends \Record{
  
  const DB = LOE\Base::DB;
  const PRIMARYKEY = LOE\Base::PRIMARYKEY;
  const TABLE = 'PlayedAnime';

  public $UID;
  public $comicId;
  public $ipAddress;
  public $playDate;

  public function __construct($UID = null){
    parent::__construct(self::DB,self::TABLE,self::PRIMARYKEY,$UID);
  }
  public static function recordExists($animeId,$playDate){
    $results = $GLOBALS['db']
        ->database(self::DB)
        ->table(self::TABLE)
        ->select(self::PRIMARYKEY)
        ->where("comicId","=",$animeId)
        ->andWhere("playDate","=","'" . $playDate . "'")
        ->get();
    if(!mysqli_num_rows($results)){
      return false;
    }
    return true;
  }
}
