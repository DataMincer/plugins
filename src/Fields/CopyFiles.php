<?php

namespace DataMincerPlugins\Fields;

use DataMincerCore\Plugin\PluginFieldBase;
use DataMincerCore\Plugin\PluginFieldInterface;

/**
 * @property PluginFieldInterface path
 * @property PluginFieldInterface mask
 * @property PluginFieldInterface destination
 */
class CopyFiles extends PluginFieldBase {

  protected static $pluginId = 'copyfiles';

  function getValue($data) {
    $path = $this->_fileManager->resolveUri($this->path->value($data));
//    $destination_path = $this->_fileManager->resolveUri($this->destination->value($data));
//    if (!file_exists($source_path)) {
//      $this->error("Cannot open file: $source_path");
//    }
//    $destination_dir = dirname($destination_path);
//    $this->_fileManager->prepareDirectory($destination_dir);
//    $result = @copy($source_path, $destination_path);
//    if (!$result) {
//      $this->error("Cannot copy file: $source_path > $destination_path");
//    }
//    return $destination_path;
  }

  static function getSchemaChildren() {
    return parent::getSchemaChildren() + [
      'path' => [ '_type' => 'partial', '_required' => TRUE, '_partial' => 'field' ],
      'mask' => [ '_type' => 'text', '_required' => FALSE ],
      'destination' => [ '_type' => 'partial', '_required' => TRUE, '_partial' => 'field' ],
    ];
  }

  static function defaultConfig($data = NULL) {
    return [
      'glue' => '',
      'filter' => FALSE,
    ] + parent::defaultConfig($data);
  }

  static function extendReplace($data = NULL) {
    xdebug_break();
    return [
      'field' => 'chain',
      'items' => [
        [
          'field' => 'opendir',
          'mask' => '*.jpg',
          'path' => 'bundle://',
        ]
      ]
    ];
  }

}
