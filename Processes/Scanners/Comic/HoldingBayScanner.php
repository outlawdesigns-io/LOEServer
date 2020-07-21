<?php namespace LOE\Comic;

require_once __DIR__ . '/../HoldingBayScanner.php';

class HoldingBayScanner extends \LOE\HoldingBayScanner{

    public $albums = array();
    public $artists = array();
    public $unknownAlbum = array();
    public $unknownArtist = array();

    public function __construct($model){
      parent::__construct($model);
    }
}
