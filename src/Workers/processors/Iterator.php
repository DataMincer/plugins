<?php

namespace DataMincerPlugins\Workers\processors;

use DataMincerCore\Plugin\PluginWorkerBase;

class Iterator extends PluginWorkerBase {

  protected static $pluginId = 'iterator';

  public function evaluate($data = []) {
    // Do not evaluate 'fields' field, as it's intended for process()
    return $this->evaluateChildren($data, [], [['fields']]);
  }

  /**
   * @inheritDoc
   */
  public function process($config) {
    $data = yield;
    $values = $this->evaluateChildren($data);
    yield $this->mergeResult($values['fields'], $data, $values);
  }

  static function getSchemaChildren() {
    return parent::getSchemaChildren() + [
      'use' => ['_type' => 'partial', '_required' => TRUE, '_partial' => 'field'],
    ];
  }

}
