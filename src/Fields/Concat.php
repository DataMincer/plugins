<?php

namespace DataMincerPlugins\Fields;

use DataMincerCore\Plugin\PluginFieldBase;

/**
 * @property array items
 * @property string glue
 */
class Concat extends PluginFieldBase {

  protected static $pluginId = 'concat';

  function getValue($data) {
    return implode($this->glue ?? '', $this->resolveParams($data, $this->items));
  }

  static function getSchemaChildren() {
    return parent::getSchemaChildren() + [
      'items' => [ '_type' => 'choice', '_choices' => [
        'array' =>   [ '_type' => 'prototype', '_required' => TRUE, '_min_items' => 1, '_prototype' => [
          '_type' => 'text', '_required' => TRUE]],
        'single' =>  [ '_type' => 'text', '_required' => TRUE ]
      ]],
      'glue' => [ '_type' => 'text', '_required' => FALSE ]
    ];
  }

}
