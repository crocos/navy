<?php
namespace Navy\Plugin;

use ReflectionObject;

abstract class AbstractPlugin implements PluginInterface
{
    protected $name;

    public function __construct()
    {
        $this->name = $this->detectName();
    }

    public function getName()
    {
        return $this->name;
    }

    public function getConfig()
    {
        return dirname((new ReflectionObject($this))->getFileName()) . '/Resources/config.yml';
    }

    public function getHooks()
    {
        return [];
    }

    protected function detectName()
    {
        $name = (new ReflectionObject($this))->getShortName();

        if (false === ($pos = strpos($name, 'Plugin'))) {
            throw new \RuntimeException(sprintf('[%s] Plugin class name must be suffixed "Plugin"', $name));
        }

        return strtolower(substr($name, 0, $pos));
    }
}
