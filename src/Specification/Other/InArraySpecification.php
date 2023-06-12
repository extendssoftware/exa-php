<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Specification\Other;

use ExtendsSoftware\ExaPHP\Specification\SpecificationInterface;

class InArraySpecification implements SpecificationInterface
{
    /**
     * InArraySpecification constructor.
     *
     * @param array     $haystack
     * @param bool|null $strict
     */
    public function __construct(private readonly array $haystack, private readonly ?bool $strict = null)
    {
    }

    /**
     * @inheritDoc
     */
    public function isSatisfied(mixed $value): bool
    {
        return in_array($value, $this->haystack, $this->strict ?? true);
    }
}
