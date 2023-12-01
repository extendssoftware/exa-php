<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Utility\Flattener;

interface FlattenerInterface
{
    /**
     * Flatten data to key/value array.
     *
     * @param object|array<int|string, mixed> $data      Data to flatten
     * @param string                          $delimiter Delimiter to use to combine nested keys.
     *
     * @return array<string, mixed>
     */
    public function flatten(object|array $data, string $delimiter): array;
}
