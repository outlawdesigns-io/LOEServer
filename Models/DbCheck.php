<?php namespace LOE;

require_once __DIR__ . '/../Base.php';

class DbCheck extends \LOE\Base{

  const TABLE = 'DbCheck';

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
}
