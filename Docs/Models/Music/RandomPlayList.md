# LOE\Music\RandomPlayList

A RandomPlayList object represents a randomly generated collection [LOE\Music\Song](./Song.md) objects.

`public function __construct($genre = null,$maxSongs = 10)`

## Requirements

* None

## Properties

### public songs:array

An array that contains the randomly selected [LOE\Music\Song](./Song.md) objects.

### protected _genre:string

A string that is passed as an argument to the constructor to limit results to a specific genre.

### protected _maxSongs:int

An int that is passed as an argument to the constructor to limit the length of the generated list.

## Methods

None

## Usage

```
$list = \LOE\Factory::createRandomPlayList('Song',10);
foreach($list->songs as $song){
  echo $song->title . "\n";
}
```
