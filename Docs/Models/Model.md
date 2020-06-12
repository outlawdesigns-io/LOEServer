

# LOE\Model

A Model object extends [LOE\Base](../../Base.md) and represents an abstract object type to be tracked by the Library of Everything.

`public function __construct($UID = null)`

## Requirements

* None

## Properties

### const TABLE:string

The name of the database table that stores these records.

### public UID:int
The unique identifier for this record. Used to instantiate a specific object. If a new object is instantiated without providing a `$UID`, a blank object will be generated.

### public label:string

The Friendly label for this model.

### public fsRoot:string

The relative path in the LOE file system that represents this model's root directory.

### public holdingBayRoot:string

The relative path in the LOE file system that represents the root directory where files associated with this model type will be stored while they await processing.

### public fileExtensions:array[string]

An array of strings that represent the file type extensions that will be tracked by this model.

### public namespace:string

A string that represents the code namespace associated with this model. This property can be used in dynamic class creation.

```
    public static function createHoldingBayScanner($model){
      $className = $model->namespace . "HoldingBayScanner";
      return new $className($model);
    }
```
## Methods

### public static getByLabel($label:string):Object<LOE\Model>

Returns the `LOE\Model` object associated with the provided `$label` argument.

`print_r(LOE\Model::getByLabel('Song'));`

## Usage

```
  protected function _buildObjects(){
    $className = $this->_model->namespace . $this->_model->label;
    $this->_objects = $className::getAll();
    $this->_recordCount = count($this->_objects);
    $this->recordCount = $this->_recordCount;
    return $this;
  }
```
