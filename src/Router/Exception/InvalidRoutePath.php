<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Exception;

use ExtendsSoftware\ExaPHP\Router\RouterException;
use InvalidArgumentException;

class InvalidRoutePath extends InvalidArgumentException implements RouterException
{
    /**
     * InvalidRoutePath constructor.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        parent::__construct(
            sprintf(
                'Invalid router assemble path, got "%s".',
                $path
            )
        );
    }
}
