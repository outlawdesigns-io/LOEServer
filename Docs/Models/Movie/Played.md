

# LOE\Movie\Played

A Played object extends extends [LOE\Base](../../Base.md) and represents an instance of the file in the LOE file system associated with a [LOE\Doc\Doc](./Doc.md) object having been requested by a client as determined by the [Web Access Service](https://github.com/outlawdesigns-io/WebAccessService).

`public function __construct($UID = null)`

## Requirements

* None

## Properties


### const TABLE:string

The name of the database table that stores these records.

### public UID:int
The unique identifier for this record. Used to instantiate a specific object. If a new object is instantiated without providing a `$UID`, a blank object will be generated.

### public movieId:int

The UID of the [LOE\Movie\Movie](./Movie.md) object to which this object relates.

### public ipAddress:string

The IP address of the requesting client.

### public userId:int

The `$UID` of the [User]() who created this object.

### public playDate:datetime

The date and time that the file in the LOE file system associated with the [LOE\Movie\Movie](./Movie.md) object to which this object relates was requested as determined by the [Web Access Service](https://github.com/outlawdesigns-io/WebAccessService).

## Methods

### public static recordExists($movieId:int, $playDate:datetime):boolean

Determine if a record for a Played movie exists based on the assumption that movie cannot be requested more than once at the exact same time.

## Usage
[UpdatePlayHistory.php](https://github.com/outlawdesigns-io/LOEServer/blob/master/Processes/Jobs/UpdatePlayHistory.php)
