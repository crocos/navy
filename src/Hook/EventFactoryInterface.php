<?php
namespace Navy\Hook;

interface EventFactoryInterface
{
    public function createEvent($name, $payload);
}
