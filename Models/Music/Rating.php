<?php namespace LOE\Music;

require_once __DIR__ . '/../../Base.php';

class Rating extends \LOE\Base{

  const TABLE = 'SongRating';

  public $UID;
  public $songId;
  public $rating;
  public $userId;
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
      $GLOBALS['db']->where("userId","=",$userId);
    }
    $results = $GLOBALS['db']->get();
    while($row = mysqli_fetch_assoc($results)){
      $data[] = new self($row[self::PRIMARYKEY]);
    }
    return $data;
  }

}
