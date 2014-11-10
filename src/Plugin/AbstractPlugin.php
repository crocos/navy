<?php
namespace Navy\Plugin;

use ReflectionObject;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

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

    public function loadConfig(ParameterBagInterface $parameters, array $config)
    {
    }

    public function loadContainer(ContainerBuilder $container)
    {
        $locator = new FileLocator(dirname((new ReflectionObject($this))->getFilename()).'/Resources');

        if (is_file($locator->locate('services.yml'))) {
            $loader = new YamlFileLoader($container, $locator);
            $loader->load('services.yml');
        }
    }

    public function getHooks()
    {
        return [];
    }

    public function getNotifiers()
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
