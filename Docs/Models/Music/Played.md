# LOE\Music\Played

A Played object extends extends [LOE\Base](../../Base.md) and represents an instance of the file in the LOE file system associated with a [LOE\Music\Song](./Song.md) object having been requested by a client as determined by the [Web Access Service](https://github.com/outlawdesigns-io/WebAccessService).

`public function __construct($UID = null)`

## Requirements

* None

## Properties


### const TABLE:string

The name of the database table that stores these records.

### public UID:int
The unique identifier for this record. Used to instantiate a specific object. If a new object is instantiated without providing a `$UID`, a blank object will be generated.

### public songId:int

The UID of the [LOE\Music\Song](./Song.md) object to which this object relates.

### public ipAddress:string

The IP address of the requesting client.

### public userId:int

The `$UID` of the [User]() who created this object.

### public playDate:datetime

The date and time that the file in the LOE file system associated with the [LOE\Music\Song](./Song.md) object to which this object relates was requested as determined by the [Web Access Service](https://github.com/outlawdesigns-io/WebAccessService).

## Methods

### public static recordExists($songId:int, $playDate:datetime):boolean

Determine if a record for a Played Song exists based on the assumption that a Song cannot be requested more than once at the exact same time.

### public static dates():array[date]

Returns an array of the distinct dates on which the files in the LOE File System associated with [LOE\Music\Song](./Song.md) objects have been requested by clients.

### public static counts($key:string, $date:datetime = null):array[associativeArray]

Returns an array of associative arrays, each with an index representing a distinct value for the `$key` argument and a `count` index that represents the number of records associated with that `$key` value.

### public static dailyAverage():int

Returns the daily average number of files in the LOE file system associated with [LOE\Music\Song](./Song.md) objects requested by clients.

## Usage

```
$results = \LOE\Music\Played::counts('artist','2020-04-29');
print_r($results);
```
```
Array
(
    [0] => Array
        (
            [count] => 19
            [artist] => Vinnie Paz
        )

    [1] => Array
        (
            [count] => 2
            [artist] => Jedi Mind Tricks
        )

    [2] => Array
        (
            [count] => 2
            [artist] => Sabaton
        )

)
```
