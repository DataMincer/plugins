<?php

namespace DataMincerPlugins\Fields;

use DataMincerCore\Plugin\PluginFieldBase;
use DataMincerCore\Plugin\PluginFieldInterface;
use DataMincerCore\Util;

/**
 * @property PluginFieldInterface path
 */
class MakeDir extends PluginFieldBase {

  protected static $pluginId = 'makedir';

  function getValue($data) {
    $path = $this->path->getValue($data);
    Util::prepareDir($path);
    return TRUE;
  }

  static function getSchemaChildren() {
    return parent::getSchemaChildren() + [
        'path' => [ '_type' => 'partial', '_required' => TRUE, '_partial' => 'field' ],
      ];
  }

}
