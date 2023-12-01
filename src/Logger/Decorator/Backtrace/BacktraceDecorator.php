<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger\Decorator\Backtrace;

use ExtendsSoftware\ExaPHP\Logger\Decorator\DecoratorInterface;
use ExtendsSoftware\ExaPHP\Logger\LogInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\StaticFactory\StaticFactoryInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;

readonly class BacktraceDecorator implements DecoratorInterface, StaticFactoryInterface
{
    /**
     * Create backtrace decorator.
     *
     * @param int $limit
     */
    public function __construct(private int $limit = 6)
    {
    }

    /**
     * @inheritDoc
     */
    public static function factory(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        return new BacktraceDecorator();
    }

    /**
     * @inheritDoc
     */
    public function decorate(LogInterface $log): LogInterface
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, $this->limit);
        $call = end($backtrace);

        if (is_array($call)) {
            foreach ($call as $key => $value) {
                $log = $log->andMetaData($key, $value);
            }
        }

        return $log;
    }
}
