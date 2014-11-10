<?php
namespace Navy\Notifier;

interface AdapterResolverInterface
{
    public function getSupportType();
    public function resolveAdapter(array $config);
}
