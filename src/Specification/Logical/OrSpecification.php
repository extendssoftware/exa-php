<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Specification\Logical;

use ExtendsSoftware\ExaPHP\Specification\SpecificationInterface;

readonly class OrSpecification implements SpecificationInterface
{
    /**
     * OrSpecification constructor.
     *
     * @param SpecificationInterface $left
     * @param SpecificationInterface $right
     */
    public function __construct(private SpecificationInterface $left, private SpecificationInterface $right)
    {
    }

    /**
     * @inheritDoc
     */
    public function isSatisfied(): bool
    {
        return $this->left->isSatisfied() or $this->right->isSatisfied();
    }
}
