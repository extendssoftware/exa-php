<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger\Filter\Priority;

use ExtendsSoftware\ExaPHP\Logger\Filter\FilterInterface;
use ExtendsSoftware\ExaPHP\Logger\LogInterface;
use ExtendsSoftware\ExaPHP\Logger\Priority\Critical\CriticalPriority;
use ExtendsSoftware\ExaPHP\Logger\Priority\PriorityInterface;
use ExtendsSoftware\ExaPHP\Processor\ProcessorException;
use ExtendsSoftware\ExaPHP\Processor\ProcessorInterface;
use ExtendsSoftware\ExaPHP\Processor\Validator\Comparison\GreaterThanValidator;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\StaticFactory\StaticFactoryInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorException;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;

readonly class PriorityFilter implements FilterInterface, StaticFactoryInterface
{
    /**
     * Create a new priority filter.
     *
     * @param PriorityInterface|null  $priority
     * @param ProcessorInterface|null $processor
     */
    public function __construct(
        private ?PriorityInterface $priority = null,
        private ?ProcessorInterface $processor = null,
    ) {
    }

    /**
     * @inheritDoc
     * @throws ServiceLocatorException
     */
    public static function factory(string $key, ServiceLocatorInterface $serviceLocator, ?array $extra = null): object
    {
        $priority = null;
        if (isset($extra['priority'])) {
            /** @var class-string<PriorityInterface> $name */
            $name = $extra['priority']['name'];

            $priority = $serviceLocator->getService($name, $extra['priority']['options'] ?? []);
        }

        $processor = null;
        if (isset($extra['processor'])) {
            /** @var class-string<ProcessorInterface> $name */
            $name = $extra['processor']['name'];

            $processor = $serviceLocator->getService($name, $extra['processor']['options'] ?? []);
        }

        return new PriorityFilter($priority, $processor);
    }

    /**
     * @inheritDoc
     * @throws ProcessorException
     */
    public function filter(LogInterface $log): bool
    {
        $priority = $this->priority ?? new CriticalPriority();
        $processor = $this->processor ?? new GreaterThanValidator($priority->getValue());

        return $processor
            ->process(
                $log
                    ->getPriority()
                    ->getValue(),
            )
            ->isValid();
    }
}
