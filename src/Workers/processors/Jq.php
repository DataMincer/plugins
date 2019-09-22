<?php

namespace DataMincerPlugins\Workers\processors;

use DataMincerCore\Plugin\PluginWorkerBase;

class Jq extends PluginWorkerBase {

  protected static $pluginId = 'jq';

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
      'params' => ['_type' => 'array', '_required' => FALSE, '_children' => [
        'slurp' => ['_type' => 'boolean', '_required' => FALSE],
        'sort-keys' => ['_type' => 'boolean', '_required' => FALSE],
      ]],
    ];
  }

}
