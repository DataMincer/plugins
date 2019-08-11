<?php

namespace DataMincerPlugins\Decks;

use DataMincerCore\Plugin\PluginDeckBase;

class DefaultDeck extends PluginDeckBase {

  protected static $pluginId = 'default';
  protected static $isDefault = TRUE;

  static function getSchemaChildren() {
    return parent::getSchemaChildren() + [
      'version' => ['_type' => 'text', '_required' => FALSE],
      'vars' => ['_type' => 'prototype', '_required' => FALSE, '_min_items' => 1, '_prototype' => [
        '_type' => 'partial', '_required' => TRUE, '_partial' => 'field',
      ]],
    ];
  }

}
