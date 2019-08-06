<?php

namespace DataMincerPlugins\Fields;

use DataMincerCore\Plugin\PluginFieldBase;

/**
 * @property array keys
 * @property array vals
 */
class Map extends PluginFieldBase {

  protected static $pluginId = 'map';

  function getValue($data) {
    $keys = $this->resolveParams($data, $this->keys);
    $vals = $this->resolveParams($data, $this->vals);
    return array_combine($keys, $vals);
  }

  static function getSchemaChildren() {
    return parent::getSchemaChildren() + [
      'keys' => [ '_type' => 'prototype', '_required' => TRUE, '_min_items' => 1, '_prototype' => [
        '_type' => 'text', '_required' => TRUE
      ]],
      'vals' => [ '_type' => 'prototype', '_required' => TRUE, '_min_items' => 1, '_prototype' => [
        '_type' => 'text', '_required' => TRUE
      ]],
    ];
  }

}
