# LOE\Music\Song

A Song object extends [LOE\Base](../../Base.md) and represents a music file stored in the LOE File System.

`public function __construct($UID = null)`

## Requirements

* [Mp3Reader](https://github.com/outlawdesigns-io/Mp3Reader) provides the ability to read and write an MP3 file's ID3 tags.

## Properties

### const TABLE:string

The name of the database table that stores these records.

### public UID:int
The unique identifier for this record. Used to instantiate a specific object. If a new object is instantiated without providing a `$UID`, a blank object will be generated.

### public title:string

The title of this record.

### public artist:string

The artist who produced this record.

### public album:string

The album on which this record is featured.

### public year:int

The year this record was released.

### public track_number:int

This record's position on the album on which it was featured.

### public genre:string

This record's genre.

### public band:string

The band which produced this record. Alias of `$artist`

### public length:int

This record's length in seconds.

### public publisher:string

The record company the produced this record.

### public bpm:int

This record's average beats per minute.

### public feat:string

A comma separated list of any featured artists on this record.

### public cover_path:string

The relative path to the image in the LOE file system that represents this record.

### public file_path:string

The relative path to the file in the LOE file system that this record represents.

### public play_count:int

The number of times the file in the LOE file system represented by this record has been requested by clients based on the number of times it appears in the [Web Access Service](https://github.com/outlawdesigns-io/WebAccessService).

### public last_play:datetime

The date of the most recent occurrence of the file in the LOE file system representing this record having been requested by a client.

### public created_date:datetime

The date that this record was created and the file in the LOE file system representing this record was processed from the Holding Bay to permanent storage.

### public artist_country:string

The home country of the artist who produced this record.

### public rating:int

The average rating (1-5) applied to this record by users.

### public artist_city:string

The home city of the artist who produced this record.

### public artist_state:string

The home state or province of the artist who produced this record.

## Methods

### public getMp3Tags():associativeArray
Reads ID3 tag data from .mp3 file associated with this record and returns an associative array of the results.
```
Array
(
    [title] => Celebration of the Goat
    [album] => Death to All
    [year] => 2009
    [track_number] => 1
    [genre] => Blackened Death Metal
    [artist] => Necrophobic
    [bpm] => 110.003
)

```
### public validateTags():associativeArray
Reads ID3 tag data from .mp3 file associated with this record, compares the results with the associated properties of this record and returns an associative array of the unequal properties and their ID3 values.

`print_r($song->validateTags());`
```
Array
(
    [album] => Death to Most
)
```
### public static getRandom($genre:string = null):LOE\Music\Song
Selects a random `LOE\Music\Song::UID`, builds and returns that object. Potential results can be limited by supplying the `$genre` argument.

`print_r($song::getRandom('Black Metal'));`
```
LOE\Music\Song Object
(
    [UID] => 47387
    [title] => Unclosing The Curse
    [artist] => Marduk
    [album] => Wormwood
    [year] => 2009
    [track_number] => 4
    [genre] => Black Metal
    [band] =>
    [length] =>
    [publisher] =>
    [bpm] => 92.391
    [feat] => N/A
    [cover_path] => /LOE/Music/Marduk/Wormwood (2009)/cover.jpg
    [file_path] => /LOE/Music/Marduk/Wormwood (2009)/Unclosing The Curse.mp3
    [play_count] => 1
    [last_play] =>
    [created_date] => 2016-11-28 03:23:46
    [artist_country] =>
    [rating] =>
    [artist_city] =>
    [artist_state] =>
    [id:protected] => 47387
    [suite:protected] =>
    [driver:protected] =>
    [database:protected] => LOE
    [table:protected] => Song
    [primaryKey:protected] => UID
)
```

### protected _writeMp3Tags():boolean

Overwrites ID3 tag data with this record's properties. Currently unused.





## Usage

```
$song = \LOE\Factory::createModel('Song',53679);
if(!count($song->validateTags())){
  echo "This song's properties match its ID3 tags. Great job!";
}
```
