<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Hateoas\Expander;

use ExtendsSoftware\ExaPHP\Hateoas\Builder\BuilderInterface;
use ExtendsSoftware\ExaPHP\Hateoas\Link\LinkInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Router\Executor\ExecutorException;
use ExtendsSoftware\ExaPHP\Router\Executor\ExecutorInterface;
use ExtendsSoftware\ExaPHP\Router\RouterException;
use ExtendsSoftware\ExaPHP\Router\RouterInterface;

readonly class Expander implements ExpanderInterface
{
    /**
     * Expander constructor.
     *
     * @param RouterInterface   $router
     * @param ExecutorInterface $executor
     */
    public function __construct(private RouterInterface $router, private ExecutorInterface $executor)
    {
    }

    /**
     * @inheritDoc
     * @throws RouterException
     * @throws ExecutorException
     */
    public function expand(LinkInterface $link, RequestInterface $request): BuilderInterface
    {
        $request = $request->withUri($link->getUri());

        $match = $this->router->route($request);
        $response = $this->executor->execute($request, $match);

        return $response->getBody();
    }
}
