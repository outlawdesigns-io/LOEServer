
# LOE\HoldingBayScanner

The abstract `LOE\HoldingBayScanner` extends [LOE\FsScanner](./FsScanner.md), accepts a [LOE\Model](../../Models/Model.md) as an argument to its constructor, calls `_scanForver()` on the directory represented by the value of that model's `$holdingBayRoot` property and implements `_interpretFile()` for its child classes. This extra layer of abstraction allows child classes to focus exclusively on processes that are unique to them.

`public function __construct($model:`[LOE\Model](../../Models/Model.md)`)`

## Requirements

* None

## Properties

### public targetModels:array[Object]

An array of blank model objects with their `$file_path` property set to the relative path in the LOE File System of each file discovered by [LOE\FsScanner](./FsScanner.md)'s `_scanForver()` method that can be associated with the value of the `$model` property of this object.

### public possibleCovers:array[string]

An array of strings representing the absolute paths to each .jpg image file discovered by [LOE\FsScanner](./FsScanner.md)'s `_scanForver()` method.

### public extraFiles:array[string]

An array of strings representing the absolute paths to each file discovered by [LOE\FsScanner](./FsScanner.md)'s `_scanForver()` method that is not an image and is not associated with this object's `$_model` property.

### protected _model:[LOE\Model](../../Models/Model.md)

  The [LOE\Model](../../Models/Model.md) whose holding bay location this object will scan. Set by the `$model` argument provided in a concrete class's constructor.

## Methods

### protected _interpretFile($absolutePath:string)

All concrete classes that extend `LOE\FsScanner` must implement a protected `_interpretFile()` method that accepts an absolute path on the LOE File System as its `$absolutePath` argument. FsScanners are intended to search the file system for file types that are of interest to them.

### public static isDirShortcut($relativePath:string):boolean

Determines if the string passed in through the `$relativePath` argument is `.` or `..`

### public static isDirEmpty($absolutePath:string):boolean

Determines if the directory represented by the `$absolutePath` argument contains files or sub directories.

### public cleanup($absolutePath:string)

public alias to `_cleanUp()`

### protected _scanForever($dir:string)

Recursively scans a directory and calls `_interpretFile()` on each file it identifies.

### protected _cleanup($dir:string)

Delete the directory in the LOE File System represented by the `$dir` argument.

### protected _unlink($absolutePath:string)

Delete the file in the LOE File System represented by the `$absolutePath` argument.


## Usage

[LOE\Movie\HoldingBayScanner](https://github.com/outlawdesigns-io/LOEServer/blob/master/Processes/Scanners/Movie/HoldingBayScanner.php)
