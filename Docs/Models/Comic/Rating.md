# LOE\Comic\Rating

A Rating object extends [LOE\Base](../../Base.md) and represents a user's rating (1 - 5) of a [LOE\Comic\Comic](./Comic.md) object.

`public function __construct($UID = null)`

## Requirements

* None

## Properties

### const TABLE:string

The name of the database table that stores these records.

### public UID:int
The unique identifier for this record. Used to instantiate a specific object. If a new object is instantiated without providing a `$UID`, a blank object will be generated.

### public comicId:int

The UID of the [LOE\Comic\Comic](./Comic.md) object to which this rating relates.

### public userId:int

The `$UID` of the [User]() who created this object.

### public created_date:datetime

The datetime that this rating was created.

## Methods

None

## Usage

```
$rating = \LOE\Factory::createModel('ComicRating');
print_r($rating);
```
```
LOE\Comic\Rating Object
(
    [UID] =>
    [comicId] =>
    [rating] =>
    [userId] =>
    [created_date] =>
    [id:protected] =>
    [suite:protected] =>
    [driver:protected] =>
    [database:protected] => LOE
    [table:protected] => ComicRating
    [primaryKey:protected] => UID
)
```
