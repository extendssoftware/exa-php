<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger\Priority\Emergency;

use ExtendsSoftware\ExaPHP\Logger\Priority\PriorityInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\StaticFactory\StaticFactoryInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;

class EmergencyPriority implements PriorityInterface, StaticFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function getValue(): int
    {
        return 0;
    }

    /**
     * @inheritDoc
     */
    public function getKeyword(): string
    {
        return 'emerg';
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return 'System is unusable.';
    }

    /**
     * @inheritDoc
     */
    public static function factory(
        string $key,
        ServiceLocatorInterface $serviceLocator,
        array $extra = null
    ): PriorityInterface {
        return new EmergencyPriority();
    }
}
