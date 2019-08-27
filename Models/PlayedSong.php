<?php namespace LOE;

require_once __DIR__ . '/../LoeBase.php';

class PlayedSong extends LoeBase{

  const TABLE = 'PlayedSong';

  public $UID;
  public $songId;
  public $playDate;

  public function __construct($UID = null){
    parent::__construct(self::DB,self::TABLE,self::PRIMARYKEY,$UID);
  }
}
