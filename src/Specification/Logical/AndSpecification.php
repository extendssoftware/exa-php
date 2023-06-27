<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Specification\Logical;

use ExtendsSoftware\ExaPHP\Specification\SpecificationInterface;

class AndSpecification implements SpecificationInterface
{
    /**
     * AndSpecification constructor.
     *
     * @param SpecificationInterface $left
     * @param SpecificationInterface $right
     */
    public function __construct(
        private readonly SpecificationInterface $left,
        private readonly SpecificationInterface $right
    ) {
    }

    /**
     * @inheritDoc
     */
    public function isSatisfied(): bool
    {
        return $this->left->isSatisfied() and $this->right->isSatisfied();
    }
}