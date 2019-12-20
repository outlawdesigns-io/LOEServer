<?php namespace LOE;

require_once __DIR__ . '/Libs/Record/Record.php';


class Base extends \Record{

    const DB = 'LOE';
    const PRIMARYKEY = 'UID';
    const FILEPATT = '/^.*(?=(\/LOE))/';
    const WEBROOT = '/var/www/html';
    const FILEUNSETERR = 'File path must be set.';

    public function __construct($database, $table, $primaryKey, $id)
    {
        parent::__construct($database, $table, $primaryKey, $id);
    }
    protected function _cleanFilePath($path){
        return html_entity_decode(preg_replace(self::FILEPATT,"",$path));
    }
    protected function _cleanProperties(){
        $reflection = new \ReflectionObject($this);
        $data = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);
        foreach($data as $obj){
            if($obj->name != self::PRIMARYKEY){
              $key = $obj->name;
              $this->$key = html_entity_decode($this->$key);
              $this->$key = utf8_encode($this->$key);
            }
        }
        return $this;
    }
    public function cleanFilePath($path){
      return $this->_cleanFilePath($path);
    }
    public function verifyLocation(){
        if(!isset($this->file_path)){
            throw new \Exception(self::FILEUNSETERR);
        }
        $path = self::WEBROOT . $this->file_path;
        if(!is_file($path)){
            return false;
        }
        return true;
    }
    public function calculateSize(){
        if(!isset($this->file_path)){
            throw new \Exception(self::FILEUNSETERR);
        }
        if($this->verifyLocation()){
            $path = self::WEBROOT . $this->file_path;
            $fileSize = filesize($path);
            return $fileSize;
        }
        return false;
    }
    public static function recordExists($absolutePath){
      $GLOBALS['db']
        ->database(self::DB)
        ->table(static::TABLE)
        ->select(static::PRIMARYKEY)
        ->where("file_path","=","'" . preg_replace("/'/","",$absolutePath) . "'");
      try{
        $results = $GLOBALS['db']->get();
      }catch(\Exception $e){
        echo $e->getMessage() . "\n";
        echo $absolutePath . "\n";
        return false;
      }
      if(!mysqli_num_rows($results)){
        return false;
      }
      return true;
    }
    public static function search($key,$value){
      $data = array();
      $ids = parent::search(self::DB,static::TABLE,static::PRIMARYKEY,$key,$value);
      foreach($ids as $id){
          $data[] = new static($id);
      }
      return $data;
    }
    public static function count(){
      return parent::count(self::DB,static::TABLE);
    }
    public static function countOf($key){
      return parent::countOf(self::DB,static::TABLE,$key);
    }
    public static function getAll(){
      $data = array();
      $ids = parent::getAll(self::DB,static::TABLE,static::PRIMARYKEY);
      foreach($ids as $id){
          $data[] = new static($id);
      }
      return $data;
    }
    public static function recent($limit){
      $data = array();
      $ids = parent::getRecent(self::DB,static::TABLE,$limit);
      foreach($ids as $id){
        $data[] = new static($id);
      }
      return $data;
    }
    public function backup(){
        //todo implement backup solution
    }
}
