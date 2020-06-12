
# LOE Server

## Preamble

The `LOEServer` package contains the code base for interacting with the Library of Everything database and file system. The LOE file system stores various types of files associated with parent [Models](./Docs/Models/Model.md) and the LOE database stores detailed relational information about those [Models](./Docs/Models/Model.md).

## Setup

All concrete models are extended from [LOE\Base](./Docs/Base.md) and require a public `$file_path` property that represents the location of the file in the LOE file system to which that record relates.

Database connection details are managed by the [Db](https://github.com/outlawdesigns-io/Db) package. Edit [credentials.php](https://github.com/outlawdesigns-io/Db/blob/master/credentials.php) to connect to your desired database server.

[LOE\Base](./Docs/Base.md) defines the following
* The name of the database that will store models.
* The name of the primary key that will be shared by all concrete models.
* The base file path to prepend to a model's `$file_path` property
  * `$file_path` will always be served up as relative paths within the LOE file system.
  * `WEBROOT` is prepended to `$file_path` to build a record's absolute path in the file system.


## Code Structure

Libs/ -- required submodules

 * [./Libs/MessageClient/MessageClient](https://github.com/outlawdesigns-io/MessageClient)
   * Used to email reports generated by Scanner/Processor classes.
 * [./Libs/Mp3Reader/Mp3Reader](https://github.com/outlawdesigns-io/Mp3Reader)
   * required for a [LOE\Music\Song](./Docs/Models/Music/Song.md) to be able to read/write the ID3 tags associated with it.
 * [./Libs/Record/Record](https://github.com/outlawdesigns-io/Record)
   * Extended to create [LOE\LoeBase](./Docs/Base.md) which is extended to create concrete model objects.
 * [./Libs/IMDB/Imdb](https://github.com/outlawdesigns-io/IMDB)
   * Used by [LOE\Movie\HoldingBayScanner](./Docs/Processes/Scanners/Movie/HoldingBayScanner.md) to fetch IMDB information about Holding Bay Movies.
 * [./Libs/MessageClient/MessageClient](https://github.com/outlawdesigns-io/MessageClient)
   * Used by [LOE\FsScanner](./Docs/Processes/Scanners/FsHealthScanner.md) and [LOE\DbHealthScanner](./Docs/Processes/Scanners/DbHealthScanner.md) to send reports.
 * [./Libs/ComicVine/ComicVine](https://github.com/outlawdesigns-io/ComicVine)
   * Used within [\LOE\Comic](./Docs/Models/Comic/Comic.md) namespace to fetch detailed information about comic book issues.
 * [./Libs/WebAccessClient/WebAccessClient](https://github.com/outlawdesigns-io/WebAccessClient)
   * Used by [\LOE\PlayCount](./Docs/Processes/Processors/PlayCount.md) to fetch number of plays from [WebAccessService](https://github.com/outlawdesigns-io/WebAccessService)

### Models/

The models directory contains class definitions for all objects that will be saved to the database and should contain a sub directory for each [Model](./Docs/Models/Model.md) namespace that will contain all the concrete class definitions associated specific to that namespace.

* [LOE\DbCheck](./Docs/Models/DbCheck.md)
* [LOE\FsCheck](./Docs/Models/FsCheck.md)
* [LOE\Model](./Docs/Models/Model.md)
* [LOE\ModelStorage](./Docs/Models/ModelStorage.md)
* [LOE\PlayCountRun](./Docs/Models/PlayCountRun.md)
* [LOE\PlayHistoryRun](./Docs/Models/PlayHistoryRun.md)
* [LOE\Share](./Docs/Models/Share.md)
* Anime
  * [LOE\Anime\Anime](./Docs/Models/Anime/Anime.md)
  * [LOE\Anime\Played](./Docs/Models/Anime/Played.md)
  * [LOE\Anime\Playlist](./Docs/Models/Anime/Playlist.md)
  * [LOE\Anime\Rating](./Docs/Models/Anime/Rating.md)
* Comic
  * [LOE\Comic\Comic](./Docs/Models/Comic/Comic.md)
  * [LOE\Comic\Played](./Docs/Models/Comic/Played.md)
  * [LOE\Comic\PlayList](./Docs/Models/Comic/PlayList.md)
  * [LOE\Comic\Rating](./Docs/Models/Comic/Rating.md)
* Doc
  * [LOE\Doc\Doc](./Docs/Models/Doc/Doc.md)
  * [LOE\Doc\Played](./Docs/Models/Doc/Played.md)
  * [LOE\Doc\PlayList](./Docs/Models/Doc/PlayList.md)
  * [LOE\Doc\Rating](./Docs/Models/Doc/Rating.md)
* Movie
  * [LOE\Movie\Movie](./Docs/Models/Movie/Movie.md)
  * [LOE\Movie\Played](./Docs/Models/Movie/Played.md)
  * [LOE\Movie\PlayList](./Docs/Models/Movie/PlayList.md)
  * [LOE\Movie\Rating](./Docs/Models/Movie/Rating.md)
* Music
  * [LOE\Music\Played](./Docs/Models/Music/Played.md)
  * [LOE\Music\PlayList](./Docs/Models/Music/PlayList.md)
  * [LOE\Music\RandomPlayList](./Docs/Models/Music/RandomPlayList.md)
  * [LOE\Music\Rating](./Docs/Models/Music/Rating.md)
  * [LOE\Music\Script](./Docs/Models/Music/Script.md)
  * [LOE\Music\Song](./Docs/Models/Music/Song.md)


### Processes/
Parent directory that contains all non-model class definitions. Non-model classes are divided into two categories:
* Scanners
  * Classes that collect and verify data but do not change the state of any data.
* Processors
  * Classes that verify data and attempt, in some way, to change to state of that data.    
### Scanners/
The scanners directory contains definitions for classes that verify data and produce reports, but do not change the state of any data and should contain a sub directory for each [Model](./Docs/Models/Model.md) namespace that will contain all the concrete class definitions specific to that namespace.

 * [LOE\DbHealthScanner](./Docs/Processes/Scanners/DbHealthScanner.md)
 * [LOE\FsHealthScanner](./Docs/Processes/Scanners/FsHealthScanner.md)
 * [LOE\FsScanner](./Docs/Processes/Scanners/FsScanner.md)
 * [LOE\HoldingBayScanner](./Docs/Processes/Scanners/HoldingBayScanner.md)
  * Doc
    * [LOE\Doc\HoldingBayScanner](./Docs/Processes/Scanners/Doc/HoldingBayScanner.md)
  * Movie
    * [LOE\Movie\HoldingBayScanner](./Docs/Processes/Scanners/Movie/HoldingBayScanner.md)
   * Music
     * [LOE\Music\HoldingBayScanner](./Docs/Processes/Scanners/Music/HoldingBayScanner.md)
   * Tv
     * [LOE\Tv\HoldingBayScanner](./Docs/Processes/Scanners/Tv/HoldingBayScanner.md)

### Processors/

The scanners directory contains definitions for classes that verify data and attempt, in some way, to change to state of that data and should contain a sub directory for each [Model](./Docs/Models/Model.md) namespace that will contain all the concrete class definitions specific to that namespace.

* [LOE\ModelStorageUpdate](./Docs/Processes/Processors/ModelStorageUpdate.md)
* [LOE\PlayCount](./Docs/Processes/Processors/PlayCount.md)
* [LOE\PlayHistory](./Docs/Processes/Processors/PlayHistory.md)
 *  Comic
   * [LOE\Comic\AutoInsert](./Docs/Processes/Processors/Comic/AutoInsert.md)
 * Doc
   * [LOE\Doc\AutoInsert](./Docs/Processes/Processors/Doc/AutoInsert.md)
 * HoldingBay
   * [LOE\HoldingBay\ArchiveExtractor](./Docs/Processes/Processors/HoldingBay/ArchiveExtractor.md)
 * Movie
   * [LOE\Movie\HoldingBayProcessor](./Docs/Processes/Processors/Movie/HoldingBayProcessor.md)
 * Music
   * [LOE\Music\AutoCovers](./Docs/Processes/Processors/Music/AutoCovers.md)
   * [LOE\Music\AutoID3](./Docs/Processes/Processors/Music/AutoID3.md)
   * [LOE\Music\AutoRestore](./Docs/Processes/Processors/Music/AutoRestore.md)
   * [LOE\Music\HoldingBayAutoProcessor](./Docs/Processes/Processors/Music/HoldingBayAutoProcessor.md)
   * [LOE\Music\HoldingBayCleaner](./Docs/Processes/Processors/Music/HoldingBayCleaner.md)
   * [LOE\Music\HoldingBayProcessor](./Docs/Processes/Processors/Music/HoldingBayProcessor.md)
 * Tv
   * [LOE\Tv\HoldingBayProcessor](./Docs/Processes/Processors/Tv/HoldingBayProcessor.md)



## Usage

```
<?php namespace \LOE;

class MyNewModel extends LoeBase{

  const DB = 'LOE';
  const TABLE = 'myModel';
  const PRIMARYKEY = 'UID';

  public $UID;
  public $firstName;
  public $secondName;
  public $file_path;
  public $tags = array();

  public function __construct($UID = null){
    parent::__construct(self::DB,self::TABLE,self::PRIMARYKEY,$UID);
    $this->file_path = $this->_cleanFilePath($this->file_path);
  }
}

class MynewScanner extends FsScanner{

  const ROOTDIR = '/LOE/myModel';
  public $files = array();

  public function __construct($msgTo = null,$authToken = null){
    $this->_scanForever(LoeBase::WEBROOT . self::ROOTDIR);
  }
  protected function _interpretFile($absolutePath){
    if(in_array(pathinfo($absolutePath)['extension'],self::$validExtensions)){
      $this->files[] = $absolutePath;
    }
    return $this;
  }
}

$songs = Songs::getAll();
foreach($songs as $song){
  if(!$song->verifyLocation()){
    //todo this song appears to be missing
  }else{
    $id3 = $song->validateTags();
  }
}

```
