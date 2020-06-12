# LOE\Anime\Anime

An `LOE\Anime\Anime` object extends [LOE\Base](../../Base.md) and represents an anime related video file stored in the LOE File System.

`public function __construct($UID = null)`

## Requirements

* None

## Properties

### const TABLE:string

The name of the database table that stores these records.

### public UID:int
The unique identifier for this record. Used to instantiate a specific object. If a new object is instantiated without providing a `$UID`, a blank object will be generated.

### public show_title:string

The English title of this record.

### public japanese_title:string

The Japanese title of this record.

### public type:string

Indicates the type of Anime associated with this record.
*  Movie
* Tv
* OVA
* Special

### public season:int

Indicates the season number of the Anime associated with this record. Defaults to `0`.

### public ep_number:string

Indicates the episode number of the Anime associated with this record. Defaults to `0`.

### public ep_title:string

Indicates the episode title of the Anime associated with this record. Defaults to `$show_title`

### public run_time:int

Indicates the length in minutes of the Anime associated with this record.

### public rating:string

A parental guidance rating that indicates the intended audience for the anime associated with this record.

### public genre:string

Indicates the primary genre of the Anime associated with this record.

### public genre2:string

Indicates the secondary genre of the Anime associated with this record.

### public genre3:string

Indicates the tertiary genre of the Anime associated with this record.

### public description:string

Provides a description of the Anime associated with this record.

### public release_date:date

The `YYY-mm-dd` formatted date that the Anime associated with this record was released.

### public cover_path:string

The relative path to the image in the LOE file system that represents this record.

### public file_path:string

The relative path to the file in the LOE file system that this record represents .

## Methods
* None

## Usage

`print_r(\LOE\Factory::createModel(\LOE\Anime\Anime::TABLE));`
