
# LOE\Movie\HoldingBayScanner

A `LOE\Movie\HoldingBayScanner` object extends [LOE\HoldingBayScanner](../HoldingBayScanner.md). Its parent `$targetModels` property will be populated with [LOE\Movie\Movie](../../../Models/Movie/Movie.md) objects. On initialization, a `LOE\Movie\HoldingBayScanner` uses the directory name of the file in the LOE File System associated with the value of each [LOE\Movie\Movie](../../../Models/Movie/Movie.md) object's  `$file_path` property to search IMDB for details.

`public function __construct($model:`[LOE\Model](../../../Models/Model.md)`)`

## Requirements

* [IMDB](https://github.com/outlawdesigns-io/IMDB)

## Properties

### const YEARPATTERN1:string

A regular expression used to test for the presence of `(` character in a possible search string.

### const YEARREPLACEMENT1:string

A regular expression used to replace everything after a `(` character.

### const YEARREPLACEMENT2:string

A regular expression used to capture everything between `()`.

### const YEARPATTERN2:string

A regular expression used to identify a series of 4 numbers.

### public movies:array[Object]

An array of [LOE\Movie\Movie](../../../Models/Movie/Movie.md) objects who have had their public properties successfully assigned values corresponding to the results of an IMDB search.

### public exceptions:array[string]

An array of strings that were used to search IMDB and returned no results at all.

## Methods

### protected _gatherData():$this

Loops through each [LOE\Movie\Movie](../../../Models/Movie/Movie.md) in `$targetModels` and attempts identify the title of that movie from the name of the directory in which that file is held. It then uses this title to search IMDB and assigns values to that [LOE\Movie\Movie](../../../Models/Movie/Movie.md)'s public properties that correspond to the values of the corresponding properties of the top IMDB search result.

## Usage

```
    protected function holdingbay(){
        $data = null;
        switch($this->verb){
            case 'movies':
                $scanner = \LOE\Factory::createHoldingBayScanner(\LOE\Factory::getModel('Movie'));
                $data = $scanner->movies;
                break;
            case 'tv':
                $scanner = \LOE\Factory::createHoldingBayScanner(\LOE\Factory::getModel('Episode'));
                $data = $scanner->shows;
                break;
            case 'music':
                \LOE\Factory::createHoldingBayCleaner('song');
                $scanner = \LOE\Factory::createHoldingBayScanner(\LOE\Factory::getModel('Song'));
                $data = $scanner->artists;
                $data['images'] = $scanner->possibleCovers;
                break;
            case 'anime':
                break;
            case 'docs':
                break;
            default:
                throw new \Exception(self::REQERR);
        }
        return $data;
    }
```
