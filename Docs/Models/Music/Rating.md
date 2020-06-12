# LOE\Music\Rating

A Rating object extends [LOE\Base](../../Base.md) and represents a user's rating (1 - 5) of a [LOE\Music\Song](./Song.md) object.

`public function __construct($UID = null)`

## Requirements

* None

## Properties

### const TABLE:string

The name of the database table that stores these records.

### public UID:int
The unique identifier for this record. Used to instantiate a specific object. If a new object is instantiated without providing a `$UID`, a blank object will be generated.

### public songId:int

The UID of the [LOE\Music\Song](./Song.md) object to which this rating relates.

### public userId:int

The `$UID` of the [User]() who created this object.

### public created_date:datetime

The datetime that this rating was created.

## Methods

None

## Usage

```
$rating = \LOE\Factory::createModel('SongRating',453);
print_r($rating);
```
```
LOE\Music\Rating Object
(
    [UID] => 453
    [songId] => 91991
    [rating] => 5
    [userId] => 2
    [created_date] => 2020-04-25 10:18:08
    [id:protected] => 453
    [suite:protected] =>
    [driver:protected] =>
    [database:protected] => LOE
    [table:protected] => SongRating
    [primaryKey:protected] => UID
)
```
