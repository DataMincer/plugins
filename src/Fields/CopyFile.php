<?php

namespace DataMincerPlugins\Fields;

use DataMincerCore\Plugin\PluginFieldBase;
use DataMincerCore\Plugin\PluginFieldInterface;
use DataMincerCore\Util;

/**
 * @property PluginFieldInterface source
 * @property PluginFieldInterface destination
 */
class CopyFile extends PluginFieldBase {

  protected static $pluginId = 'copyfile';

  function getValue($data) {
    $source_path = $this->source->value($data);
    $destination_path = $this->destination->value($data);
    if (!file_exists($source_path)) {
      // Special case: when having bundle in the data, check its directory automatically
      if (array_key_exists('bundle', $data) && array_key_exists('path', $data['bundle'])) {
        $source_path = $data['bundle']['path'] . '/' . $source_path;
      }
    }
    if (!file_exists($source_path)) {
      $this->error("Cannot open file: $source_path");
    }
    $destination_dir = dirname($destination_path);
    Util::prepareDir($destination_dir);
    $result = @copy($source_path, $destination_path);
    if (!$result) {
      $this->error("Cannot copy file: $source_path > $destination_path");
    }
    return $destination_path;
  }

  static function getSchemaChildren() {
    return parent::getSchemaChildren() + [
      'source' => [ '_type' => 'partial', '_required' => TRUE, '_partial' => 'field' ],
      'destination' => [ '_type' => 'partial', '_required' => TRUE, '_partial' => 'field' ],
    ];
  }

}
