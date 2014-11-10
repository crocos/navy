<?php
namespace Navy;

use Monolog\Logger;
use ReflectionClass;

class LoggerProvider
{
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function get()
    {
        $logger = new Logger('navy');

        foreach ($this->config as $handlerConfig) {
            $logger->pushHandler($this->buildHandler($handlerConfig));
        }

        return $logger;
    }

    protected function buildHandler($config)
    {
        $config = $this->fixConfig($config);

        $class = new ReflectionClass('Monolog\Handler\\' . self::camelize($config['type']) . 'Handler');
        unset($config['type']);

        $args = [];
        foreach ($class->getMethod('__construct')->getParameters() as $param) {
            if (isset($config[$param->getName()])) {
                $args[] = $config[$param->getName()];
            } elseif ($param->isDefaultValueAvailable()) {
                $args[] = $param->getDefaultValue();
            } else {
                throw new \InvalidArgumentException(sprintf('Monolog handler "%s" requires "%s"', $config['type'], $param->getName()));
            }
        }

        return $class->newInstanceArgs($args);
    }

    protected function fixConfig($config)
    {
        switch ($config['type']) {
            case 'fingers_crossed':
                if (isset($config['action_level'])) {
                    $config['activationStrategy'] = $config['action_level'];
                    unset($config['action_level']);
                }

                $config['handler'] = $this->buildHandler($config['handler']);
        }

        return $config;
    }

    protected function camelize($word)
    {
        return strtr(ucwords(strtr($word, array('_' => ' ', '.' => '_ ', '\\' => '_ '))), array(' ' => ''));
    }
}
