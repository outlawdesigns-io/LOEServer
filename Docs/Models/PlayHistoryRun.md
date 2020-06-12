

# LOE\PlayHistoryRun

A PlayHistoryRun object extends [LOE\Base](../../Base.md) and represents a snapshot of a [LOE\PlayHistory](../../Processes/Processors/PlayHistory.md) execution.

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

The datetime that the [LOE\PlayHistory](../../Processes/Processors/PlayHistory.md) execution associated with this object began.

### public endTime:datetime

The datetime that the [LOE\PlayHistory](../../Processes/Processors/PlayHistory.md) execution associated with this object completed.

### public runTime:int

The number of seconds spent executing the [LOE\PlayHistory](../../Processes/Processors/PlayHistory.md) associated with this object.

### public searchResultCount:int

The total number of search results processed by the [LOE\PlayHistory](../../Processes/Processors/PlayHistory.md) execution associated with this object.

### public exceptionCount:int

The total number of search results determined to not have corresponding model objects by the [LOE\PlayHistory](../../Processes/Processors/PlayHistory.md) execution associated with this object.

### public exceptionCaught:boolean

Indicates if the [LOE\PlayHistory](../../Processes/Processors/PlayHistory.md) execution associated with this object failed to complete because of a thrown exception.

### public exceptionMessage:string

The message associated with the thrown exception that flipped the `$exceptionCaught` property.

## Methods
* None

## Usage

[UpdatePlayCounts.php](https://github.com/outlawdesigns-io/LOEServer/blob/master/Processes/Jobs/UpdatePlayCounts.php)
