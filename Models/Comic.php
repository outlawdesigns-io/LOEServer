<?php namespace LOE;

require_once __DIR__ . '/../LoeBase.php';

class Comic extends LoeBase{
  const TABLE = 'comic';

  public $UID;
  public $issue_number;
  public $issue_titile;
  public $issue_cover_date;
  public $series_title;
  public $series_start_year;
  public $series_end_year;
  public $publisher;
  public $story_arc;
  public $issue_description;
  public $series_description;
  public $issue_type;
  public $file_path;

  public function __construct($UID = null){
      parent::__construct(self::DB,self::TABLE,self::PRIMARYKEY,$UID);
      $this->file_path = $this->_cleanFilePath($this->file_path);
      $this->_cleanProperties();
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
    return parent::count(self::DB,self::TABLE);
  }
  public static function countOf($key){
    return parent::countOf(self::DB,self::TABLE,$key);
  }
}
