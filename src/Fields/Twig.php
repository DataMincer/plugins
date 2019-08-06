<?php

namespace DataMincerPlugins\Fields;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\ArrayLoader;
use Twig\TwigFunction;
use DataMincerCore\Plugin\PluginFieldBase;
use DataMincerCore\Plugin\PluginFieldInterface;

/**
 * @property PluginFieldInterface template
 * @property PluginFieldInterface[] params
 * @property array options
 */
class Twig extends PluginFieldBase {

  protected static $pluginId = 'twig';

  /** @var Environment  */
  protected $twig = NULL;

  public function initialize() {
    parent::initialize();
    $twig = new Environment(new ArrayLoader(), $this->options ?? []);
    $twig->addFunction(new TwigFunction('bp', function ($context) {
      if (function_exists('xdebug_break')) {
        /** @noinspection PhpComposerExtensionStubsInspection */
        xdebug_break();
      }
    }, [
      'needs_context' => TRUE,
    ]));
    $this->twig = $twig;
  }

  /**
   * @inheritDoc
   */
  function getValue($data) {
    $template = $this->template->value($data);
    /** @noinspection PhpUndefinedMethodInspection */
    $this->twig->getLoader()->setTemplate('template', $template);
    $result = NULL;
    try {
      $context = $data;
      if (array_key_exists('params', $this->config)) {
        $params = [];
        foreach ($this->params as $name => $param) {
          $params[$name] = $param->value($data);
        }
        //$context['params'] = $this->resolveParams($data, $this->params);
        $context['params'] = $params;
      }
      $result = $this->twig->load('template')->render($context);
    }
    catch (LoaderError | RuntimeError | SyntaxError $e) {
      $this->error($e->getMessage() . "\nTemplate:\n" . $template);
    }
    return $result;
  }

  static function getSchemaChildren() {
    return parent::getSchemaChildren() + [
      'template' => [ '_type' => 'partial', '_required' => TRUE, '_partial' => 'field' ],
      'params' =>   [ '_type' => 'prototype', '_required' => FALSE, '_min_items' => 1, '_prototype' => [
        '_type' => 'partial', '_required' => TRUE, '_partial' => 'field'
      ]],
      'options' => [ '_type' => 'array', '_required' => FALSE, '_ignore_extra_keys' => TRUE, '_children' => [
        'cache' => [ '_type' => 'text', '_required' => FALSE ],
        'debug' => [ '_type' => 'boolean', '_required' => FALSE ],
      ]]
    ];
  }

}
