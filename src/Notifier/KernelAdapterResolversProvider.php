<?php
namespace Navy\Notifier;

use Navy\Kernel;

class KernelAdapterResolversProvider implements AdapterResolversProviderInterface
{
    protected $kernel;
    protected $container;

    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
        $this->container = $kernel->getContainer();
    }

    public function getAdapterResolvers()
    {
        $resolvers = [];

        foreach ($this->kernel->getPlugins() as $plugin) {
            foreach ($plugin->getNotifiers() as $resolverId) {
                $resolver = $this->container->get($resolverId);

                $resolvers[$resolver->getSupportType()] = $resolver;
            }
        }

        return $resolvers;
    }
}
