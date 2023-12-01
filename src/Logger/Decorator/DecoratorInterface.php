<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger\Decorator;

use ExtendsSoftware\ExaPHP\Logger\LogInterface;

interface DecoratorInterface
{
    /**
     * Decorate log.
     *
     * When log is decorated, a new instance must be returned.
     *
     * @param LogInterface $log
     *
     * @return LogInterface
     */
    public function decorate(LogInterface $log): LogInterface;
}
