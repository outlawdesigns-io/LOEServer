
# LOE\Factory

The `LOE\Factory` class is a collection of static methods intended to provide access to most or all of the Library of Everything's essential functions. Using the factory to instantiate models and initiate processes rather than accessing those classes directly will minimize namespace errors and help to future-proof your existing code.

* `LOE\Factory` does not require instantiation as all of its methods are static.

## Requirements

* None

## Properties


### const BADOBJ:string

Error string to be thrown when trying to construct an invalid object type.

## Methods

### public static createModel($type:string, $UID:int = null):Object

The `$type` argument represents the table name of the object type you want to create. It can be passed as a string, or the model's `::TABLE` value can be called directly. The `$UID` argument represents the unique identifier of the specific model you want to create. By default `$UID` is null and `createModel` will return a blank object.
```
require_once __DIR__ . '/Factory.php';

print_r(\LOE\Factory::createModel(\LOE\Music\Song::TABLE,53679));

```

### public static createHoldingBayScanner($model:Object<[Model](./Models/Model.md)>):Object

Build a scanner

### public static createFsScanner($model:Object<[Model](./Models/Model.md)>, $msgTo:string = null, $authToken:string = null):Object<[FsHealthScanner](./Processes/Scanners/FsHealthScanner.md)>

Fs Scan

### public static createDbScanner($model:Object<[Model](./Models/Model.md)>, $msgTo:string = null, $authToken:string = null):Object<[DbHealthScanner](./Processes/Scanners/DbHealthScanner.md)>

Db Scan

### public static createHoldingBayProcessor($type:string, $inputObj:Object):Object

Process a holding bay file.

### public static createHoldingBayCleaner($type:string):Object

Clean holding bay files.

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
