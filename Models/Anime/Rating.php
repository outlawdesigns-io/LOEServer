<?php namespace LOE\Anime;

require_once __DIR__ . '/../../Base.php';

class Rating extends \LOE\Base{

  const TABLE = 'AnimeRating';

  public $UID;
  public $animeId;
  public $rating;
  public $userId;
  public $created_date;

  public function __construct($UID = null){
    parent::__construct(self::DB,self::TABLE,self::PRIMARYKEY,$UID);
  }

}
