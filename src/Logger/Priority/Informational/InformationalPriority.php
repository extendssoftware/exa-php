<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger\Priority\Informational;

use ExtendsSoftware\ExaPHP\Logger\Priority\PriorityInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\StaticFactory\StaticFactoryInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;

class InformationalPriority implements PriorityInterface, StaticFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function getValue(): int
    {
        return 6;
    }

    /**
     * @inheritDoc
     */
    public function getKeyword(): string
    {
        return 'info';
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return 'Informational messages.';
    }

    /**
     * @inheritDoc
     */
    public static function factory(
        string                  $key,
        ServiceLocatorInterface $serviceLocator,
        array                   $extra = null
    ): PriorityInterface {
        return new InformationalPriority();
    }
}
