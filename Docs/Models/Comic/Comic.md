# LOE\Comic\Comic

An `LOE\Comic\Comic` object extends [LOE\Base](../../Base.md) and represents an comic book file stored in the LOE File System.

`public function __construct($UID = null)`

## Requirements

* None

## Properties

### const TABLE:string

The name of the database table that stores these records.

### public UID:int
The unique identifier for this record. Used to instantiate a specific object. If a new object is instantiated without providing a `$UID`, a blank object will be generated.

### public issue_number:int

This record's position in order of publication relative to other records of the same publication series.

### public issue_title:string

The unique title of this record.

### public issue_cover_date:date

The `YYY-mm-dd` formatted date that the comic book associated with this record was released.

### public series_title:string

The title shared by other records of the same publication series.

### public series_start_year:date

The `YYY` formatted date that the series associated with this record began publication.

### public series_end_year:date

The `YYY` formatted date that the series associated with this record ceased publication.

### public publisher:string

The name of the publication company that produced the comic book represented by the file in the LOE File System associated with this record.

### public story_arc:string

The title of the story arc under which the comic book represented by the file in the LOE File system associated with this record takes place.

### public issue_description:string

Detailed description of the events that take place in the comic book represented by the file in the LOE File System associated with this record.

### public series_description:string

Broad description of the series in which the comic book represented by the file in the LOE File System associated with this record was published.

### public issue_type:string

The type of issue of the comic book represented by the file in the LOE File System associated with this record.

### public file_path:string

The relative path to the file in the LOE file system that this record represents .

## Methods
* None

## Usage

`print_r(\LOE\Factory::createModel(\LOE\Comic\Comic::TABLE,1166));`
