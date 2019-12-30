<?php namespace LOE\Movie;

require_once __DIR__ . '/../HoldingBayScanner.php';
require_once __DIR__ . "/../../../Libs/IMDB/Imdb.php";

class HoldingBayScanner extends \LOE\HoldingBayScanner{

    const YEARPATTERN1 = "/\(/";
    const YEARREPLACEMENT1 = "/\((.*)/";
    const YEARREPLACEMENT2 = "/[0-9]{4}(.*)/";
    const YEARPATTERN2 = "/[0-9]{4}/";

    public $movies = array();
    public $exceptions = array();

    public function __construct($model){
      parent::__construct($model);
      $this->_gatherData();
    }
    protected function _gatherData(){
      foreach($this->targetModels as $movie){
        $titleStr = pathinfo(pathinfo($movie->file_path)['dirname'])['basename'];
        if(preg_match(self::YEARPATTERN1,$titleStr,$matches)){
          $titleStr = preg_replace(self::YEARREPLACEMENT1,'',$titleStr);
        }elseif(preg_match(self::YEARPATTERN2,$titleStr,$matches)){
          $titleStr = preg_replace(self::YEARREPLACEMENT2,'',$titleStr);
        }
        try{
          $searchResult = \Imdb::search($titleStr);
        }catch(\Exception $e){
          $searchResult = false;
        }
        if(!$searchResult){
          $this->exceptions[] = $titleStr;
        }else{
          $genres = explode(',',$searchResult->Genre);
          $movie->title = $searchResult->Title;
          $movie->relyear = $searchResult->Year;
          $movie->rating = $searchResult->Rated;
          $movie->genre = $genres[0];
          $movie->genre2 = (isset($genres[1])) ? $genres[1] : null;
          $movie->genre3 = (isset($genres[2])) ? $genres[2] : null;
          $movie->director = $searchResult->Director;
          $movie->description = $searchResult->Plot;
          $movie->run_time = $searchResult->Runtime;
          $movie->cover_path = $searchResult->Poster;
          $this->movies[] = $movie;
        }
      }
      return $this;
    }
}
