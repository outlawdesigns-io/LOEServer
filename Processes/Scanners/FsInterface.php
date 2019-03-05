<?php namespace LOE;

interface FsInterface{
  protected function _scanForever($dir);
  protected function _interpretFile($absolutePath);
  protected function _recordExists($absolutePath);
  protected function _verifyDatabase();
}
