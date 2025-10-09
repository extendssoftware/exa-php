<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger\Filter\Priority;

use ExtendsSoftware\ExaPHP\Logger\Filter\FilterInterface;
use ExtendsSoftware\ExaPHP\Logger\LogInterface;
use ExtendsSoftware\ExaPHP\Logger\Priority\Critical\CriticalPriority;
use ExtendsSoftware\ExaPHP\Logger\Priority\PriorityInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\StaticFactory\StaticFactoryInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorException;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\Comparison\GreaterThanValidator;
use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;

readonly class PriorityFilter implements FilterInterface, StaticFactoryInterface
{
    /**
     * Create a new priority filter.
     *
     * @param PriorityInterface|null  $priority
     * @param ValidatorInterface|null $validator
     */
    public function __construct(
        private ?PriorityInterface $priority = null,
        private ?ValidatorInterface $validator = null,
    ) {}

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

        $validator = null;
        if (isset($extra['validator'])) {
            /** @var class-string<ValidatorInterface> $name */
            $name = $extra['validator']['name'];

            $validator = $serviceLocator->getService($name, $extra['validator']['options'] ?? []);
        }

        return new PriorityFilter($priority, $validator);
    }

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function filter(LogInterface $log): bool
    {
        $priority = $this->priority ?? new CriticalPriority();
        $validator = $this->validator ?? new GreaterThanValidator($priority->getValue());

        return $validator
            ->validate(
                $log
                    ->getPriority()
                    ->getValue(),
            )
            ->isValid();
    }
}
