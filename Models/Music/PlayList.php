<?php namespace LOE\Music;

require_once __DIR__ . '/../../Base.php';

class PlayList extends \LOE\Base{

  const TABLE = 'SongPlayList';

  public $UID;
  public $UserId;
  public $Label;
  public $SongIds = array();
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
  public static function getAll(){
      $data = array();
      $ids = parent::getAll(self::DB,self::TABLE,self::PRIMARYKEY);
      foreach($ids as $id){
          $data[] = new self($id);
      }
      return $data;
  }
}
