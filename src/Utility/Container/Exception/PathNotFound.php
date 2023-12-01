<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Utility\Container\Exception;

use ExtendsSoftware\ExaPHP\Utility\Container\ContainerException;
use InvalidArgumentException;

class PathNotFound extends InvalidArgumentException implements ContainerException
{
    /**
     * PathNotFound constructor.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        parent::__construct(
            sprintf(
                'Path "%s" in container can not be found.',
                $path
            )
        );
    }
}
