<?php namespace LOE\Doc;

require_once __DIR__ . '/../HoldingBayScanner.php';

class HoldingBayScanner extends \LOE\HoldingBayScanner{

  public $docs = array();

  public function __construct($model){
    parent::__construct($model);
    $this->_gatherData();
  }
  protected function _gatherData(){
    foreach($this->targetModels as $doc){
      $pieces = explode('/',$doc->file_path);
      for($i = 0; $i < count($pieces);$i++){
        if($pieces[$i] == 'docs'){
          $baseKey = $i;
        }
      }
      $doc->category = $pieces[$baseKey + 1];
      $tags = array();
      for($i = $baseKey + 2; $i < count($pieces) -1;$i++){
        $tags[] = $pieces[$i];
      }
      $doc->tags = $tags;
      $titles = $this->_parseTitle(\LOE\Base::WEBROOT . $pathInfo['filename']);
      $doc->title = $titles[0];
      if(count($titles) > 1){
        $doc->subTitle = $titles[1];
      }
      $this->docs[] = $doc;
    }
    return $this;
  }
  protected function _parseTitle($filename){
    $data = array();
    $filename = preg_replace('/_/',' ',$filename);
    if(preg_match('/-/',$filename)){
      $pieces = explode('-',$filename);
      $data[] = ucwords($pieces[0]);
      $data[] = ucwords($pieces[1]);
    }else{
      $data[] = ucwords($filename);
    }
    return $data;
  }
}
