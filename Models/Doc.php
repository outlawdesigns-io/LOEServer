<?php namespace LOE;

require_once __DIR__ . '/../LoeBase.php';

class Doc extends LoeBase{

    const TABLE = 'docs';

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
    public static function getAll(){
        $data = array();
        $ids = parent::getAll(self::DB,self::TABLE,self::PRIMARYKEY);
        foreach($ids as $id){
            $data[] = new self($id);
        }
        return $data;
    }
    public static function count(){
      return parent::count(self::TABLE);
    }
    public static function countOf($key){
      return parent::countOf(self::TABLE,$key);
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
