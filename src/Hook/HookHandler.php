<?php
namespace Navy\Hook;

use Exception;
use Navy\Request;
use Navy\Response;
use Psr\Log\LoggerInterface;

class HookHandler
{
    protected $hooks;
    protected $eventFactory;
    protected $logger;

    public function __construct(HooksProviderInterface $provider, EventFactoryInterface $eventFactory, LoggerInterface $logger)
    {
        $this->hooks = $provider->getHooks();
        $this->eventFactory = $eventFactory;
        $this->logger = $logger;
    }

    public function handle(Request $request)
    {
        $response = new Response();

        $result = [
            'result' => true,
        ];
        try {
            switch ($request->getMethod()) {
                case Request::METHOD_POST:
                    $this->handlePost($request);
                    break;
                default:
                    $response->setStatusCode(405);
                    $result['result'] = false;
                    $result['error'] = 'Method not allowed';
                    break;
            }
        } catch (Exception $e) {
            $response->setStatusCode(500);

            $result['result'] = false;
            $result['error'] = 'An Error Occured.';

            $this->logger->critical(sprintf('Uncaught exception:: [%s] %s', get_class($e), $e->getMessage()));
        }

        $content = json_encode($result);
        $response->setContent($content);

        $this->logger->info(sprintf('Response content "%s"', $content));

        return $response;
    }

    protected function handlePost(Request $request)
    {
        if (!isset($this->hooks[$request->getEvent()])) {
            return;
        }

        $event = $this->eventFactory->createEvent($request->getEvent(), $request->getPayload());

        $hooksToProcess = [];
        foreach ($this->hooks[$request->getEvent()] as $name => $hook) {
            $hooksToProcess[$name] = $hook;
        }

        if (count($hooksToProcess) < 0) {
            return;
        }

        $method = 'on' . static::camelize($request->getEvent());

        foreach ($hooksToProcess as $name => $hook) {
            $hook->$method($event);
        }
    }

    protected function camelize($word)
    {
        return strtr(ucwords(strtr($word, array('_' => ' ', '.' => '_ ', '\\' => '_ '))), array(' ' => ''));
    }
}
