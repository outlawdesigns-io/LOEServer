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
    public static function count($objType){
      return parent::count(self::DB,$objType);
    }
    public static function countOf($objType,$key){
      return parent::countOf(self::DB,$objType,$key);
    }
    public function backup(){
        //todo implement backup solution
    }
}
