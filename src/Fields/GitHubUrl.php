<?php

namespace DataMincerPlugins\Fields;

use DataMincerCore\Plugin\PluginFieldBase;

/**
 * @property bool failsafe
 * @property string path
 */
class GitHubUrl extends PluginFieldBase {

  protected static $pluginId = 'githuburl';

  function getValue($data) {
    $path = $this->resolveParam($data, $this->path);
    $path = $this->_fileManager->resolveUri($path);
    if (empty($path)) {
      $path = '.';
    }
    $cmd = 'git --git-dir=' .escapeshellarg($path . '/.git') . ' config --get remote.origin.url';
    $result = exec($cmd, $output, $return);
    if ($return !== 0) {
      if (!$this->failsafe) {
        $this->error("Got error while executing command:\n\t$cmd\n\tReturn code: $return");
      }
      else {
        return "";
      }
    }
    if (preg_match('~^.*:(\w+/.+)\.git$~', $result, $matches)) {
      $result = 'https://github.com/' . $matches[1];
    }
    return $result;
  }

  static function getSchemaChildren() {
    return parent::getSchemaChildren() + [
        'failsafe' => [ '_type' => 'boolean', '_required' => FALSE ],
        'path' => [ '_type' => 'text', '_required' => FALSE ],
      ];
  }

  static function defaultConfig($data = NULL) {
    return [
        'failsafe' => FALSE,
        'path' => '',
      ] + parent::defaultConfig($data);
  }
}
