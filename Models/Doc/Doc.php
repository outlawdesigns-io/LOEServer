<?php namespace LOE\Doc;

require_once __DIR__ . '/../../Base.php';

class Doc extends \LOE\Base{

    const TABLE = 'Doc';

    public $UID;
    public $title;
    public $subTitle;
    public $author;
    public $pub_date;
    public $pub_type;
    public $created_date;
    public $category;
    public $access_level;
    public $file_path;
    public $tags = array();

    public function __construct($UID = null){
        parent::__construct(self::DB,self::TABLE,self::PRIMARYKEY,$UID);
        $this->file_path = $this->_cleanFilePath($this->file_path);
        $this->_cleanProperties();
    }
    protected function _parseTags(){
        $tagStr = '';
        for($i = 0; $i < count($this->tags);$i++){
            if($i == count($this->tags) - 1){
                $tagStr .= $this->tags[$i];
            }else{
                $tagStr .= $this->tags[$i] . ',';
            }
        }
        return $tagStr;
    }
}
