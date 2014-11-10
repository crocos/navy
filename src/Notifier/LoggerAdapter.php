<?php
namespace Navy\Notifier;

use Psr\Log\LoggerInterface;

class LoggerAdapter implements AdapterInterface
{
    protected $logger;
    protected $level;

    public function __construct(LoggerInterface $logger, $level = 'notice')
    {
        $this->logger = $logger;
        $this->level = $level;
    }

    public function notify($message)
    {
        $this->logger->log($this->level, sprintf('[Notifier] %s', $message));
    }

    public function notifyChannel($channel, $message)
    {
        $this->logger->log($this->level, sprintf('[Notifier][%s] %s', $channel, $message));
    }
}
