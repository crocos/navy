<?php
namespace Navy\Notifier;

class Notifier implements NotifierInterface
{
    protected $adapters = [];

    public function addAdapter($type, AdapterInterface $adapter)
    {
        $this->adapters[$type] = $adapter;
    }

    public function getAdapters()
    {
        return $this->adapters;
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
