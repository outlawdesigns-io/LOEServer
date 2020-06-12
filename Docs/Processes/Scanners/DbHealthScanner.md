
# LOE\DbHealthScanner

A DbHealthScanner object extends [MessageClient](https://github.com/outlawdesigns-io/MessageClient) and identifies model records whose associated file in the LOE File System is determined to not exist. It calculates the health of a provided [LOE\Model](../../Models/Model.md)'s database and optionally emails the results to a specified address.

`public function __construct($model:`[LOE\Model](../../Models/Model.md),`$msgTo:string = null,$authToken:string = null)`

## Requirements

* [MessageClient](https://github.com/outlawdesigns-io/MessageClient)

## Properties

### const MSGSUBJ:string

The base string to be assigned to the `$subject` property for emailed reports.

### public msgResponse:Object
The response received by [MessageClient](https://github.com/outlawdesigns-io/MessageClient) from the [MessengerService](https://github.com/outlawdesigns-io/MessengerService) when attempting to email report results.

### public recordCount:int

A public alias to `$_recordCount`

### public missing:array[Object]

An array of model objects whose files in the LOE File System associated with their `$file_path` properties are determined to not exist by this object.

### protected _objects:array[Object]

An array of objects whose `$file_path` property this object will test to determine if the file in the LOE File System associated with that object exists.

### protected _msgTo:string

A string that represents the email address to which the results of this report should be sent. Set by the `$msgTo` argument of the constructor.

### protected _fileCount:int

The total number of model objects whose files in the LOE File System associated with their `$file_path` properties are determined to exist by this object.

### protected _recordCount:int

The total number of model records processed by this object.

### protected _model:Object<[LOE\Model](../../Models/Model.md)>

The [LOE\Model](../../Models/Model.md) that this object should test. Set by the `$model` argument of the constructor.

## Methods

None

## Usage

```
    private function _createScanner(){
        $key = ucwords($this->endpoint);
        if(!isset($this->args[1])){
          $msgTo = null;
          $authToken = null;
        }else{
          $msgTo = $this->args[1];
          $authToken = $this->user->auth_token;
        }
        if(strtolower($this->args[0]) == 'db'){
          $obj = \LOE\Factory::createDbScanner($key,$msgTo,$authToken);
        }elseif(strtolower($this->args[0] == 'fs')){
          $obj = \LOE\Factory::createFsScanner($key,$msgTo,$authToken);
        }else{
          throw new \Exception(self::REQERR);
        }
        return $obj->missing;
    }
```
