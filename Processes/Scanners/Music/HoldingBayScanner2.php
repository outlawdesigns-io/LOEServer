<?php namespace LOE\Music;

require_once __DIR__ . '/../../../Factory.php';

class HoldingBayScanner extends \LOE\HoldingBayScanner{

    public $albums = array();
    public $artists = array();
    public $unknownAlbum = array();
    public $unknownArtist = array();

    public function __construct($model){
      parent::__construct($model);
    }
    private function _getTags(){
        $i = 0;
        foreach($this->targetFiles as $song){
            $song->UID = $i++;
            $tags = $song->getMp3Tags();
            foreach($tags as $key=>$value){
                $song->$key = html_entity_decode($value);
            }
        }
        return $this;
    }
    private function _sortAlbums(){
        foreach($this->targetFiles as $song){
            if(empty($song->album) || is_null($song->album)){
                $this->unknownAlbum[] = $song;
            }else{
                $this->albums[$song->album][] = $song;
            }
        }
        return $this;
    }
    private function _sortArtists(){
        foreach($this->albums as $album){
            if(empty($album[0]->artist) || is_null($album[0]->artist)){
                $this->unknownArtist[] = $album;
            }else{
                $this->artists[$album[0]->artist][$album[0]->album] = $album;
            }
        }
        return $this;
    }
}
