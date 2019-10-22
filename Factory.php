<?php namespace LOE;

require_once __DIR__ . '/Models/Movie/Movie.php';
require_once __DIR__ . '/Models/Movie/Played.php';
require_once __DIR__ . '/Models/Movie/Rating.php';
require_once __DIR__ . '/Models/Tv/Episode.php';
require_once __DIR__ . '/Models/Tv/Played.php';
require_once __DIR__ . '/Models/Tv/Rating.php';
require_once __DIR__ . '/Models/Music/Song.php';
require_once __DIR__ . '/Models/Music/Played.php';
require_once __DIR__ . '/Models/Music/Rating.php';
require_once __DIR__ . '/Models/Music/PlayList.php';
require_once __DIR__ . '/Models/Doc/Doc.php';
require_once __DIR__ . '/Models/Doc/Rating.php';
require_once __DIR__ . '/Models/Anime/Anime.php';
require_once __DIR__ . '/Models/Anime/Played.php';
require_once __DIR__ . '/Models/Anime/Rating.php';
require_once __DIR__ . '/Models/Comic/Comic.php';
require_once __DIR__ . '/Models/Comic/Rating.php';
require_once __DIR__ . '/Processes/Scanners/Anime/DbHealthScanner.php';
require_once __DIR__ . '/Processes/Scanners/Comic/FsHealthScanner.php';
require_once __DIR__ . '/Processes/Scanners/Comic/HoldingBayScanner.php';
require_once __DIR__ . '/Processes/Scanners/Movie/FsHealthScanner.php';
require_once __DIR__ . '/Processes/Scanners/Movie/HoldingBayScanner.php';
require_once __DIR__ . '/Processes/Scanners/Movie/DbHealthScanner.php';
require_once __DIR__ . '/Processes/Scanners/Music/FsHealthScanner.php';
require_once __DIR__ . '/Processes/Scanners/Music/DbHealthScanner.php';
require_once __DIR__ . '/Processes/Scanners/Music/HoldingBayScanner.php';
require_once __DIR__ . '/Processes/Scanners/Tv/FsHealthScanner.php';
require_once __DIR__ . '/Processes/Scanners/Tv/HoldingBayScanner.php';
require_once __DIR__ . '/Processes/Scanners/Tv/DbHealthScanner.php';
require_once __DIR__ . '/Processes/Processors/Music/HoldingBayProcessor.php';
require_once __DIR__ . '/Processes/Processors/Music/PlayCount.php';
require_once __DIR__ . '/Processes/Processors/Music/PlayHistory.php';
require_once __DIR__ . '/Processes/Processors/Movie/HoldingBayProcessor.php';
require_once __DIR__ . '/Processes/Processors/Tv/HoldingBayProcessor.php';
require_once __DIR__ . '/Processes/Processors/HoldingBay/ArchiveExtractor.php';


class Factory{

  const BADOBJ = 'Invalid Object Type';

