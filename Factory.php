<?php namespace LOE;

require_once __DIR__ . '/Models/Movie.php';
require_once __DIR__ . '/Models/Episode.php';
require_once __DIR__ . '/Models/Song.php';
require_once __DIR__ . '/Models/Doc.php';
require_once __DIR__ . '/Models/Anime.php';
require_once __DIR__ . '/Models/Comic.php';
require_once __DIR__ . '/Processes/Scanners/HoldingBay/MovieScanner.php';
require_once __DIR__ . '/Processes/Scanners/HoldingBay/TvScanner.php';
require_once __DIR__ . '/Processes/Scanners/HoldingBay/MusicScanner.php';
require_once __DIR__ . '/Processes/Processors/HoldingBay/SongProcessor.php';
require_once __DIR__ . '/Processes/Processors/HoldingBay/MovieProcessor.php';
require_once __DIR__ . '/Processes/Processors/HoldingBay/EpisodeProcessor.php';

class LoeFactory{

    public static function create($type,$UID = null){
        switch (strtolower($type)){
            case "movies":
                $obj = new Movie($UID);
                break;
            case "tv":
                $obj = new Episode($UID);
                break;
            case "music":
                $obj = new Song($UID);
                break;
            case "docs":
                $obj = new Doc($UID);
                break;
            case "anime":
                $obj = new Anime($UID);
                break;
            case "comic":
                $obj = new Comic($UID);
                break;
            default:
                throw new \Exception('Invalid Object Type');
        }
        return $obj;
    }
    public static function createScanner($type){
        $obj = null;
        switch(strtolower($type)){
            case 'movies':
                $obj = new MovieScanner();
                break;
            case 'tv':
                $obj = new TvScanner();
                break;
            case 'music':
                $obj = new MusicScanner();
                break;
            default:
                throw new \Exception('Invalid Object Type');
        }
        return $obj;
    }
    public static function createProcessor($type,$inputObj){
        $obj = null;
        switch(strtolower($type)){
            case 'movies':
                $obj = new MovieProcessor($inputObj);
                break;
            case 'tv':
                $obj = new EpisodeProcessor($inputObj);
                break;
            case 'music':
                $obj = new SongProcessor($inputObj);
                break;
            default:
                throw new \Exception('Invalid Object Type');
        }
        return $obj;
    }
   public static function browse($table,$key){
       $data = array();
       $ids = \Record::browse(LoeBase::DB,$table,$key);
   }
   public static function search($table,$key,$value){
       $data = array();
       if($table == Movie::TABLE && $key == "genre"){
         $ids1 = \Record::search(LoeBase::DB,$table,LoeBase::PRIMARYKEY,$key,$value);
         $ids2 = \Record::search(LoeBase::DB,$table,LoeBase::PRIMARYKEY,"genre2",$value);
         $ids3 = \Record::search(LoeBase::DB,$table,LoeBase::PRIMARYKEY,"genre3",$value);
         $ids = array_merge($ids1,$ids2);
         $ids = array_merge($ids3,$ids);
       }else{
         $ids = \Record::search(LoeBase::DB,$table,LoeBase::PRIMARYKEY,$key,$value);
       }
       foreach($ids as $id){
         $data[] = self::create($table,$id);
       }
       return $data;
   }
   public static function recent($table,$limit){
       $data = array();
       $ids = \Record::getRecent(LoeBase::DB,$table,LoeBase::PRIMARYKEY,$limit);
       foreach($ids as $id){
         $data[] = self::create($table,$id);
       }
       return $data;
   }
}
