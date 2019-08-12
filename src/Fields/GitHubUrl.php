<?php

namespace DataMincerPlugins\Fields;

use DataMincerCore\Plugin\PluginFieldBase;

class GitHubUrl extends PluginFieldBase {

  protected static $pluginId = 'githuburl';

  function getValue($data) {
    $cmd = 'git config --get remote.origin.url';
    $result = exec($cmd, $output, $return);
    if ($return !== 0) {
      $this->error("Got error while executing command:\n\t$cmd\n\tReturn code: $return");
    }
    preg_match('~^.*:\w+/.+$~', $result, $matches);
    return $result;
  }

}
