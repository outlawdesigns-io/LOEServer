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
    public static function recordExists($absolutePath){
      $results = $GLOBALS['db']
        ->database(self::DB)
        ->table(self::TABLE)
        ->select(self::PRIMARYKEY)
        ->where("file_path","=","'" . $absolutePath . "'")
        ->get();
      if(!mysqli_num_rows($results)){
        return false;
      }
      return true;
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
