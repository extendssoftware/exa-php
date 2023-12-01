<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Application\Http;

use ExtendsSoftware\ExaPHP\Application\AbstractApplication;
use ExtendsSoftware\ExaPHP\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsSoftware\ExaPHP\Http\Middleware\MiddlewareException;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;

class HttpApplication extends AbstractApplication
{
    /**
     * @inheritDoc
     */
    public function __construct(
        private readonly MiddlewareChainInterface $chain,
        private readonly RequestInterface $request,
        ServiceLocatorInterface $serviceLocator,
        array $modules
    ) {
        parent::__construct($serviceLocator, $modules);
    }

    /**
     * @inheritDoc
     * @throws MiddlewareException
     */
    protected function run(): AbstractApplication
    {
        $this->chain->proceed($this->request);

        return $this;
    }
}
