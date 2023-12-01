<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger\Priority\Debug;

use ExtendsSoftware\ExaPHP\Logger\Priority\PriorityInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\StaticFactory\StaticFactoryInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;

class DebugPriority implements PriorityInterface, StaticFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function getValue(): int
    {
        return 7;
    }

    /**
     * @inheritDoc
     */
    public function getKeyword(): string
    {
        return 'debug';
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return 'Debug-level messages.';
    }

    /**
     * @inheritDoc
     */
    public static function factory(
        string $key,
        ServiceLocatorInterface $serviceLocator,
        array $extra = null
    ): PriorityInterface {
        return new DebugPriority();
    }
}
