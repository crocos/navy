<?php
namespace Navy\Fixtures\HookAwarePlugin;

use Navy\Plugin\AbstractPlugin;

class HookAwarePlugin extends AbstractPlugin
{
    public function getHooks()
    {
        return [
            'test_hook_aware.hook.foo',
            'test_hook_aware.hook.bar',
        ];
    }
}
