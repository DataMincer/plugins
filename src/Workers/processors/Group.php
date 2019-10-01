<?php

namespace DataMincerPlugins\Workers\processors;

use DataMincerCore\Plugin\PluginBufferingWorkerBase;

/**
 * @property string[] $by
 * @property string $from
 */
class Group extends PluginBufferingWorkerBase {

  protected static $pluginId = 'group';

  protected $currentGroup = NULL;
  protected $lastGroup = NULL;
  protected $context = [];
  /**
   * @var bool
   */
  private $buffering = TRUE;

  public function isBuffering() {
    return $this->buffering;
  }

  /**
   * @inheritDoc
   */
  public function process($config) {
    $data = yield;
    $values = $this->evaluate($data);
    $this->currentGroup = $this->extractGroup($values['by'], $data[$values['from']]);
    if (!is_null($this->lastGroup)) {
      if ($this->lastGroup !== $this->currentGroup) {
        $this->buffering = FALSE;
      }
    }
    else {
      $this->lastGroup = $this->currentGroup;
    }
    $row = $this->extractGroupNot($values['by'], $data[$values['from']]);
    // Save other values
    $this->context = $this->extractGroupNot([$values['from']], $data);
    yield $row;
  }

  protected function rowsEqual($columns, $r1, $r2) {
    foreach ($columns as $column) {
      if ($r1[$column] !== $r2[$column]) {
        return FALSE;
      }
    }
    return TRUE;
  }

  protected function extractGroup($columns, $r) {
    return array_filter($r, function($i) use ($columns) {
      return in_array($i, $columns);
    }, ARRAY_FILTER_USE_KEY);
  }

  protected function extractGroupNot($columns, $r) {
    return array_filter($r, function($i) use ($columns) {
      return !in_array($i, $columns);
    }, ARRAY_FILTER_USE_KEY);
  }

  public function processBuffer() {
    $row = $this->lastGroup + [
      'items' => $this->buffer,
    ];
    // Reset buffer
    $this->buffer = [];
    $this->lastGroup = $this->currentGroup;
    $this->buffering = TRUE;
    return $this->mergeResult($row, $this->context, $this->_config);
  }

  static function getSchemaChildren() {
    return parent::getSchemaChildren() + [
      'from' => ['_type' => 'text', '_required' => FALSE ],
      'by' => ['_type' => 'prototype', '_required' => TRUE, '_min_items' => 1, '_prototype' => [
        '_type' => 'text', '_required' => TRUE
      ]]
    ];
  }

  static function defaultConfig($data = NULL) {
    return parent::defaultConfig($data) + [
      'from' => 'row'
    ];
  }


}
