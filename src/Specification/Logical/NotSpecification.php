<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Specification\Logical;

use ExtendsSoftware\ExaPHP\Specification\SpecificationInterface;

class NotSpecification implements SpecificationInterface
{
    /**
     * NotSpecification constructor.
     *
     * @param SpecificationInterface $specification
     */
    public function __construct(private readonly SpecificationInterface $specification)
    {
    }

    /**
     * @inheritDoc
     */
    public function isSatisfied(mixed $value): bool
    {
        return !$this->specification->isSatisfied($value);
    }
}
