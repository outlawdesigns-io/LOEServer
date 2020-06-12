
# LOE\Base

The abstract `LOE\Base` class extends [\Record](https://github.com/outlawdesigns-io/Record) and contains constants and methods that need to be available to all concrete model classes.

`public function __construct($database, $table, $primaryKey, $id)`

## Requirements

* [Record](https://github.com/outlawdesigns-io/Record) facilitates database communications.

## Properties


### const DB:string

The name of the database that stores model tables.

### cosnt PRIMARYKEY:string

The primary key property that must exist on all concrete models to uniquely identify them.

### const FILEPATT:string

Regular expression used for turning absolute paths into relative ones.

### const WEBROOT:string

The relative path that should be prefixed to an object's file_path to convert its relative path to absolute.

### const FILEUNSETERR:string

Error string to be thrown when trying to execute a method that relies on an object's file_path property when that property is not set.

## Methods

### protected _cleanFilePath($path:string):string

Decodes HTML Entities and converts absolute paths to relative.

### protected _cleanProperties():$this

Decodes HTML Entites and UTF8 encodes each of a model's public properties.

### public cleanFilePath($path:string):string

Public alias to `_cleanFilePath()`

### public verifyLocation():boolean

Verifies that a model's `$file_path` property is set and that the associated file exists.

### public calculateSize():int

Verifies that a model's `$file_path` property is set, that the associated file exists and returns the size in bytes of that file.

### public static recordExists($absolutePath:string):boolean

Accepts an absolute path to a file in the LOE file system and determines if a record exists for that file in a specific model's database table.

### public static search($key:string, $value:string):Object

Performs a search where `$key` like `$value`.

### public static count():associativeArray

Returns an associative array with an index named `count` whose value represents the total number of records in a specific model's table.

### public static function countOf($key:string):associativeArray

Returns an associative array with indexes representing distinct values for `key` and the number of records associated with that key.

### public static function getAll():Array[Object]

Returns an array of model objects representing all records in that model's table.

### public static function recent($limit:int):Array[Object]

Returns an array of model objects representing representing the most recent records added to that model's table limited by the `$limit` argument.

## Usage
```
<?php namespace LOE;

require_once __DIR__ . '/Base.php';

Class Image extends Base{

const TABLE = 'images';

public $UID;
public $takenBy;
public $takenOn;
public $ofWhat;

  public function __construct($UID = null){
    parent::__construct(self::DB,self::TABLE,self::PRIMARYKEY,$UID);
  }
}
```
