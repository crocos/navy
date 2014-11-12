<?php
namespace Navy\Fixtures\HookAwarePlugin;

use Navy\Plugin\AbstractPlugin;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class HookAwarePlugin extends AbstractPlugin
{
    public function loadConfig(ParameterBagInterface $parameters, array $config)
    {
        $parameters->set('hookaware.foo', $config['foo']);
    }

    public function getHooks()
    {
        return [
            'test_hook_aware.hook.foo',
            'test_hook_aware.hook.bar',
        ];
    }
}
