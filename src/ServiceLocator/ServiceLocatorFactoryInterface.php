<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\ServiceLocator;

interface ServiceLocatorFactoryInterface
{
    /**
     * Create ServiceLocator for resolvers.
     *
     * When looking for a resolver to create the requested service, the resolvers will be looped sequentially. So, it
     * is recommended to begin with the most used resolver(s) in resolvers.
     *
     * @param mixed[] $config
     *
     * @return ServiceLocatorInterface
     * @throws ServiceLocatorException
     */
    public function create(array $config): ServiceLocatorInterface;
}
