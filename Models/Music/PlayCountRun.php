<?php namespace LOE\Music;

require_once __DIR__ . '/../../Base.php';

class PlayCountRun extends \LOE\Base{

  const TABLE = 'PlayCountRun';

  public $UID;
  public $model;
  public $startTime;
  public $endTime;
  public $runTime;
  public $exceptionCount;
  public $processedCount;

  public function __construct($UID = null){
    parent::__construct(self::DB,self::TABLE,self::PRIMARYKEY,$UID);
  }
  
}
