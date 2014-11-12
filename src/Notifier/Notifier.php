<?php
namespace Navy\Notifier;

class Notifier implements NotifierInterface
{
    protected $adapters = [];

    public function addAdapter(AdapterInterface $adapter)
    {
        $this->adapters[] = $adapter;
    }

    public function notify($message)
    {
        foreach ($this->adapters as $notifier) {
            $notifier->notify($message);
        }
    }

    public function notifyChannel($channel, $message)
    {
        foreach ($this->adapters as $notifier) {
            $notifier->notifyChannel($channel, $message);
        }
    }
}
