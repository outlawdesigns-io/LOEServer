<?php namespace LOE\Tv;

require_once __DIR__ . '/../../Base.php';

class Rating extends \LOE\Base{

  const TABLE = 'EpisodeRating';

  public $UID;
  public $episodeId;
  public $rating;
  public $userId;
  public $created_date;

  public function __construct($UID = null){
    parent::__construct(self::DB,self::TABLE,self::PRIMARYKEY,$UID);
  }

}