  public static function createModel($type,$UID = null){
    switch(ucwords($type)){
      case \LOE\Movie\Movie::TABLE:
        $obj = new \LOE\Movie\Movie($UID);
        break;
      case \LOE\Movie\Played::TABLE:
        $obj = new \LOE\Movie\Played($UID);
        break;
      case \LOE\Movie\Rating::TABLE:
        $obj = new \LOE\Movie\Rating($UID);
        break;
      case \LOE\Music\Song::TABLE:
        $obj = new \LOE\Music\Song($UID);
        break;
      case \LOE\Music\Played::TABLE:
        $obj = new \LOE\Music\Played($UID);
        break;
      case \LOE\Music\Rating::TABLE:
        $obj = new \LOE\Music\Rating($UID);
        break;
      case \LOE\Music\PlayList::TABLE:
        $obj = new \LOE\Music\PlayList($UID);
        break;
      case \LOE\Tv\Episode::TABLE:
        $obj = new \LOE\Tv\Episode($UID);
        break;
      case \LOE\Tv\Played::TABLE:
        $obj = new \LOE\Tv\Played($UID);
        break;
      case \LOE\Tv\Rating::TABLE:
        $obj = new \LOE\Tv\Rating($UID);
        break;
      case \LOE\Anime\Anime::TABLE:
        $obj = new \LOE\Anime\Anime($UID);
        break;
      case \LOE\Anime\Played::TABLE:
        $obj = new \LOE\Anime\Played($UID);
        break;
      case \LOE\Anime\Rating::TABLE:
        $obj = new \LOE\Anime\Rating($UID);
        break;
      case \LOE\Doc\Doc::TABLE:
        $obj = new \LOE\Doc\Doc($UID);
        break;
      case \LOE\Doc\Rating::TABLE:
        $obj = new \LOE\Doc\Rating($UID);
        break;
      case \LOE\Comic\Comic::TABLE:
        $obj = new \LOE\Comic\Comic($UID);
        break;
      case \LOE\Comic\Rating::TABLE:
        $obj = new \LOE\Comic\Rating($UID);
      default:
        throw new \Exception(self::BADOBJ);
      }
      return $obj;
    }
    public static function createHoldingBayScanner($type){
      $obj = null;
      switch(ucwords($type)){
          case \LOE\Movie\Movie::TABLE:
          $obj = new \LOE\Movie\HoldingBayScanner();
        break;
        case \LOE\Tv\Episode::TABLE:
          $obj = new \LOE\Tv\HoldingBayScanner();
          break;
        case \LOE\Music\Song::TABLE:
          $obj = new \LOE\Music\HoldingBayScanner();
          break;
        case \LOE\Comic\Comic::TABLE:
          $obj = new \LOE\Comic\HoldingBayScanner();
          break;
        default:
          throw new \Exception(self::BADOBJ);
      }
      return $obj;
    }
    public static function createFsScanner($type,$msgTo = null, $authToken = null){
      switch(ucwords($type)){
        case \LOE\Movie\Movie::TABLE:
            $obj = new \LOE\Movie\FsHealthScanner($msgTo,$authToken);
            break;
        case \LOE\Tv\Episode::TABLE:
            $obj = new \LOE\Tv\FsHealthScanner($msgTo,$authToken);
            break;
        case \LOE\Music\Song::TABLE:
            $obj = new \LOE\Music\FsHealthScanner($msgTo,$authToken);
            break;
        case \LOE\Comic\Comic::TABLE:
            $obj = new \LOE\Comic\FsHealthScanner($msgTo,$authToken);
            break;
        default:
          throw new \Exception(self::BADOBJ);
      }
      return $obj;
    }
    public static function createDbScanner($type,$msgTo = null, $authToken = null){
      switch(ucwords($type)){
        case \LOE\Movie\Movie::TABLE:
            $obj = new \LOE\Movie\DbHealthScanner($msgTo,$authToken);
            break;
        case \LOE\Tv\Episode::TABLE:
            $obj = new \LOE\Tv\DbHealthScanner($msgTo,$authToken);
            break;
        case \LOE\Music\Song::TABLE:
            $obj = new \LOE\Music\DbHealthScanner($msgTo,$authToken);
            break;
        case \LOE\Anime\Anime::TABLE:
            $obj = new \LOE\Anime\DbHealthScanner($msgTo,$authToken);
            break;
        default:
            throw new \Exception(self::BADOBJ);
      }
      return $obj;
    }
    public static function createHoldingBayProcessor($type,$inputObj){
        $obj = null;
        switch(ucwords($type)){
            case \LOE\Movie\Movie::TABLE:
                $obj = new \LOE\Movie\HoldingBayProcessor($inputObj);
                break;
            case \LOE\Tv\Episode::TABLE:
                $obj = new \LOE\Tv\HoldingBayProcessor($inputObj);
                break;
            case \LOE\Music\Song::TABLE:
                $obj = new \LOE\Music\HoldingBayProcessor($inputObj);
                break;
            default:
                throw new \Exception(self::BADOBJ);
        }
        return $obj;
    }
   public static function browse($table,$key){
       $data = array();
       $ids = \Record::browse(Base::DB,$table,$key);
       return $data;
   }
   public static function search($table,$key,$value){
       $data = array();
       if($table == \LOE\Movie\Movie::TABLE && $key == "genre"){
         $ids1 = \Record::search(Base::DB,$table,Base::PRIMARYKEY,$key,$value);
         $ids2 = \Record::search(Base::DB,$table,Base::PRIMARYKEY,"genre2",$value);
         $ids3 = \Record::search(Base::DB,$table,Base::PRIMARYKEY,"genre3",$value);
         $ids = array_merge($ids1,$ids2);
         $ids = array_merge($ids3,$ids);
       }else{
         $ids = \Record::search(Base::DB,$table,Base::PRIMARYKEY,$key,$value);
       }
       foreach($ids as $id){
         $data[] = self::createModel($table,$id);
       }
       return $data;
   }
   public static function recent($table,$limit){
       $data = array();
       $ids = \Record::getRecent(Base::DB,$table,Base::PRIMARYKEY,$limit);
       foreach($ids as $id){
         $data[] = self::createModel($table,$id);
       }
       return $data;
   }
   public static function updateSongCounts($username,$password){
       return new \LOE\Music\PlayCount($username,$password);
   }
   public static function updatePlayHistory($objType,$username,$password){
       switch(ucwords($objType)){
         case \LOE\Music\Song::TABLE:
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
   public static function count($table){
     return \Record::count(Base::DB,$table);
   }
   public static function countOf($table,$key){
     return \Record::countOf(Base::DB,$table,$key);
   }
}
