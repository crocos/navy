<?php
namespace Navy\Fixtures\HookAwarePlugin\Hook;

use Navy\Hook\Event;
use Navy\Hook\HookInterface;

class BarHook implements HookInterface
{
    public function getEvent()
    {
        return 'bar';
    }

    public function onBar(Event $event)
    {
    }
}
