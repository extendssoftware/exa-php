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
     * Middleware chain.
     *
     * @var MiddlewareChainInterface
     */
    private $chain;

    /**
     * Request.
     *
     * @var RequestInterface
     */
    private $request;

    /**
     * @inheritDoc
     */
    public function __construct(
        MiddlewareChainInterface $chain,
        RequestInterface $request,
        ServiceLocatorInterface $serviceLocator,
        array $modules
    ) {
        parent::__construct($serviceLocator, $modules);

        $this->chain = $chain;
        $this->request = $request;
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
