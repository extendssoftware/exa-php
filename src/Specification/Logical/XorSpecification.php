<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Specification\Logical;

use ExtendsSoftware\ExaPHP\Specification\SpecificationInterface;

class XorSpecification implements SpecificationInterface
{
    /**
     * XorSpecification constructor.
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
        return $this->left->isSatisfied() xor $this->right->isSatisfied();
    }
}
