<?php namespace LOE;

require_once __DIR__ . '/../LoeBase.php';
require_once __DIR__ . '/../Libs/Mp3Reader/Mp3Reader.php';

class Song extends LoeBase{

    const TABLE = 'music';
    const PRIMARYKEY = 'UID';

    public $UID;
    public $title;
    public $artist;
    public $album;
    public $year;
    public $track_number;
    public $genre;
    public $band;
    public $length;
    public $publisher;
    public $bpm;
    public $feat;
    public $cover_path;
    public $file_path;
    public $play_count;
    public $last_play;
    public $created_date;
    public $artist_country;

    public function __construct($UID = null){
        parent::__construct(self::DB,self::TABLE,self::PRIMARYKEY,$UID);
        $this->file_path = $this->_cleanFilePath($this->file_path);
        $this->cover_path = $this->_cleanFilePath($this->cover_path);
        $this->_cleanProperties();
    }
    public function getMp3Tags(){
        if(isset($this->created_date)){
            $path = self::WEBROOT . $this->file_path;
        }else{
            $path = $this->file_path;
        }
        $reader = new \Mp3Reader();
        $reader->fileName = $path;
        try{
          $reader->getTags();
        }catch(\Exception $e){
          throw new \Exception($e->getMessage());
        }
        return $reader->tagData;
    }
    public function validateTags(){
        $data = array();
        $id3Data = $this->getMp3Tags();
        foreach($id3Data as $property=>$value){
          if(!property_exists($this,$property)){
            throw new \Exception('Unknown Property: ' . $property);
          }
          if($this->$property != $value){
            $data[$property] = $value;
          }
        }
        return $data;
    }
    protected function _writeMp3Tags(){
        $path = self::WEBROOT . $this->file_path;
        $reader = new \Mp3Reader();
        $reader->fileName = $path;
        $reader->constructTags($this->title,$this->artist,$this->album,$this->year,$this->genre,"",$this->track_number);
        if(!$reader->writeTags()){
            return false;
        }
        return true;
    }
    public static function getAll(){
        $data = array();
        $ids = parent::getAll(self::DB,self::TABLE,self::PRIMARYKEY);
        foreach($ids as $id){
            $data[] = new self($id);
        }
        return $data;
    }
    public static function search($key,$value){
        $data = array();
        $ids = parent::search(self::DB,self::TABLE,self::PRIMARYKEY,$key,$value);
        foreach($ids as $id){
            $data[] = new self($id);
        }
        return $data;
    }
}
