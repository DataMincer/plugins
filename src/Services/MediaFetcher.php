<?php

namespace DataMincerPlugins\Services;

use DirectoryIterator;
use Exception;
use /** @noinspection PhpComposerExtensionStubsInspection */
  finfo;
use DataMincerCore\Exception\PluginException;
use DataMincerCore\Plugin\PluginServiceBase;
use DataMincerCore\Util;

class MediaFetcher extends PluginServiceBase {

  protected static $pluginId = 'mediafetcher';
  protected static $pluginType = 'service';

  protected $client;

  /**
   * @param $url
   * @return array|mixed|null
   * @throws PluginException
   */
  function fetch($url) {
    $use_cache = boolval($this->_config['cache'] ?? FALSE);
    if ($use_cache) {
      $cache_dir = $this->getCacheDir();
    }
    $data = NULL;
    $request_id = sha1($url);
    if ($use_cache) {
      /** @noinspection PhpUndefinedVariableInspection */
      if (file_exists($cache_file_name = $cache_dir . '/' . $request_id)) {
        try {
          $data = unserialize(file_get_contents($cache_file_name));
        }
        catch (Exception $e) {
          $this->error('Cannot read file contents: ' . $cache_file_name . "\n" . $e->getMessage());
        }
        /** @noinspection PhpUndefinedVariableInspection */
        return $data;
      }
    }
    $contents = @file_get_contents($url);
    if ($contents === FALSE) {
      $this->error('Cannot fetch file: ' . $url);
    }
    $data = [
      'request_id' => $request_id,
      'data' => $contents,
      'mime' => $this->getMimeFromData($contents),
    ];
    if ($use_cache) {
      /** @noinspection PhpUndefinedVariableInspection */
      file_put_contents($cache_file_name, serialize($data));
    }
    return $data;
  }

  function getMimeFromData($data) {
    /** @noinspection PhpComposerExtensionStubsInspection */
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    return $finfo->buffer($data);
  }

  protected function getCacheDir() {
    $cache_dir = $this->findCacheDir();
    if ($cache_dir === FALSE) {
      $cache_dir = $this->createCacheDir();
    }
    return $cache_dir;
  }

  protected function getCachePath() {
    if (array_key_exists('cachePath', $this->_config)) {
      $sys_temp_dir = $this->_config['cachePath'];
    }
    else {
      $sys_temp_dir = sys_get_temp_dir();
    }
    return $sys_temp_dir;
  }

  protected function findCacheDir() {
    $result = FALSE;
    foreach (new DirectoryIterator($this->getCachePath()) as $fileInfo) {
      if ($fileInfo->isDir() && !$fileInfo->isDot() && strpos($fileInfo->getFilename(), static::$pluginId) === 0) {
        $result = $fileInfo->getPathname();
        break;
      }
    }
    return $result;
  }

  protected function createCacheDir() {
    $temp_file = tempnam($this->getCachePath(), static::$pluginId);
    if (file_exists($temp_file)) {
      unlink($temp_file);
    }
    Util::prepareDir($temp_file);
    return $temp_file;
  }

  static function defaultConfig($data = NULL) {
    return [
      'cache' => FALSE,
    ];
  }

  static function getSchemaChildren() {
    return [
      'cache' => [ '_type' => 'boolean', '_required' => FALSE ],
      'cachePath' => [ '_type' => 'text', '_required' => FALSE ],
    ];

  }

}
