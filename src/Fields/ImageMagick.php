<?php

namespace DataMincerPlugins\Fields;

use DataMincerCore\Plugin\PluginFieldBase;
use DataMincerCore\Plugin\PluginFieldInterface;

/**
 * @property PluginFieldInterface params_string
 * @property PluginFieldInterface in
 * @property PluginFieldInterface out
 * @property string imagemagick
 */
class ImageMagick extends PluginFieldBase {

  protected static $pluginId = 'imagemagick';

  function getValue($data) {
    $params_string = $this->params_string->value($data);
    $in = $this->_fileManager->resolveUri($this->in->value($data));
    if (!file_exists($in)) {
      $this->error("File not found: " . $in);
    }
    $out = $this->_fileManager->resolveUri($this->out->value($data));
    $imagemagick = $this->resolveParam($data, $this->imagemagick);
    $cmd = $imagemagick . ' ' . $in . ' ' . escapeshellarg($params_string) . ' ' . $out;
    $result = exec($cmd, $output, $return);
    if ($return !== 0) {
      $this->error("ImageMagick error: \n\t$cmd");
    }
    return $out;
  }

  static function getSchemaChildren() {
    return parent::getSchemaChildren() + [
      'params_string' => [ '_type' => 'partial', '_required' => TRUE, '_partial' => 'field'],
      // Input file path
      'in' => [ '_type' => 'partial', '_required' => TRUE, '_partial' => 'field'],
      // Output file path
      'out' => [ '_type' => 'partial', '_required' => TRUE, '_partial' => 'field'],
      'imagemagick' => [ '_type' => 'text', '_required' => FALSE ],
    ];
  }

  static function defaultConfig($data = NULL) {
    return [
        'imagemagick' => '/usr/bin/convert',
      ] + parent::defaultConfig($data);
  }

}
