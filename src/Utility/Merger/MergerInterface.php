<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Utility\Merger;

interface MergerInterface
{
    /**
     * Merge right into left.
     *
     * @param array<int|string, mixed> $left
     * @param array<int|string, mixed> $right
     *
     * @return array<int|string, mixed>
     * @throws MergerException
     */
    public function merge(array $left, array $right): array;
}
