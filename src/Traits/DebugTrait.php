<?php

namespace DataMincerPlugins\Traits;

trait DebugTrait {

  protected static $MAXLENGTH = 100;

  protected function prepareData($data, $select = [], $shrink = FALSE) {
    $result = [];
    $current_step = array_shift($select);
    foreach($data as $key => $value) {
      if (!is_null($current_step) && $current_step !== $key) {
        continue;
      }
      switch(gettype($value)) {
        case "string":
          if ($this->isBase64($value)) {
            if ($shrink && strlen($value) > static::$MAXLENGTH) {
              $value = substr($value, 0, static::$MAXLENGTH) . '...';
            }
          }
          if ($this->isBinary($value)) {
            $value = '[BINARY]';
          }
          $result[$key] = $value;
          break;
        case "array":
          $result[$key] = $this->prepareData($value, $select, $shrink);
          break;
        case "object":
          $result[$key] = (string) $value;
          break;
        default:
          $result[$key] = $value;
      }
    }
    return $result;
  }

  function isBase64($data) {
    return preg_match('~^[a-zA-Z0-9/+]*={0,2}$~', $data);
  }

  function isBinary($data) {
    return preg_match('~[^\x20-\x7E\t\r\n]~', $data) > 0;
  }

  public static function defaultConfig($data = NULL) {
    /** @noinspection PhpUndefinedClassInspection */
    return parent::defaultConfig($data) + [
      'dense' => TRUE,
      'select' => []
    ];
  }

  static function getSchemaChildren() {
    /** @noinspection PhpUndefinedClassInspection */
    return parent::getSchemaChildren() + [
      'dense' => ['_type' => 'boolean', '_required' => FALSE],
      'select' => ['_type' => 'prototype', '_required' => FALSE, '_min_items' => 1, '_prototype' => [
        '_type' => 'text', '_required' => TRUE,
      ]]
    ];
  }



}
