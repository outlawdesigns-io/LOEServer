# LOE\Anime\Rating

A Rating object extends [LOE\Base](../../Base.md) and represents a user's rating (1 - 5) of a [LOE\Anime\Anime](./Anime.md) object.

`public function __construct($UID = null)`

## Requirements

* None

## Properties

### const TABLE:string

The name of the database table that stores these records.

### public UID:int
The unique identifier for this record. Used to instantiate a specific object. If a new object is instantiated without providing a `$UID`, a blank object will be generated.

### public animeId:int

The UID of the [LOE\Anime\Anime](./Anime.md) object to which this rating relates.

### public userId:int

The `$UID` of the [User]() who created this object.

### public created_date:datetime

The datetime that this rating was created.

## Methods

None

## Usage

```
$rating = \LOE\Factory::createModel('AnimeRating');
print_r($rating);
```
```
LOE\Anime\Rating Object
(
    [UID] =>
    [animeId] =>
    [rating] =>
    [userId] =>
    [created_date] =>
    [id:protected] =>
    [suite:protected] =>
    [driver:protected] =>
    [database:protected] => LOE
    [table:protected] => AnimeRating
    [primaryKey:protected] => UID
)
```
