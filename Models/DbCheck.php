<?php namespace LOE;

require_once __DIR__ . '/../Base.php';

class DbCheck extends \LOE\Base{

  const TABLE = 'FsCheck';

  public $UID;
  public $modelId;
  public $startTime;
  public $endTime;
  public $runTime;
  public $recordCount;
  public $missingCount;

  public function __construct($UID = null){
    parent::__construct(self::DB,self::TABLE,self::PRIMARYKEY,$UID);
  }
  public static function getAll(){
    $data = array();
    $ids = parent::getAll(self::DB,self::TABLE,self::PRIMARYKEY);
    foreach($ids as $id){
      $data[] = new self($id);
    }
    return $data;
  }
  public static function count(){
    return parent::count(self::TABLE);
  }
  public static function countOf($key){
    return parent::countOf(self::TABLE,$key);
  }
}
