<?php namespace LOE\Music;

require_once __DIR__ . "/../../Scanners/FsScanner.php";

// $exceptions = array();
// try{
//     $m = new MissingAlbumCovers();
// }catch(Exception $e){
//     $exceptions[] = $e->getMessage();
// }
// echo "Auto Fix Complete\n";
// echo "Dirs Auto Fixed: " . $m->autoFixCount . "\n";
// echo "Dirs Remaining: " . count($m->missingCovers) . "\n";
// print_r($m->missingCovers);
// if(count($exceptions)){
//     echo "Exception Dirs: " . count($exceptions) . "\n";
//     print_r($exceptions);
// }

class AutoCovers extends \LOE\FsScanner{

    /*RECURSIVE SCAN OF ROOT MUSIC DIR TO IDENTIFY ALBUM DIRS WITH NO 'cover.jpg'
    1) IDENTIFY DIRS
    2) ATTEMPT TO AUTO FIX DIRS WITH IMAGE FILES MATCHING A KNOWN FORMAT
    3) RETURN USER UNFIXED DIRS WITH ANY POSSIBLE IMAGES

    NOTE IS AUTO FIX ATTEMPTS TO FIX A FILE AND FAILS IT WILL THROW AN EXCEPTION CONTAINING THE DIR IN QUESTION
    */

    const ROOTDIR = '/LOE/Music/';
    const OPENPATTERN = '/\(/';
    const CLOSEPATTERN = '/\)/';

    public $possibleCovers = array();
    public $missing = array();
    public $fixedDirs = array();
    public $autoFixCount;
    protected $_hasCover = array();
    protected $altNames = array('00-cover.jpg','Cover.jpg');

    public function __construct(){
        $this->autoFixCount = 0;
        $this->_root(\LOE\LoeBase::WEBROOT . self::ROOTDIR)
               _scanForever(\LOE\LoeBase::WEBROOT . self::ROOTDIR);
        // ->_autoFix();
    }
    protected function _interpretFile($absolutePath){
      $fileInfo = pathinfo($absolutePath);
      if($fileInfo["basename"] == "cover.jpg" && !in_array($fileInfo['dirname'],$this->_hasCover)){
        $this->_hasCover[] = $fileInfo['dirname'];
      }elseif($fileInfo['extension'] == 'jpg'){
        $this->possibleCovers[] = $absolutePath;
      }
      if(!in_array($fileInfo['dirname'],$this->_hasCover) && !in_array($fileInfo['dirname'],$this->missing) && preg_match(self::OPENPATTERN,$fileInfo['dirname'])){
        echo $absolutePath . "\n";
        $this->missing[] = $fileInfo['dirname'];
      }
      return $this;
    }
    protected function _autoFix(){
        foreach($this->missingCovers as $dir){
            foreach($this->possibleCovers as $imgPath){
                $pattern = "~" . preg_quote($dir) . "~";
                if(preg_match($pattern,$imgPath)){
                    $pathInfo = pathinfo($imgPath);
                    if(in_array($pathInfo["basename"],$this->altNames) && !in_array($dir,$this->fixedDirs)){
                        $newName = dirname($imgPath) . "/cover.jpg";
                        if(!rename($imgPath,$newName)){
                            throw new \Exception($dir);
                        }else{
                            $this->fixedDirs[] = $dir;
                            $this->autoFixCount++;
                        }
                    }
                }
            }
        }
        return $this;
    }
}
