<?php

namespace DataMincerPlugins\Workers\processors;

use DataMincerCore\Plugin\PluginWorkerBase;

class Join extends PluginWorkerBase {

  protected static $pluginId = 'join';

//  public function evaluate($data = []) {
//    // Do not evaluate 'fields' field, as it's intended for process()
//    return $this->evaluateChildren($data, [], [['fields']]);
//  }

  /**
   * @inheritDoc
   */
  public function process($config) {
    $data = yield;
  }

  static function getSchemaChildren() {
    return parent::getSchemaChildren() + [
      'join' => ['_type' => 'partial', '_required' => FALSE, '_partial' => 'field'],
      'select' => ['_type' => 'prototype', '_required' => FALSE, '_prototype' => [
        '_type' => 'text', '_required' => TRUE,
      ]]
    ];
  }

  static function defaultConfig($data = NULL) {
    return [
      'select' => []
    ] + parent::defaultConfig($data);
  }

}
