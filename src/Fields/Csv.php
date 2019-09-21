<?php

namespace DataMincerPlugins\Fields;

use DataMincerCore\Plugin\PluginFieldBase;
use DataMincerCore\Plugin\PluginFieldInterface;
use DataMincerPlugins\CsvTrait;

/**
 * @property PluginFieldInterface path
 * @property string format
 */
class Csv extends PluginFieldBase {

  use CsvTrait;

  protected static $pluginId = 'csv';

  function getValue($data) {

  }


}
