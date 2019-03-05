<?php namespace LOE;

require_once __DIR__ . '/../../Libs/MessageClient/MessageClient.php';

abstract class FsScanner extends \MessageClient{
  abstract protected function _scanForever($dir);
  abstract protected function _interpretFile($absolutePath);
  abstract protected function _recordExists($absolutePath);
  abstract protected function _verifyDatabase();
}
