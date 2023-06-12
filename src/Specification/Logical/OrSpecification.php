<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Specification\Logical;

use ExtendsSoftware\ExaPHP\Specification\SpecificationInterface;

class OrSpecification implements SpecificationInterface
{
    /**
     * OrSpecification constructor.
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
    public function isSatisfied(mixed $value): bool
    {
        return $this->left->isSatisfied($value) || $this->right->isSatisfied($value);
    }
}
