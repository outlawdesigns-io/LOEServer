<?php namespace LOE\Music;

require_once __DIR__ . '/../../../Factory.php';

ini_set('max_execution_time', 300);

<<<<<<< HEAD:Processes/Scanners/HoldingBay/MusicScanner.php
class MusicScanner extends FsScanner{
=======
class HoldingBayScanner extends \LOE\FsScanner{
>>>>>>> development:Processes/Scanners/Music/HoldingBayScanner.php

    const ROOTDIR = "/LOE/holding_bay/music";

    public $songs = array();
    public $possibleCovers = array();
    public $extraFiles = array();
    public $albums = array();
    public $artists = array();
    private $unknownAlbum = array();
    private $unknownArtist = array();

    private $results = array();

    public function __construct(){
<<<<<<< HEAD:Processes/Scanners/HoldingBay/MusicScanner.php
        $this->_scanForever(\LOE\LoeBase::WEBROOT . self::ROOTDIR)
=======
        $this->songCount = 0;
        $this->_scanForever(\LOE\Base::WEBROOT . self::ROOTDIR)
>>>>>>> development:Processes/Scanners/Music/HoldingBayScanner.php
            ->_getTags()
            ->_sortAlbums()
            ->_sortArtists();
    }
    protected function _interpretFile($absolutePath){
      $fileInfo = pathinfo($absolutePath);
<<<<<<< HEAD:Processes/Scanners/HoldingBay/MusicScanner.php
      $song = new Song();
=======
      $song = \LOE\Factory::createModel(Song::TABLE);
>>>>>>> development:Processes/Scanners/Music/HoldingBayScanner.php
      if($fileInfo['extension'] == 'mp3'){
        $song->file_path = $song->cleanFilePath($absolutePath);
        $this->songs[] = $song;
      }elseif(strtolower($fileInfo['extension']) == 'jpg'){
        $this->possibleCovers[] = $song->cleanFilePath($absolutePath);
      }else{
        $this->extraFiles[] = $song->cleanFilePath($absolutePath);
      }
      return $this;
    }
    private function _getTags(){
        $i = 0;
        foreach($this->songs as $song){
            $song->UID = $i++;
            $tags = $song->getMp3Tags();
            foreach($tags as $key=>$value){
                $song->$key = html_entity_decode($value);
            }
        }
        return $this;
    }
    private function _sortAlbums(){
        foreach($this->songs as $song){
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
