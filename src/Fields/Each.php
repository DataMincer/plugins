<?php

namespace DataMincerPlugins\Fields;

use DataMincerCore\Plugin\PluginFieldBase;
use DataMincerCore\Plugin\PluginFieldInterface;

/**
 * @property PluginFieldInterface do
 * @property PluginFieldInterface source
 */
class Each extends PluginFieldBase {

  protected static $pluginId = 'each';

  /**
   * Initialize field
   */
  public function initialize() {
    // Don't bootstrap fields below
    $this->initialized = TRUE;
  }

  function getValue($data) {
    $source = $this->source->getValue($data);
    if (!is_array($source)) {
      $this->error("Value of the 'source' must be an array.");
    }
    $result = [];
    foreach($source as $item) {
      $result[] = $this->do->getValue(['input' => $item] + $data);
    }
    return $result;
  }

  static function getSchemaChildren() {
    return parent::getSchemaChildren() + [
      'source' => [ '_type' => 'partial', '_required' => TRUE, '_partial' => 'field' ],
      'do' => [ '_type' => 'partial', '_required' => TRUE, '_partial' => 'field' ],
    ];
  }

}
