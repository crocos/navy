<?php
namespace Navy\Fixtures\HookAwarePlugin\Hook;

use Navy\Hook\Event;
use Navy\Hook\HookInterface;

class FooHook implements HookInterface
{
    public function getEvent()
    {
        return 'foo';
    }

    public function onFoo(Event $event)
    {
    }
}
