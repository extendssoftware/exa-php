<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Exception;

use ExtendsSoftware\ExaPHP\Router\RouterException;
use InvalidArgumentException;

use function sprintf;

class RouteNotFound extends InvalidArgumentException implements RouterException
{
    /**
     * RouteNotFound constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct(
            sprintf(
                'Route for name "%s" can not be found.',
                $name
            )
        );
    }
}
