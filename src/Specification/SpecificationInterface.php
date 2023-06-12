<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Specification;

interface SpecificationInterface
{
    /**
     * If specification is satisfied by value.
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function isSatisfied(mixed $value): bool;
}
