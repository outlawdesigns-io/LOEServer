<?php namespace LOE;

require_once __DIR__ . '/../Base.php';

class Model extends \LOE\Base{

  const TABLE = 'Model';

  public $UID;
  public $label;
  public $fsRoot;
  public $holdingBayRoot;
  public $fileExtensions = array();
  public $namespace;

  public function __construct($UID = null){
    parent::__construct(self::DB,self::TABLE,self::PRIMARYKEY,$UID);
  }
  public static function getByLabel($label){
    $results = $GLOBALS['db']
      ->database(self::DB)
      ->table(self::TABLE)
      ->select(self::PRIMARYKEY)
      ->where('label','=',"'" . $label . "'")
      ->get();
    if(!mysqli_num_row($results)){
      throw new \Exception('Invalid Model Label');
    }
    while($row = mysqli_fetch_assoc($results)){
      $id = $row[self::PRIMARYKEY];
    }
    return new self($id);
  }
}
