<?php

namespace DataMincerPlugins\Fields;

use DataMincerCore\Plugin\PluginFieldBase;
use DataMincerCore\Plugin\PluginFieldInterface;

/**
 * @property PluginFieldInterface path
 */
class MakeDir extends PluginFieldBase {

  protected static $pluginId = 'makedir';

  function getValue($data) {
    $path = $this->fileManager->resolveUri($this->path->getValue($data));
    $this->fileManager->prepareDirectory($path);
    return $path;
  }

  static function getSchemaChildren() {
    return parent::getSchemaChildren() + [
        'path' => [ '_type' => 'partial', '_required' => TRUE, '_partial' => 'field' ],
      ];
  }

}
