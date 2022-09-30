<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Application\Exception;

use ExtendsSoftware\ExaPHP\Application\ApplicationBuilderException;
use RuntimeException;
use Throwable;

class FailedToLoadCache extends RuntimeException implements ApplicationBuilderException
{
    /**
     * FailedToLoadCache constructor.
     *
     * @param Throwable $previous
     */
    public function __construct(Throwable $previous)
    {
        parent::__construct('Failed to load config. See previous exception for more details.', 0, $previous);
    }
}
