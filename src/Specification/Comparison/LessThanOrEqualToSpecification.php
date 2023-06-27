<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Specification\Comparison;

use ExtendsSoftware\ExaPHP\Specification\SpecificationInterface;

class LessThanOrEqualToSpecification implements SpecificationInterface
{
    /**
     * LessThanOrEqualToSpecification constructor.
     *
     * @param mixed $left
     * @param mixed $right
     */
    public function __construct(private readonly mixed $left, private readonly mixed $right)
    {
    }

    /**
     * @inheritDoc
     */
    public function isSatisfied(): bool
    {
        return $this->left <= $this->right;
    }
}
