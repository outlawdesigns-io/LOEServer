
# LOE\Music\HoldingBayScanner

A `LOE\Music\HoldingBayScanner` object extends [LOE\HoldingBayScanner](../HoldingBayScanner.md). Its parent `$targetModels` property will be populated with [LOE\Music\Song](../../../Models/Music/Song.md) objects. On initialization, a `LOE\Music\HoldingBayScanner` gathers ID3 tag data from each file in the LOE File System associated with the `$file_path` property of each [LOE\Music\Song](../../../Models/Music/Song.md) object and uses that data to sort the `$targetModels` according to album and artist.

`public function __construct($model:`[LOE\Model](../../../Models/Model.md)`)`

## Requirements

* None

## Properties

### public albums:array[Object]
An associative array with indexes corresponding to unique values of the `$album` property of the [LOE\Music\Song](../../../Models/Music/Song.md)s in `$targetModels`. The value of those indexes holds an array of [LOE\Music\Song](../../../Models/Music/Song.md)s whose `$album` property relates that that index.

### public artists:array[Object]
An associative array with indexes corresponding to unique values of the `$artist` property of the [LOE\Music\Song](../../Models/Music/Song.md)s in `$targetModels`. The value of those indexes holds an array of indexes corresponding to unique values of the `$album` property of the [LOE\Music\Song](../../../Models/Music/Song.md)s in `$targetModels`. The value of those indexes holds an array of [LOE\Music\Song](../../../Models/Music/Song.md)s whose `$album` property relates that that index.

### public unknownAlbum:array[Object]

An array of [LOE\Music\Song](../../../Models/Music/Song.md) objects for which no value for their `$album` property could be identified.

### public unknownArtist:array[Object]

An array of [LOE\Music\Song](../../../Models/Music/Song.md) objects for which no value for their `$artist` property could be identified.

## Methods

### protected _getTags():$this

Loops through each [LOE\Music\Song](../../../Models/Music/Song.md) in `$targetModels` and attempts to assign the results of calling its `getMp3Tags()` method to its public properties.

### protected  _sortAlbums():$this

Populates this object's `$albums` property.

### protected _sortArtists():$this

Populates this object's `$artists` property.


## Usage

[LOE\Music\HoldingBayCleaner](https://github.com/outlawdesigns-io/LOEServer/blob/master/Processes/Processors/Music/HoldingBayCleaner.php)
