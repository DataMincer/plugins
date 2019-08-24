<?php

namespace DataMincerPlugins\Fields;

use DataMincerCore\Plugin\PluginFieldBase;

/**
 * @property string exec
 * @property string params
 */
class Exec extends PluginFieldBase {

  protected static $pluginId = 'exec';

  function getValue($data) {
    $cmd = $this->resolveParams($data, $this->exec);
    $params = $this->resolveParams($data, $this->params);
    foreach ($params as $param) {
    }
//    $result = exec($cmd, $output, $return);
//    if ($return !== 0) {
//      $this->error("Got error while executing command:\n\t$cmd\n\tReturn code: $return");
//    }
//    if (preg_match('~^.*:(\w+/.+)\.git$~', $result, $matches)) {
//      $result = 'https://github.com/' . $matches[1];
//    }
    return $result;
  }

  static function getSchemaChildren() {
    return parent::getSchemaChildren() + [
        'exec' => [ '_type' => 'text', '_required' => TRUE ],
        'params' => [ '_type' => 'prototype', '_required' => FALSE, '_min_items' => 1, '_prototype' => [
          '_type' => 'partial', 'required' => TRUE, '_partial' => 'field',
        ]],
      ];
  }

}
