<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger;

use ExtendsSoftware\ExaPHP\Logger\Priority\PriorityInterface;
use Throwable;

interface LoggerInterface
{
    /**
     * Log message with priority and metaData.
     *
     * When priority is null a CRIT (2) priority will be used.
     *
     * @param string                 $message
     * @param PriorityInterface|null $priority
     * @param Throwable|null         $throwable
     * @param mixed[]|null           $metaData
     *
     * @return LoggerInterface
     */
    public function log(
        string $message,
        PriorityInterface $priority = null,
        Throwable $throwable = null,
        array $metaData = null,
    ): LoggerInterface;
}
