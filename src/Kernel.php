<?php
namespace Navy;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Navy\Plugin\PluginInterface;
use RuntimeException;

class Kernel
{
    protected $configDir;
    protected $container;
    protected $plugins = [];
    protected $hooks = [];

    public function __construct($configDir = null)
    {
        $this->configDir = $configDir ?: dirname(__DIR__) . '/config';

        $this->container = new ContainerBuilder();
        $this->container->set('kernel', $this);
    }

    public function bootstrap()
    {
        $this->loadConfig(__DIR__ . '/Resources/config.yml');
        $this->loadConfig($this->getConfigDir() . '/parameters.yml');

        $this->loadPlugins();

        $this->container->compile();

        return $this;
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function getPlugins()
    {
        return $this->plugins;
    }

    public function getHooks()
    {
        return $this->hooks;
    }

    protected function getConfigDir()
    {
        return $this->configDir;
    }

    protected function loadConfig($path)
    {
        // TODO: テストしづらい状態になってるので、ここは整理する. Configクラスを切ってもいいかも
        $pathinfo = pathinfo($path);
        if (in_array($pathinfo['extension'], ['yml', 'yaml'], true)) {
            $loaderClass = Loader\YamlFileLoader::class;
        } else {
            $loaderClass = Loader\PhpFileLoader::class;
        }

        (new $loaderClass($this->container, new FileLocator($pathinfo['dirname'])))->load($pathinfo['basename']);
    }

    protected function loadPlugins()
    {
        $loadedPlugins = [];

        $pluginConfig = $this->container->getParameter('navy')['plugins'];
        foreach ($pluginConfig as $pluginClass) {
            if (!class_exists($pluginClass)) {
                throw new RuntimeException(sprintf('Plugin "%s" not found', $pluginClass));
            }

            $plugin = new $pluginClass($this->container);
            if (! ($plugin instanceof PluginInterface)) {
                throw new RuntimeException(sprintf('Plugin "%s" must be implemented "%s"', $pluginClass, PluginInterface::class));
            }

            $name = $plugin->getName();
            if (isset($loadedPlugins[$name])) {
                throw new RuntimeException(sprintf('Cannot load plugin "%s" because plugin name "%s" was duplicated', $pluginClass, $name));
            }

            $this->loadConfig($plugin->getConfig()); // plugin config
            if (file_exists($this->getConfigDir() . "/plugins/$name.yml")) {
                $this->loadConfig($this->getConfigDir() . "/plugins/$name.yml"); // user config for plugin
            }

            $loadedPlugins[$name] = $plugin;
        }

        // load hooks
        $loadedHooks = [];
        foreach ($loadedPlugins as $plugin) {
            $hooks = $plugin->getHooks();
            foreach ($hooks as $hookId) {
                $hook = $this->container->get($hookId);
                $hook->getEvent();
                if (!isset($loadedHooks[$hook->getEvent()])) {
                    $loadedHooks[$hook->getEvent()] = [];
                }

                $loadedHooks[$hook->getEvent()][] = $hook;
            }
        }

        $this->plugins = $loadedPlugins;
        $this->hooks   = $loadedHooks;
    }
}
