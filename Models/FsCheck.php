<?php namespace LOE;

require_once __DIR__ . '/../Base.php';

class FsCheck extends \LOE\Base{

  const TABLE = 'FsCheck';

  public $UID;
  public $modelId;
  public $startTime;
  public $endTime;
  public $runTime;
  public $fileCount;
  public $missingCount;

  public function __construct($UID = null){
    parent::__construct(self::DB,self::TABLE,self::PRIMARYKEY,$UID);
  }
}
