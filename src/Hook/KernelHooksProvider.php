<?php
namespace Navy\Hook;

use Navy\Kernel;

/**
 * 直接 Kernel に依存したくないので Provider を間に経由するようにした
 */
class KernelHooksProvider implements HooksProviderInterface
{
    protected $kernel;
    protected $container;

    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
        $this->container = $kernel->getContainer();
    }

    public function getHooks()
    {
        $hooks = [];

        foreach ($this->kernel->getPlugins() as $plugin) {
            foreach ($plugin->getHooks() as $hookId) {
                $hook = $this->container->get($hookId);

                if (!isset($hooks[$hook->getEvent()])) {
                    $hooks[$hook->getEvent()] = [];
                }

                $hooks[$hook->getEvent()][] = $hook;
            }
        }

        return $hooks;
    }
}
