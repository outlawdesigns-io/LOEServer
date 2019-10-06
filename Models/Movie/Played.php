<?php namespace LOE\Movie;

require_once __DIR__ . '/../../Base.php';

class Played extends \LOE\Base{

  const TABLE = 'PlayedMovie';

  public $UID;
  public $movieId;
  public $ipAddress;
  public $playDate;

  public function __construct($UID = null){
    parent::__construct(self::DB,self::TABLE,self::PRIMARYKEY,$UID);
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
