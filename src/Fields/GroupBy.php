<?php

namespace DataMincerPlugins\Fields;

use DataMincerCore\Plugin\PluginFieldBase;

/**
 * @property array items
 * @property string glue
 */
class GroupBy extends PluginFieldBase {

  protected static $pluginId = 'groupby';

  function getValue($data) {
    xdebug_break();
  }

  static function getSchemaChildren() {
    return parent::getSchemaChildren() + [
      'list' => [ '_type' => 'partial', '_required' => TRUE, '_partial' => 'field' ],
      'by' => [ '_type' => 'prototype', '_required' => TRUE, '_min_items' => 1, '_prototype' => [
        '_type' => 'text', '_required' => TRUE
      ]],

    ];
  }

}
