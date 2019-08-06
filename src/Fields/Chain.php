<?php

namespace DataMincerPlugins\Fields;

use DataMincerCore\Plugin\PluginFieldBase;
use DataMincerCore\Plugin\PluginFieldInterface;

/**
 * @property PluginFieldInterface[] items
 * @property string source
 */
class Chain extends PluginFieldBase {

  protected static $pluginId = 'chain';

  function getValue($data) {
    $result = NULL;
    $data_copy = $data;
    $data_copy['source'] = isset($this->source) ? $this->resolveParams($data, $this->source) : NULL;
    $data_copy['input'] = $data_copy['source'];
    foreach ($this->items as $index => $field) {
      $result = $field->value($data_copy);
      for ($i = $index; $i > 0; $i--) {
        $data_copy[str_repeat('../', $i) . 'input'] = $data_copy[str_repeat('../', $i - 1) . 'input'];
      }
      $data_copy['input'] = $result;
    }
    return $result;
  }

  static function getSchemaChildren() {
    return parent::getSchemaChildren() + [
      'source' => [ '_type' => 'text', '_required' => FALSE ],
      'items' => [ '_type' => 'prototype', '_required' => TRUE, '_prototype' => [
        '_type' => 'partial', '_required' => TRUE, '_partial' => 'field',
      ]],
    ];
  }

}
