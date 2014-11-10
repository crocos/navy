<?php
namespace Navy;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\Yaml\Parser as YamlParser;
use Navy\Plugin\PluginInterface;
use RuntimeException;

class Kernel
{
    const VERSION = '0.2.0';

    protected $configDir;
    protected $container;
    protected $plugins;

    public function __construct($configDir = null)
    {
        $this->configDir = $configDir ?: dirname(__DIR__) . '/config';
    }

    public function bootstrap()
    {
        $parameters = new ParameterBag();
        $container = new ContainerBuilder($parameters);

        $container->set('kernel', $this);
        $parameters->set('kernel.version', self::VERSION);

        $config = (new YamlParser())->parse(file_get_contents($this->getConfigDir().'/navy.yml'));

        $plugins = $this->loadPlugins($config, $parameters, $container);

        $container->compile();

        $this->container = $container;
        $this->plugins = $plugins;
    }

    public function getConfigDir()
    {
        return $this->configDir;
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function getPlugins()
    {
        return $this->plugins;
    }

    protected function loadPlugins(array $config, ParameterBag $parameters, ContainerBuilder $container)
    {
        $plugins = [new NavyPlugin()];
        if (isset($config['navy']['plugins'])) {
            foreach ($config['navy']['plugins'] as $class) {
                $plugins[] = new $class();
            }
        }

        foreach ($plugins as $plugin) {
            $pluginConfig = isset($config[$plugin->getName()]) ? $config[$plugin->getName()] : [];
            $plugin->loadConfig($parameters, $pluginConfig);
        }

        foreach ($plugins as $plugin) {
            $plugin->loadContainer($container);
        }

        $loadedPlugins = [];
        foreach ($plugins as $plugin) {
            $loadedPlugins[$plugin->getName()] = $plugin;
        }

        return $loadedPlugins;
    }
}
