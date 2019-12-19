<?php namespace LOE\Comic;

require_once __DIR__ . '/../../Base.php';

class Comic extends \LOE\Base{

  const TABLE = 'Comic';

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
}
