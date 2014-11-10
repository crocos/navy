<?php
namespace Navy;

use Navy\Plugin\AbstractPlugin;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class NavyPlugin extends AbstractPlugin
{
    public function loadConfig(ParameterBagInterface $parameters, array $config)
    {
        $params = [
            'navy.notifier.config' => [],
            'navy.logger.config'   => [],
        ];

        if (isset($config['vars'])) {
            // extra parameters
            foreach ($config['vars'] as $name => $value) {
                $params["navy.{$name}"] = $value;
            }
        }

        if (isset($config['notifier'])) {
            $params['navy.notifier.config'] = $config['notifier'];
        }

        if (isset($config['logger'])) {
            $params['navy.logger.config'] = $config['logger'];
        }

        $parameters->add($params);
    }

    public function loadContainer(ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/Resources'));
        $loader->load('services.yml');
    }

    public function getNotifiers()
    {
        return [
            'notifier_logger_adapter_resolver',
        ];
    }
}
