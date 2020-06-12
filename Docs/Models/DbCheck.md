

# LOE\DbCheck

A DbCheck object extends [LOE\Base](../../Base.md) and represents a snapshot of a [LOE\DbHealthScanner](../../Processes/Scanners/DbHealthScanner.md) execution.

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

### public startTime:datetime

The datetime that the [LOE\DbHealthScanner](../../Processes/Scanners/DbHealthScanner.md) execution associated with this object began.

### public endTime:datetime

The datetime that the [LOE\DbHealthScanner](../../Processes/Scanners/DbHealthScanner.md) execution associated with this object completed.

### public runTime:int

The number of seconds spent executing the [LOE\DbHealthScanner](../../Processes/Scanners/DbHealthScanner.md) associated with this object.

### public recordCount:int

The total number of model objects processed by the [LOE\DbHealthScanner](../../Processes/Scanners/DbHealthScanner.md) execution associated with this object.

### public missingCount:int

The total number of model objects who's `$file_path` value has been determined to not exist by the [LOE\DbHealthScanner](../../Processes/Scanners/DbHealthScanner.md) execution associated with this object.

## Methods
* None

## Usage

[DbHealthCheck.php](https://github.com/outlawdesigns-io/LOEServer/blob/master/Processes/Jobs/DbHealthCheck.php)
