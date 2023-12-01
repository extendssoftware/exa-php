<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger\Priority\Critical;

use ExtendsSoftware\ExaPHP\Logger\Priority\PriorityInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\StaticFactory\StaticFactoryInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;

class CriticalPriority implements PriorityInterface, StaticFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function getValue(): int
    {
        return 2;
    }

    /**
     * @inheritDoc
     */
    public function getKeyword(): string
    {
        return 'crit';
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return 'Critical conditions, such as hard device errors.';
    }

    /**
     * @inheritDoc
     */
    public static function factory(
        string $key,
        ServiceLocatorInterface $serviceLocator,
        array $extra = null
    ): PriorityInterface {
        return new CriticalPriority();
    }
}
