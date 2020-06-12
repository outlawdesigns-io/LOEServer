# LOE\Music\PlayList

A PlayList object represents a collection of [LOE\Music\Song](../../Base.md) objects that have been saved by a [User](./Song.md).

`public function __construct($UID = null)`

## Requirements

* None

## Properties


### const TABLE:string

The name of the database table that stores these records.

### public UID:int
The unique identifier for this record. Used to instantiate a specific object. If a new object is instantiated without providing a `$UID`, a blank object will be generated.

### public UserId:int

The `$UID` of the [User]() who created this object.

### public Label:string

The user friendly label for identifying this object.

### public SongIds:array[int]

An array of the `$UID`s associated with the [LOE\Music\Song](./Song.md) objects that comprise this list.

## Methods

None

## Usage

```
$list = \LOE\Factory::createModel('SongPlayList',1);
print_r($list);
```
```
LOE\Music\PlayList Object
(
    [UID] => 1
    [UserId] => 2
    [Label] => krome_antihydropic
    [SongIds] => Array
        (
            [0] => 99730
            [1] => 99630
            [2] => 100549
        )

    [created_date] => 2020-05-06 10:29:36
    [id:protected] => 1
    [suite:protected] =>
    [driver:protected] =>
    [database:protected] => LOE
    [table:protected] => SongPlayList
    [primaryKey:protected] => UID
)

```
