<?php
namespace Navy\Notifier;

interface AdapterInterface
{
    public function notify($message);
    public function notifyChannel($channel, $message);
}
