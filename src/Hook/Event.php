<?php
namespace Navy\Hook;

class Event
{
    protected $event;
    protected $payload;

    public function __construct($event, $payload)
    {
        $this->event = $event;
        $this->payload = $payload;
    }

    public function getEvent()
    {
        return $this->event;
    }

    public function getPayload()
    {
        return $this->payload;
    }
}
