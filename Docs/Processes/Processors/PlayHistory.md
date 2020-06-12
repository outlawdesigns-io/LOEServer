
# LOE\PlayHistory

A `LOE\PlayHistory` object accepts a [LOE\Model](../../../Models/Model.md) as an argument to its constructor well as a `$username` and a `$password` string that will be used to authenticate access to the [Web Access Service](https://github.com/outlawdesigns-io/WebAccessService). The `LOE\PlayCount` object will query the [Web Access Service](https://github.com/outlawdesigns-io/WebAccessService) for all requests whose `$query` property includes an extension that is contained in the [LOE\Model](../../../Models/Model.md)'s `$fileExtensions` property.

`public function __construct($model:`[LOE\Model](../../../Models/Model.md)`,$username:string,$password:string)`

## Requirements

* [Web Access Client](https://github.com/outlawdesigns-io/WebAccessClient)

## Properties

### const SPACEPATT:string

A Regular expression used to identify URL encoded space patterns (`%20`).

### const REQEND:string

The [Web Access Service](https://github.com/outlawdesigns-io/WebAccessService) endpoint with which this object communicates.

### const REQKEY:string

The [Request](https://github.com/outlawdesigns-io/AccessLogParser/blob/master/Models/Request.php) property on which this object will search.

### const TARGETFIELD:string
The [LOE Model](../../../Base.md) property on which `REQKEY` will be matched.

### public exceptions:array[string]
An array of `$file_path` properties that this object could not match to an existing model object.

### public processedCount:int

The total number of `Played` [LOE Model](../../../Base.md)s created by this object.

### public static responceCodes:array[int]

An array of HTTP status codes that indicate that a resource was requested and successfully returned.

### public searchResults:array[Object<[\Request](https://github.com/outlawdesigns-io/AccessLogParser/blob/master/Models/Request.php)>]
An array of [Web Access Client](https://github.com/outlawdesigns-io/WebAccessClient) search results.

### protected _webClient:Object
The instance of [Web Access Client](https://github.com/outlawdesigns-io/WebAccessClient) that this object will use to communicate with [Web Access Service](https://github.com/outlawdesigns-io/WebAccessService).

### protected _model:Object
The [LOE\Model](../../../Models/Model.md) on which this object is operating.

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
  $run = \LOE\Factory::createModel('PlayHistoryRun');
  $run->modelId = $model->UID;
  $run->startTime = date("Y-m-d H:i:s");
  try{
    $processor = \LOE\Factory::updatePlayHistory($model,ACCOUNT_USER,ACCOUNT_PASSWORD);
    $run->searchResultCount = count($processor->searchResults);
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
