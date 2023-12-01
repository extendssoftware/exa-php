<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger\Priority\Alert;

use ExtendsSoftware\ExaPHP\Logger\Priority\PriorityInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\StaticFactory\StaticFactoryInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;

class AlertPriority implements PriorityInterface, StaticFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function getValue(): int
    {
        return 1;
    }

    /**
     * @inheritDoc
     */
    public function getKeyword(): string
    {
        return 'alert';
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return 'Action must be taken immediately.';
    }

    /**
     * @inheritDoc
     */
    public static function factory(
        string $key,
        ServiceLocatorInterface $serviceLocator,
        array $extra = null
    ): PriorityInterface {
        return new AlertPriority();
    }
}
