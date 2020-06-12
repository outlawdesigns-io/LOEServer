# LOE\PlayCount

A `LOE\PlayCount` object accepts a [LOE\Model](../../../Models/Model.md) as an argument to its constructor well as a `$username` and a `$password` string that will be used to authenticate access to the [Web Access Service](https://github.com/outlawdesigns-io/WebAccessService). The `LOE\PlayCount` object will query the [Web Access Service](https://github.com/outlawdesigns-io/WebAccessService) for all requests whose `$query` property includes an extension that is contained in the [LOE\Model](../../../Models/Model.md)'s `$fileExtensions` property.

`public function __construct($model:`[LOE\Model](../../../Models/Model.md)`,$username:string,$password:string)`

## Requirements

* [Web Access Client](https://github.com/outlawdesigns-io/WebAccessClient)

## Properties

### const SPACEPATT:string

A Regular expression used to identify URL encoded space patterns (`%20`).

### public searchResultCount:int
The total number of results returned by [Web Access Client](https://github.com/outlawdesigns-io/WebAccessClient).

### public exceptions:array[string]
An array of `$file_path` properties that this object could not match to an existing model object.

### public processedCount:int
The total number of....

### protected _webClient:Object
The instance of [Web Access Client](https://github.com/outlawdesigns-io/WebAccessClient) that this object will use to communicate with [Web Access Service](https://github.com/outlawdesigns-io/WebAccessService).

### protected _model:Object
The [LOE\Model](../../../Models/Model.md) on which this object is operating.

### protected _modelCounts:array[Object<[\Request](https://github.com/outlawdesigns-io/AccessLogParser/blob/75c49203dc544d37b2c46eb5fd3292c886fcb703/Models/Request.php)>]
An array of [Web Access Client](https://github.com/outlawdesigns-io/WebAccessClient) search results.
## Methods

### protected _getModels():$this

Uses `SHELLBASE` to call PHP's `shell_exec()` command on a directory provided as the `$dir` argument.

### protected  _updateCounts():$this

Uses `REGPATTERN` to extract directory size and measurement units from shell command output.

### protected _buildPath($query:string):string
Accepts a [Web Access Client](https://github.com/outlawdesigns-io/WebAccessClient)'s `$query` property as an argument and returns that query formatted as a path on the LOE file system.

## Usage
```
$models = \LOE\Model::getAll();

foreach($models as $model){
  $startTime = microtime(true);
  $run = \LOE\Factory::createModel('PlayCountRun');
  $run->modelId = $model->UID;
  $run->startTime = date("Y-m-d H:i:s");
  try{
    $processor = \LOE\Factory::updatePlayCounts($model,ACCOUNT_USER,ACCOUNT_PASSWORD);
    $run->searchResultCount = $processor->searchResultCount;
    $run->exceptionCount = count($processor->exceptions);
    $run->processedCount = $processor->processedCount;
    if(count($processor->exceptions)){
      print_r($processor->exceptions);
    }
  }catch(\Exception $e){
    $run->exceptionCaught = 1;
    $run->exceptionMessage = $e->getMessage();
  }
  $endTime = microtime(true);
  $executionSeconds = $endTime - $startTime;
  $run->endTime = date("Y-m-d H:i:s");
  $run->runTime = $executionSeconds;
  $run->create();
}
```
