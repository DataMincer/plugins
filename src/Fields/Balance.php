<?php

namespace DataMincerPlugins\Fields;

use DataMincerCore\Plugin\PluginFieldBase;
use DataMincerCore\Plugin\PluginFieldInterface;

/**
 * @property PluginFieldInterface source
 * @property string[] levels
 */
class Balance extends PluginFieldBase {

  protected static $pluginId = 'balance';

  function getValue($data) {
    $source = $this->source->getValue($data);
    $levels = $this->resolveParam($data, $this->levels);
    $res = $this->balanceTree($source, $levels);
    return $res;
  }

  /**
   * @param $tree
   * @param $levels
   */
  function balanceTree($tree, $levels) {
    $level = array_shift($levels);
    $t = $tree;
  }

  static function getSchemaChildren() {
    return parent::getSchemaChildren() + [
      'source' => [ '_type' => 'partial', '_required' => TRUE, '_partial' => 'field' ],
      'levels' => [ '_type' => 'text', '_required' => TRUE ]
    ];
  }

}
