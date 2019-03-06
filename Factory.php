<?php namespace LOE;

require_once __DIR__ . '/Models/Movie.php';
require_once __DIR__ . '/Models/Episode.php';
require_once __DIR__ . '/Models/Song.php';
require_once __DIR__ . '/Models/Doc.php';
require_once __DIR__ . '/Models/Anime.php';
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
//    public static function browse($table,$key){
//        return Record::browse($table,$key);
//    }
//    public static function search($table,$key,$value){
//        return Record::search($table,$key,$value);
//    }
//    public static function recent($table,$limit){
//        return Record::getRecent($table,$limit);
//    }
}
