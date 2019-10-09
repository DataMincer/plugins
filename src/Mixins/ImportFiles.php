<?php

namespace DataMincerPlugins\Mixins;

use DataMincerCore\Plugin\PluginFieldBase;
use DataMincerCore\Plugin\PluginFieldInterface;

/**
 * @property PluginFieldInterface path
 * @property string mask
 * @property PluginFieldInterface destination
 */
class ImportFiles extends PluginFieldBase {

  protected static $pluginId = 'importfiles';

  function getValue($data) {
    /** @var PluginFieldInterface $mixin */
    $mixin = $this->mixin();
    return $mixin->value(['mixin' => [
      'mask' => $this->resolveParam($data, $this->mask),
      'path' => $this->path->value($data),
      'destination' => $this->destination->value($data),
    ]] + $data);
  }

  static function getSchemaChildren() {
    return parent::getSchemaChildren() + [
      'path' => [ '_type' => 'partial', '_required' => TRUE, '_partial' => 'field' ],
      'mask' => [ '_type' => 'text', '_required' => FALSE ],
      'destination' => [ '_type' => 'partial', '_required' => TRUE, '_partial' => 'field' ],
    ];
  }

  static function getMixin() {
    return <<< YAML
field: chain
items:
  - field: opendir
    mask: '@mixin.mask'
    path: '@mixin.path'
  - field: debug
    select: '@input'
  - field: each
    source: '@input'
    do:
      value:
        field: chain
        source: '@input'
        items:
          - field: uuid
            persistent: '@source.filename'
          - field: concat
            items: ['@input', '.', '@source.extension']
          - field: copyfile
            source:
              field: concat
              items: ['@source.path', '/', '@source.filename']
            destination:
              field: concat
              items: ['@mixin.destination', '/', '@input']
          - field: value
            value: ['@source.filename', '@../input']
  - field: group
    source: '@input'
    by: 0
YAML;
  }

  static function getMixinSchema() {
    return [
      '_type' => 'partial', '_required' => TRUE, '_partial' => 'field'
    ];
  }

}
