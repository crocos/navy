<?php
namespace Navy\Notifier;

class NotifierProvider
{
    protected $config;

    public function __construct(AdapterResolversProviderInterface $resolverProvider, array $config)
    {
        $this->resolvers = $resolverProvider->getAdapterResolvers();
        $this->config = $config;
    }

    public function get()
    {
        $notifier = new Notifier();

        foreach ($this->config as $notifierConfig) {
            $type = $notifierConfig['type'];
            if (!isset($this->resolvers[$type])) {
                throw new \InvalidArgumentException(sprintf('No support notifier type "%s"', $type));
            }

            $adapter = $this->resolvers[$type]->resolveAdapter($notifierConfig);
            $notifier->addAdapter($adapter);
        }

        return $notifier;
    }
}
