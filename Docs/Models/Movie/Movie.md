

# LOE\Movie\Movie

An `LOE\Doc\Doc` object extends [LOE\Base](../../Base.md) and represents a movie file stored in the LOE File System.

`public function __construct($UID = null)`

## Requirements

* None

## Properties

### const TABLE:string

The name of the database table that stores these records.

### public UID:int
The unique identifier for this record. Used to instantiate a specific object. If a new object is instantiated without providing a `$UID`, a blank object will be generated.

### public title:string

The title of the movie associated with this record.

### public relyear:date

The `YYYY` formatted date that represents the year that the movie associated with this record was released.

### public genre:string

The primary genre of the movie associated with this record.

### public genre2:string

The secondary genre of the movie associated with this record.

### public genre3:string

The tertiary genre of the movie associated with this record.

### public director:string

The director of the movie associated with this record.

### public description:string

A detailed description of the events that take place in the movie associated with this record.

### public run_time:int

The length in minutes of the movie associated with this record.

### public cover_path:string

The relative path to the image in the LOE file system that represents this record.

### public file_path:string

The relative path to the file in the LOE file system that this record represents.

### rating:string

A parental guidance rating that indicates the intended audience for the movie associated with this record.

### user_rating:int

The average rating (1-5) applied to this record by users.

### play_count:int

The number of times the file in the LOE file system represented by this record has been requested by clients based on the number of times it appears in the [Web Access Service](https://github.com/outlawdesigns-io/WebAccessService).

## Methods
* None

## Usage

`print_r(\LOE\Factory::createModel(\LOE\Doc\Doc::TABLE,104));`
