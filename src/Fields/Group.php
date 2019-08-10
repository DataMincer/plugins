<?php

namespace DataMincerPlugins\Fields;

use DataMincerCore\Plugin\PluginFieldBase;
use DataMincerCore\Plugin\PluginFieldInterface;

/**
 * @property PluginFieldInterface source
 */
class Group extends PluginFieldBase {

  protected static $pluginId = 'map';

  function getValue($data) {
    $source = $this->source;
//    $vals = $this->resolveParams($data, $this->vals);
//    return array_combine($keys, $vals);
  }

  static function getSchemaChildren() {
    return parent::getSchemaChildren() + [
      'source' => [ '_type' => 'partial', '_required' => TRUE, '_partial' => 'field' ],
      'by' => [ '_type' => 'text', '_required' => TRUE ],
    ];
  }

}
