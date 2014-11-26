<?php
namespace Navy\Notifier;

interface NotifierInterface extends AdapterInterface
{
    public function addAdapter($type, AdapterInterface $adapter);
    public function getAdapters();
}
