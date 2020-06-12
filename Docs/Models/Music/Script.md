# LOE\Music\Script

A Script object extends [LOE\Base](../../Base.md) and represents a the script (or lyrics) for a [LOE\Music\Song](./Song.md) object.

`public function __construct($UID = null)`

## Requirements

* None

## Properties

### const TABLE:string

The name of the database table that stores these records.

### public UID:int
The unique identifier for this record. Used to instantiate a specific object. If a new object is instantiated without providing a `$UID`, a blank object will be generated.

### public songId:int

The UID of the [LOE\Music\Song](./Song.md) object to which this script relates.

### public body:string

The body text of this object's script (lyrics).

## Methods

None

## Usage

```
$script = \LOE\Factory::createModel('SongScript',797);
print_r($script);
```
```
LOE\Music\Script Object
(
    [UID] => 797
    [songId] => 94342
    [body] => (Instrumental)
    [id:protected] => 797
    [suite:protected] =>
    [driver:protected] =>
    [database:protected] => LOE
    [table:protected] => SongScript
    [primaryKey:protected] => UID
)

```
