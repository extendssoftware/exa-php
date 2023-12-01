<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Specification;

interface SpecificationInterface
{
    /**
     * If specification is satisfied.
     *
     * @return bool
     */
    public function isSatisfied(): bool;
}
