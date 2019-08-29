<?php

namespace DataMincerPlugins\Fields;

use DataMincerCore\Plugin\PluginFieldBase;
use DataMincerCore\Plugin\PluginFieldInterface;

/**
 * @property PluginFieldInterface regexp
 * @property PluginFieldInterface subject
 * @property int return
 */
class PregMatch extends PluginFieldBase {

  protected static $pluginId = 'preg_match';

  function getValue($data) {
    $regexp = $this->regexp->getValue($data);
    $subject = $this->subject->getValue($data);
    $return = $this->return;
    $result = NULL;
    if (preg_match_all($regexp, $subject, $matches)) {
      if (array_key_exists($return, $matches)) {
        $result = $matches[$return];
      }
    }
    return $result;
  }

  static function getSchemaChildren() {
    return parent::getSchemaChildren() + [
      'regexp' => [ '_type' => 'partial', '_required' => TRUE, '_partial' => 'field' ],
      'subject' => [ '_type' => 'partial', '_required' => TRUE, '_partial' => 'field' ],
      'return' => [ '_type' => 'number', '_required' => FALSE, '_partial' => 'field' ],
    ];
  }

  static function defaultConfig($data = NULL) {
    return [
        'return' => 1,
      ] + parent::defaultConfig($data);
  }
}
