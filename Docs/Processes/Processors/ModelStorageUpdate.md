
# LOE\ModelStorageUpdate

A `LOE\ModelStorageUpdate` object accepts a [LOE\Model](../../../Models/Model.md) object as an argument to its constructor and creates a [LOE\ModelStorage](../../../Models/ModelStorage.md) representing a snapshot of that [LOE\Model](../../../Models/Model.md)'s storage consumption on the LOE file system.

`public function __construct($model:`[LOE\Model](../../../Models/Model.md)`)`

## Requirements

* This object relies on PHP's ability to execute shell commands on its host. It leverages that ability to execute the Linux command stored in `SHELLBASE`. It is the only operating system dependency in [LOEServer](https://github.com/outlawdesigns-io/LOEServer/) package and is a temporary solution.

## Properties

### const SHELLBASE:string

The base Linux shell command that this object uses to determine storage sizes and measurement units.

### const TARGETMODEL:string
The class name of the [LOE\ModelStorage](../../../Models/ModelStorage.md) that this object creates.

### const REGPATTERN:string
A regular expression used to extract storage size and measurement units from `SHELLBASE` output.

### protected _model:Object
The [LOE\Model](../../../Models/Model.md), accepted in the constructor, whose storage consumption on the LOE file system is being measured.

### protected _storageModel:array
The concrete instance of `TARGETMODEL`.

## Methods

### protected _execShellCommand($dir:string):array

Uses `SHELLBASE` to call PHP's `shell_exec()` command on a directory provided as the `$dir` argument.

### protected  _parseResults($resultStr:string):array

Uses `REGPATTERN` to extract directory size and measurement units from shell command output.

## Usage
```
require_once __DIR__ . '/../../Factory.php';

$models = \LOE\Model::getAll();

foreach($models as $model){
  try{
    \LOE\Factory::updateModelStorage($model);
  }catch(\Exception $e){
    echo $e->getMessage() . "\n";
  }
}
```
