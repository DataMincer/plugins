<?php

namespace DataMincerPlugins\Fields;

use DataMincerCore\Plugin\PluginFieldBase;
use DataMincerCore\Plugin\PluginFieldInterface;

/**
 * @property string get
 */
class GitInfo extends PluginFieldBase {

  protected static $pluginId = 'gitinfo';

  function getValue($data) {
    $get = $this->resolveParam($data, $this->get);
    if (!preg_match('~^[a-z.]+$~', $get)) {
      $this->error('Forbidden characters in the "get" param value: ' . $get);
    }
    $res = exec('git config get' . $get);
  }

  static function getSchemaChildren() {
    return parent::getSchemaChildren() + [
      'get' => [ '_type' => 'text', '_required' => TRUE],
    ];
  }

}
