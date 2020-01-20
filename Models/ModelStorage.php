<?php namespace LOE;

require_once __DIR__ . '/../Base.php';

class ModelStorage extends \LOE\Base{

  const TABLE = 'ModelStorage';

  public $UID;
  public $modelId;
  public $created_date;
  public $fs_size;
  public $fs_unit;
  public $hb_size;
  public $hb_unit;


  public function __construct($UID = null){
    parent::__construct(self::DB,self::TABLE,self::PRIMARYKEY,$UID);
  }
}
