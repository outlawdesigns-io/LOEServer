
# LOE\Doc\Doc

An `LOE\Doc\Doc` object extends [LOE\Base](../../Base.md) and represents a document file stored in the LOE File System.

`public function __construct($UID = null)`

## Requirements

* None

## Properties

### const TABLE:string

The name of the database table that stores these records.

### public UID:int
The unique identifier for this record. Used to instantiate a specific object. If a new object is instantiated without providing a `$UID`, a blank object will be generated.

### public title:string

The title of the document file associated with this record.

### public subtitle:string

The sub title of the document file associated with this record.

### public author:string

The author of the document file associated with this record.

### public pub_date:date

The `YYY-mm-dd` formatted date that the document associated with this record was published.

### public pub_type:string

The format in which the document associated with this record was originally published.

### public created_date:datetime

The date that this record was created and the file in the LOE file system representing this record was processed from the Holding Bay to permanent storage.

### public category:string

The name of the publication company that produced the comic book represented by the file in the LOE File System associated with this record.

### public access_level:int

Indicates a level of access that a user requires to access the document associated with this record.

### public file_path:string

The relative path to the file in the LOE file system that this record represents.

### public tags:array[string]

List of tags that apply to the document associated with this record so that it might fall into multiple categories.

## Methods
* None

## Usage

`print_r(\LOE\Factory::createModel(\LOE\Doc\Doc::TABLE,104));`
