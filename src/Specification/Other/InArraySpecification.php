<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Specification\Other;

use ExtendsSoftware\ExaPHP\Specification\SpecificationInterface;

class InArraySpecification implements SpecificationInterface
{
    /**
     * InArraySpecification constructor.
     *
     * @param array<mixed> $left
     * @param mixed        $right
     * @param bool|null    $strict
     */
    public function __construct(
        private readonly array $left,
        private readonly mixed $right,
        private readonly ?bool $strict = null
    ) {
    }

    /**
     * @inheritDoc
     */
    public function isSatisfied(): bool
    {
        return in_array($this->right, $this->left, $this->strict ?? true);
    }
}
