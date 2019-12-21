<?php namespace LOE;

require_once __DIR__ . '/../Base.php';

class PlayCountRun extends \LOE\Base{

  const TABLE = 'PlayCountRun';

  public $UID;
  public $modelId;
  public $startTime;
  public $endTime;
  public $runTime;
  public $searchResultCount;
  public $exceptionCount;
  public $processedCount;
  public $exceptionCaught;
  public $exceptionMessage;

  public function __construct($UID = null){
    parent::__construct(self::DB,self::TABLE,self::PRIMARYKEY,$UID);
  }
}
