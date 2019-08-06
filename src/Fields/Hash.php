<?php

namespace DataMincerPlugins\Fields;

use DataMincerCore\Plugin\PluginFieldBase;

/**
 * @property string source
 */
class Hash extends PluginFieldBase {

  protected static $pluginId = 'hash';

  function getValue($data) {
    $value = $this->resolveParam($data, $this->source);
    if (is_scalar($value)) {
      return sha1($value);
    }
    else {
      return NULL;
    }
  }

  static function getSchemaChildren() {
    return parent::getSchemaChildren() + [
      'source' => [ '_type' => 'text', '_required' => TRUE ]
    ];
  }

}
