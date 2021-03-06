<?php namespace LOE\Anime;

require_once __DIR__ . '/../../Base.php';

class Anime extends \LOE\Base{

    const TABLE = 'Anime';

    public $UID;
    public $show_title;
    public $japanese_title;
    public $type;
    public $season;
    public $ep_number;
    public $ep_title;
    public $run_time;
    public $rating;
    public $genre;
    public $genre2;
    public $genre3;
    public $description;
    public $release_date;
    public $cover_path;
    public $file_path;

    public function __construct($UID = null){
        parent::__construct(self::DB,self::TABLE,self::PRIMARYKEY,$UID);
        $this->file_path = $this->_cleanFilePath($this->file_path);
        $this->cover_path = $this->_cleanFilePath($this->cover_path);
        $this->_cleanProperties();
    }
}
