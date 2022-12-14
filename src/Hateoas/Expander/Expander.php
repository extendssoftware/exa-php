<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Hateoas\Expander;

use ExtendsSoftware\ExaPHP\Hateoas\Builder\BuilderInterface;
use ExtendsSoftware\ExaPHP\Hateoas\Link\LinkInterface;
use ExtendsSoftware\ExaPHP\Router\Controller\Executor\ExecutorException;
use ExtendsSoftware\ExaPHP\Router\Controller\Executor\ExecutorInterface;
use ExtendsSoftware\ExaPHP\Router\RouterException;
use ExtendsSoftware\ExaPHP\Router\RouterInterface;

class Expander implements ExpanderInterface
{
    /**
     * Expander constructor.
     *
     * @param RouterInterface   $router
     * @param ExecutorInterface $executor
     */
    public function __construct(private readonly RouterInterface $router, private readonly ExecutorInterface $executor)
    {
    }

    /**
     * @inheritDoc
     * @throws RouterException
     * @throws ExecutorException
     */
    public function expand(LinkInterface $link): BuilderInterface
    {
        $request = $link->getRequest();

        $match = $this->router->route($request);
        $response = $this->executor->execute($request, $match);

        return $response->getBody();
    }
}
