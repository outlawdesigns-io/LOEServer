<?php namespace LOE;

require_once __DIR__ . '/Models/Movie.php';
require_once __DIR__ . '/Models/Episode.php';
require_once __DIR__ . '/Models/Song.php';
require_once __DIR__ . '/Models/Doc.php';
require_once __DIR__ . '/Models/Anime.php';
require_once __DIR__ . '/Models/Comic.php';
require_once __DIR__ . '/Models/PlayedSong.php';
require_once __DIR__ . '/Processes/Scanners/Anime/DbHealthScanner.php';
require_once __DIR__ . '/Processes/Scanners/Movie/DbHealthScanner.php';
require_once __DIR__ . '/Processes/Scanners/Music/DbHealthScanner.php';
require_once __DIR__ . '/Processes/Scanners/Tv/DbHealthScanner.php';
require_once __DIR__ . '/Processes/Scanners/Comic/FsHealthScanner.php';
require_once __DIR__ . '/Processes/Scanners/Movie/FsHealthScanner.php';
require_once __DIR__ . '/Processes/Scanners/Music/FsHealthScanner.php';
require_once __DIR__ . '/Processes/Scanners/Tv/FsHealthScanner.php';
require_once __DIR__ . '/Processes/Scanners/HoldingBay/MovieScanner.php';
require_once __DIR__ . '/Processes/Scanners/HoldingBay/TvScanner.php';
require_once __DIR__ . '/Processes/Scanners/HoldingBay/MusicScanner.php';
require_once __DIR__ . '/Processes/Scanners/HoldingBay/ComicScanner.php';
require_once __DIR__ . '/Processes/Processors/HoldingBay/SongProcessor.php';
require_once __DIR__ . '/Processes/Processors/HoldingBay/MovieProcessor.php';
require_once __DIR__ . '/Processes/Processors/HoldingBay/EpisodeProcessor.php';
require_once __DIR__ . '/Processes/Processors/HoldingBay/ArchiveExtractor.php';
require_once __DIR__ . '/Processes/Processors/Music/PlayCount.php';
require_once __DIR__ . '/Processes/Processors/Music/PlayHistory.php';

class Factory{

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
            case "playedsong";
                $obj = new PlayedSong($UID);
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
            case 'comic':
                $obj = new ComicScanner();
                break;
            default:
                throw new \Exception('Invalid Object Type');
        }
        return $obj;
    }
    public static function createFsScanner($type,$msgTo = null, $authToken = null){
      switch(strtolower($type)){
        case Movie::TABLE:
            $obj = new \LOE\Movie\FsHealthScanner($msgTo,$authToken);
            break;
        case Episode::TABLE:
            $obj = new \LOE\Tv\FsHealthScanner($msgTo,$authToken);
            break;
        case Song::TABLE:
            $obj = new \LOE\Music\FsHealthScanner($msgTo,$authToken);
            break;
        case Comic::TABLE:
            $obj = new \LOE\Comic\FsHealthScanner($msgTo,$authToken);
            break;
        default:
            throw new \Exception('Invalid Object Type');
      }
      return $obj;
    }
    public static function createDbScanner($type,$msgTo = null, $authToken = null){
      switch(strtolower($type)){
        case Movie::TABLE:
            $obj = new \LOE\Movie\DbHealthScanner($msgTo,$authToken);
            break;
        case Episode::TABLE:
            $obj = new \LOE\Tv\DbHealthScanner($msgTo,$authToken);
            break;
        case Song::TABLE:
            $obj = new \LOE\Music\DbHealthScanner($msgTo,$authToken);
            break;
        case Anime::TABLE:
            $obj = new \LOE\Anime\DbHealthScanner($msgTo,$authToken);
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
   public static function updateSongCounts($username,$password){
       return new \LOE\Music\PlayCount($username,$password);
   }
   public static function updatePlayHistory($objType,$username,$password){
       switch(strtolower($objType)){
         case Song::TABLE:
           $obj = new \LOE\Music\PlayHistory($username,$password);
         break;
         default:
           throw new \Exception('Invalid Object Type');
       }
       return $obj;
   }
   public static function authenticate($username,$password){
     return \LOE\Movie\DbHealthScanner::authenticate($username,$password);
   }
   public static function extractArchives($rootDir){
     return new \LOE\HoldingBay\ArchiveExtractor($rootDir);
   }
   public static function count($type){
     if(strtolower($type) == 'movies'){
       $type = 'movie';
     }elseif(strtolower($type) == 'docs'){
       $type = 'doc';
     }elseif(strtolower($type) == 'music'){
       $type = 'song';
     }elseif(strtolower($type) == 'tv'){
       $type = 'episode';
     }
     $key = __NAMESPACE__ . "\\" . ucwords($type);
     return $key::count();
   }
   public static function countOf($type,$key){
     if(strtolower($type) == 'movies'){
       $type = 'movie';
     }elseif(strtolower($type) == 'docs'){
       $type = 'doc';
     }elseif(strtolower($type) == 'music'){
       $type = 'song';
     }elseif(strtolower($type) == 'tv'){
       $type = 'episode';
     }
     $type = __NAMESPACE__ . "\\" . ucwords($type);
     return $type::countOf($key);
   }
}
