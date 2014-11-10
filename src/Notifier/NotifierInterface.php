<?php
namespace Navy\Notifier;

interface NotifierInterface extends AdapterInterface
{
    public function addAdapter(AdapterInterface $adapter);
}
