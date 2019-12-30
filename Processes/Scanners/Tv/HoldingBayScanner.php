<?php namespace LOE\Tv;

require_once __DIR__ . '/../../../Factory.php';

class HoldingBayScanner extends \LOE\HoldingBayScanner{

    const LASTSLASHPAT = '/[^\/]+$/';
    const EPNUMPAT = '/S0[0-9]E[0-9]{2}/';
    const SEASONPAT = '/Season\s([0-9]{1,2})/';

    public $episodes = array();
    public $shows = array();

    protected $_episodeCount;

    public function __construct(){
      parent::__construct($model);
      $this->_episodeCount = 0;
      $this->_buildEpisodeData()->_sortShows();
    }
    private function _buildEpisodeData(){
      foreach($this->targetModels as $e){
        $e->UID = $this->episodeCount++;
        if(preg_match(self::LASTSLASHPAT,dirname(dirname($path)),$matches)){
            $e->show_title = $matches[0];
        }
        if(preg_match(self::LASTSLASHPAT,$path,$matches)){
            $e->ep_title = $matches[0];
        }
        if(preg_match(self::LASTSLASHPAT,dirname(dirname(dirname($path))),$matches)){
            $e->genre = $matches[0];
        }
        if(preg_match(self::EPNUMPAT,$path,$matches)){
            $e->ep_number = $matches[0];
        }
        if(preg_match(self::SEASONPAT,$path,$matches)){
            $e->season_number = (int)$matches[1];
        }
        $this->episodes[] = $e;
      }
      return $this;
    }
    private function _sortShows(){
      foreach($this->episodes as $episode){
        $seasonStr = 'Season ' . $episode->season_number;
        $this->shows[$episode->show_title][$seasonStr][] = $episode;
      }
      return $this;
    }
}
