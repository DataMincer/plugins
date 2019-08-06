<?php

namespace DataMincerPlugins\Decks;

use DataMincerCore\Plugin\PluginDeckBase;

class Named extends PluginDeckBase {

  protected static $pluginId = 'named';
  protected static $isDefault = TRUE;

  static function getSchemaChildren() {
    return parent::getSchemaChildren() + [
        'name' => [ '_type' => 'partial', '_required' => TRUE, '_partial' => 'field' ],
      ];
  }

}
