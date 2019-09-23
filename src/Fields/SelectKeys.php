<?php

namespace DataMincerPlugins\Fields;

use DataMincerCore\Plugin\PluginFieldBase;
use DataMincerCore\Plugin\PluginFieldInterface;

/**
 * @property PluginFieldInterface source
 * @property PluginFieldInterface keys
 */
class SelectKeys extends PluginFieldBase {

  protected static $pluginId = 'selectkeys';

  function getValue($data) {
    $source = $this->source->getValue($data);
    $keys = $this->keys->getValue($data);
    if (!array_key_exists($index, $source)) {
      $this->error("Index '$index' not found in the data.");
    }
    return $source[$index];
  }

  static function getSchemaChildren() {
    return parent::getSchemaChildren() + [
      'source' => [ '_type' => 'partial', '_required' => TRUE, '_partial' => 'field' ],
      'keys' => [ '_type' => 'partial', '_required' => TRUE, '_partial' => 'field' ],
    ];
  }

}
