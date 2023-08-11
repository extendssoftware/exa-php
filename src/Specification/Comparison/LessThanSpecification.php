<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Specification\Comparison;

use ExtendsSoftware\ExaPHP\Specification\SpecificationInterface;

readonly class LessThanSpecification implements SpecificationInterface
{
    /**
     * LessThanSpecification constructor.
     *
     * @param mixed $left
     * @param mixed $right
     */
    public function __construct(private mixed $left, private mixed $right)
    {
    }

    /**
     * @inheritDoc
     */
    public function isSatisfied(): bool
    {
        return $this->left < $this->right;
    }
}
