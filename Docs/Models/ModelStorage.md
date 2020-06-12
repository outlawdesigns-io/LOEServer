

# LOE\ModelStorage

A ModelStorage object extends [LOE\Base](../../Base.md) and represents a snapshot of each model's disk usage in the LOE File System.

`public function __construct($UID = null)`

## Requirements

* None

## Properties

### const TABLE:string

The name of the database table that stores these records.

### public UID:int
The unique identifier for this record. Used to instantiate a specific object. If a new object is instantiated without providing a `$UID`, a blank object will be generated.

### public modelId:int

The `$UD` of the [LOE\Model](./Model.md) to which this object relates.

### public created_date:datetime

The datetime that this object was created.

### public fs_size:decimal

The number of `$fs_unit`s stored in the LOE file system associated with the [LOE\Model](./Model.md) associated with this object.

### public fs_unit:string

The unit by which to measure the `$fs_size` associated with this object.

### public hb_size:decimal

The number of `$hb_unit`s stored awaiting processing in the LOE Holding Bay associated with the [LOE\Model](./Model.md) associated with this object.

### public hb_unit:string

The unit by which to measure the `$hb_size` associated with this object.

## Methods
* None

## Usage

[LOE\ModelStorageUpdate](https://github.com/outlawdesigns-io/LOEServer/blob/master/Processes/Processors/ModelStorageUpdate.php)
