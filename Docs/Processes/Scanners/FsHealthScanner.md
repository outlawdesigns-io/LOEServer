# LOE\FSHealthScanner

A FSHealthScanner object extends [LOE\FsScanner](./FsScanner.md) and identifies files in the LOE File System with no associated record in a [LOE\Model](../../Models/Model.md)'s database and optionally emails the results to a specified address.

`public function __construct($model:`[LOE\Model](../../Models/Model.md),`$msgTo:string = null,$authToken:string = null)`

## Requirements

* None

## Properties

### const MSGSUBJ:string

The base string to be assigned to the `$subject` property for emailed reports.

### public msgResponse:Object
The response received by [MessageClient](https://github.com/outlawdesigns-io/MessageClient) from the [MessengerService](https://github.com/outlawdesigns-io/MessengerService) when attempting to email report results.

### public files:array[string]

An array of strings representing the absolute paths to each of the files in the LOE File System determined to be associated with the given [LOE\Model](../../Models/Model.md).

### public missing:array[string]

An array of strings representing the absolute paths to each of the files in the LOE File System determined to not having an existing record in its associated [LOE\Model](../../Models/Model.md)'s database.

### protected _msgTo:string

A string that represents the email address to which the results of this report should be sent. Set by the `$msgTo` argument of the constructor.

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
