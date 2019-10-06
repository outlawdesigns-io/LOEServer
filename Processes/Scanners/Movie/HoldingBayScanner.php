<?php namespace LOE\Movie;

require_once __DIR__ . '/../../../Factory.php';
require_once __DIR__ . "/../../../Libs/IMDB/Imdb.php";
require_once __DIR__ . '/../FsScanner.php';

class HoldingBayScanner extends \LOE\FsScanner{

    const ROOTDIR = '/LOE/holding_bay/movies';
    const WEBROOTPATTERN = "/\/var\/www\/html/";
    const YEARPATTERN1 = "/\(/";
    const YEARREPLACEMENT1 = "/\((.*)/";
    const YEARREPLACEMENT2 = "/[0-9]{4}(.*)/";
    const YEARPATTERN2 = "/[0-9]{4}/";

    public $movies = array();
    private $movieDirs = array();
    private $movieFiles = array();
    private $titles = array();
    private $xmlFiles = array();
    private $knownExtensions = array("mp4","MP4","avi","AVI","mkv","MKV");

    public function __construct(){
        $this->_scanForever(\LOE\Base::WEBROOT . self::ROOTDIR);
    }
    protected function _interpretFile($absolutePath){
      if(in_array(pathinfo($absolutePath)['extension'],$this->knownExtensions)){
        $this->_parseResult($absolutePath);
      }
      return $this;
    }
    protected function _parseResult($result){
        $titleStr = pathinfo(pathinfo($result)['dirname'])['basename'];
        if(preg_match(self::YEARPATTERN1,$titleStr,$matches)){
            $titleStr = preg_replace(self::YEARREPLACEMENT1,'',$titleStr);
        }elseif(preg_match(self::YEARPATTERN2,$titleStr,$matches)){
            $titleStr = preg_replace(self::YEARREPLACEMENT2,'',$titleStr);
        }
        try{
          $searchResult = \Imdb::search($titleStr);
        }catch(\Exception $e){
          $this->exceptions[] = $titleStr . " " . $e->getMessage();
          $searchResult = false;
        }
        if(!$searchResult){
            $this->exceptions[] = $titleStr;
        }else{
            $genres = explode(",",$searchResult->Genre);
            $movie = \LOE\Factory::createModel(\LOE\Movie\Movie::TABLE);
            $movie->title = $searchResult->Title;
            $movie->relyear = $searchResult->Year;
            $movie->rating = $searchResult->Rated;
            $movie->genre = $genres[0];
            $movie->genre2 = (isset($genres[1])) ? $genres[1] : null;
            $movie->genre3 = (isset($genres[2])) ? $genres[2] : null;
            $movie->file_path = preg_replace(self::WEBROOTPATTERN,'',$result);
            $movie->director = $searchResult->Director;
            $movie->description = $searchResult->Plot;
            $movie->run_time = $searchResult->Runtime;
            $movie->cover_path = $searchResult->Poster;
            $this->movies[] = $movie;
        }
        return $this;
    }
}
