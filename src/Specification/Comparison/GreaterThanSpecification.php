<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Specification\Comparison;

use ExtendsSoftware\ExaPHP\Specification\SpecificationInterface;

class GreaterThanSpecification implements SpecificationInterface
{
    /**
     * GreaterThanSpecification constructor.
     *
     * @param mixed $value
     */
    public function __construct(private readonly mixed $value)
    {
    }

    /**
     * @inheritDoc
     */
    public function isSatisfied(mixed $value): bool
    {
        return $value > $this->value;
    }
}
