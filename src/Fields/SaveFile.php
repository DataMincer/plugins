<?php

namespace DataMincerPlugins\Fields;

use DataMincerCore\Plugin\PluginFieldBase;
use DataMincerCore\Plugin\PluginFieldInterface;
use DataMincerCore\Util;

/**
 * @property PluginFieldInterface contents
 * @property PluginFieldInterface destination
 * @property boolean base64
 */
class SaveFile extends PluginFieldBase {

  protected static $pluginId = 'savefile';

  function getValue($data) {
    $contents = $this->contents->value($data);
    if ($this->base64) {
      $contents = base64_decode($contents);
      // Don't resolve param if it's a binary content
    }
    $destination_path = $this->destination->value($data);
    $destination_dir = dirname($destination_path);
    Util::prepareDir($destination_dir);
    if (!file_exists($destination_path)) {
      file_put_contents($destination_path, $contents);
    }
    return $destination_path;
  }

  static function getSchemaChildren() {
    return parent::getSchemaChildren() + [
      'contents' => [ '_type' => 'partial', '_required' => TRUE, '_partial' => 'field' ],
      'base64' => [ '_type' => 'boolean', '_required' => FALSE ],
      'destination' => [ '_type' => 'partial', '_required' => TRUE, '_partial' => 'field' ],
    ];
  }

  static function defaultConfig($data = NULL) {
    return [
      'base64' => FALSE,
    ] + parent::defaultConfig($data);
  }

}
