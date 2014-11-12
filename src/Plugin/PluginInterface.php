<?php
namespace Navy\Plugin;

use Navy\ConfigLoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

interface PluginInterface
{
    /**
     * @return string module name.
     */
    public function getName();

    /**
     * @param ParameterBagInterface $parameters
     * @param array $config
     */
    public function loadConfig(ParameterBagInterface $parameters, array $config);

    /**
     * @param ContainerBuilder $container
     */
    public function loadContainer(ContainerBuilder $container);

    /**
     * @return array
     */
    public function getHooks();

    /**
     * @return array
     */
    public function getNotifiers();
}
