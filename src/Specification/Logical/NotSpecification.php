<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Specification\Logical;

use ExtendsSoftware\ExaPHP\Specification\SpecificationInterface;

readonly class NotSpecification implements SpecificationInterface
{
    /**
     * NotSpecification constructor.
     *
     * @param SpecificationInterface $specification
     */
    public function __construct(private SpecificationInterface $specification)
    {
    }

    /**
     * @inheritDoc
     */
    public function isSatisfied(): bool
    {
        return !$this->specification->isSatisfied();
    }
}
