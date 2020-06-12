<?php namespace LOE\Music;

require_once __DIR__ . '/../../Base.php';

class Script extends \LOE\Base{

    const TABLE = 'SongScript';

    public $UID;
    public $songId;
    public $body;

    public function __construct($UID = null){
        parent::__construct(self::DB,self::TABLE,self::PRIMARYKEY,$UID);
        $this->_cleanProperties();
    }
}
