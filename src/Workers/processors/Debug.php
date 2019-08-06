<?php

namespace DataMincerPlugins\Workers\processors;

use DataMincer;
use DataMincerCore\Plugin\PluginWorkerBase;
use DataMincerCore\Util;
use DataMincerPlugins\Traits\DebugTrait;

/**
 * @property bool dense
 */
class Debug extends PluginWorkerBase {

  use DebugTrait;

  protected static $pluginId = 'debug';

  /**
   * @inheritDoc
   */

  /**
   * @inheritDoc
   */
  public function process() {
    $data = yield;
    $values = $this->evaluate($data);
    $debug_data = $this->prepareData($data, $values['select'], $values['dense']);
    DataMincer::logger()->debug(Util::toJson($debug_data));
    yield $data;
  }

}
