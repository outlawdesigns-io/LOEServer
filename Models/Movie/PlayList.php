<?php namespace LOE\Movie;

require_once __DIR__ . '/../../Base.php';

class PlayList extends \LOE\Base{

  const TABLE = 'MoviePlayList';

  public $UID;
  public $UserId;
  public $Label;
  public $MovieIds = array();
  public $created_date;

  public function __construct($UID = null){
    parent::__construct(self::DB,self::TABLE,self::PRIMARYKEY,$UID);
  }
  public static function count(){
    return parent::count(self::TABLE);
  }
  public static function countOf($key){
    return parent::countOf(self::TABLE,$key);
  }
  public static function getAll($userId = null){
    $data = array();
    $GLOBALS['db']->database(self::DB)->table(self::TABLE)->select(self::PRIMARYKEY);
    if(!is_null($userId)){
      $GLOBALS['db']->where("UserId","=",$userId);
    }
    $results = $GLOBALS['db']->get();
    while($row = mysqli_fetch_assoc($results)){
      $data[] = new self($row[self::PRIMARYKEY]);
    }
    return $data;
  }
}
