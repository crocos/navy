<?php
namespace Navy\Notifier;

use Psr\Log\LoggerInterface;

class LoggerAdapterResolver implements AdapterResolverInterface
{
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function getSupportType()
    {
        return 'logger';
    }

    public function resolveAdapter(array $config)
    {
        $level = isset($config['level']) ? $config['level'] : 'notice';

        return new LoggerAdapter($this->logger, $level);
    }
}
