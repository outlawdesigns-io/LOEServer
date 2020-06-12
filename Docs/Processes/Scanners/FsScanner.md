
# LOE\FsScanner

The abstract `LOE\FsScanner` extends [MessageClient](https://github.com/outlawdesigns-io/MessageClient) and provides access to file system related methods to be inherited by concrete classes that scan the LOE File System.

## Requirements

* [MessageClient](https://github.com/outlawdesigns-io/MessageClient)

## Properties

None
## Methods

### abstract protected _interpretFile($absolutePath:string)

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

[LOE\FsHealthScanner](https://github.com/outlawdesigns-io/LOEServer/blob/master/Processes/Scanners/FsHealthScanner.php)
