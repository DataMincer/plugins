<?php

namespace DataMincerPlugins\Fields;

use Exception;
use DataMincerCore\Plugin\PluginFieldBase;
use DataMincerCore\Plugin\PluginFieldInterface;

/**
 * @property PluginFieldInterface path
 */
class File extends PluginFieldBase {

  protected static $pluginId = 'file';

  function getValue($data) {
    $path = $this->path->value($data);
    $path_info = parse_url($path);
    if (!array_key_exists('scheme', $path_info)) {
      // local file
      if (!file_exists($path)) {
        // Special case: when having bundle in the data, check its directory automatically
        if (array_key_exists('bundle', $data) && array_key_exists('path', $data['bundle'])) {
          $path = $data['bundle']['path'] . '/' . $path;
        }
      }
      if (!file_exists($path)) {
        $this->error("Cannot open file: $path");
      }
    }
    $data = @file_get_contents($path);
    if ($data === FALSE) {
      $this->error('Cannot read file data: ' . error_get_last()['message']);
    }
    return $data;
  }

  static function getSchemaChildren() {
    return parent::getSchemaChildren() + [
      'path' => [ '_type' => 'partial', '_required' => TRUE, '_partial' => 'field' ],
    ];
  }

}
