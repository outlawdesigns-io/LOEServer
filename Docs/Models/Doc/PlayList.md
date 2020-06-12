# LOE\Doc\PlayList

A PlayList object represents a collection of [LOE\Doc\Doc](./Doc.md) objects that have been saved by a [User](./Doc.md).

`public function __construct($UID = null)`

## Requirements

* None

## Properties


### const TABLE:string

The name of the database table that stores these records.

### public UID:int
The unique identifier for this record. Used to instantiate a specific object. If a new object is instantiated without providing a `$UID`, a blank object will be generated.

### public UserId:int

The `$UID` of the [User]() who created this record.

### public Label:string

The user friendly label for identifying this record.

### public DocIds:array[int]

An array of the `$UID`s associated with the [LOE\Doc\Doc](./Doc.md) objects that comprise this list.

## Methods

None

## Usage

```
$list = \LOE\Factory::createModel('DocPlayList',1);
print_r($list);
```
```
LOE\Doc\PlayList Object
(
    [UID] => 1
    [UserId] => 2
    [Label] => krome_antihydropic
    [SongIds] => Array
        (
            [0] => 1
            [1] => 993
            [2] => 541
        )

    [created_date] => 2020-05-06 10:29:36
    [id:protected] => 1
    [suite:protected] =>
    [driver:protected] =>
    [database:protected] => LOE
    [table:protected] => DocPlayList
    [primaryKey:protected] => UID
)

```
