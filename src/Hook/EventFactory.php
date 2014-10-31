<?php
namespace Navy\Hook;

class EventFactory implements EventFactoryInterface
{
    public function createEvent($name, $payload)
    {
        $event = null;
        if ($name === 'pull_request') {
            $event = new PullRequestEvent($payload);
        } else {
            $event = new Event($name, $payload);
        }

        return $event;
    }
}
