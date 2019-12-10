<?php namespace LOE\Doc;

require_once __DIR__ . '/../../../Factory.php';

class AutoInsert extends \LOE\FsScanner{

  const ROOTDIR = '/LOE/Documents';

  public $docs = array();
  public static $fileTypes = array(
    'pdf',
    'chm',
    'txt',
    'epub',
    'log'
  );

  public function __construct(){
    $this->_scanForever(\LOE\Base::WEBROOT . self::ROOTDIR);
  }

  protected function _interpretFile($absolutePath){
    $pathInfo = pathinfo($absolutePath);
    if(in_array($pathInfo['extension'],self::$fileTypes)){
        $doc = \LOE\Factory::createModel(Doc::TABLE);
        $doc->file_path = $absolutePath;
        $pieces = explode('/',$absolutePath);
        for($i = 0; $i < count($pieces);$i++){
            if($pieces[$i] == 'Documents'){
                $baseKey = $i;
            }
        }
        $doc->category = $pieces[$baseKey + 1];
        $tags = array();
        for($i = $baseKey + 2; $i < count($pieces) -1;$i++){
            $tags[] = $pieces[$i];
        }
        $doc->tags = $tags;
        $titles = $this->_parseTitle($pathInfo['filename']);
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
