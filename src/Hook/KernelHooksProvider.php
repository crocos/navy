<?php
namespace Navy\Hook;

use Navy\Kernel;

/**
 * 直接 Kernel に依存したくないので Provider を間に経由するようにした
 */
class KernelHooksProvider implements HooksProviderInterface
{
    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
    }

    public function getHooks()
    {
        return $this->kernel->getHooks();
    }
}
