<?php

namespace DataMincerPlugins\Workers\processors;

use DataMincerCore\Plugin\PluginWorkerBase;

class Process extends PluginWorkerBase {

  protected static $pluginId = 'process';

  /**
   * @inheritDoc
   */
  public function process() {
    $data = yield;
    $values = $this->evaluate($data);
    yield $this->mergeResult($values['fields'], $data, $values);
  }

  static function getSchemaChildren() {
    return parent::getSchemaChildren() + [
      'fields' => ['_type' => 'prototype', '_required' => TRUE, '_prototype' => [
        '_type' => 'partial', '_required' => TRUE, '_partial' => 'field',
      ]],
    ];
  }

}
