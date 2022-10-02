<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Exception;

use ExtendsSoftware\ExaPHP\Router\Route\RouteInterface;
use ExtendsSoftware\ExaPHP\Router\RouterException;
use LogicException;

class GroupRouteExpected extends LogicException implements RouterException
{
    /**
     * GroupRouteExpected constructor.
     *
     * @param RouteInterface $route
     */
    public function __construct(RouteInterface $route)
    {
        parent::__construct(
            sprintf(
                'A group route was expected, but an instance of "%s" was returned.',
                get_class($route)
            )
        );
    }
}
