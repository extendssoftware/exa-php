<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Utility\Flattener;

use function get_object_vars;
use function is_object;

class Flattener implements FlattenerInterface
{
    /**
     * @inheritDoc
     */
    public function flatten(object|array $data, string $delimiter, string $prepend = ''): array
    {
        if (is_object($data)) {
            $data = get_object_vars($data);
        }

        $flatten = [];
        foreach ($data as $key => $value) {
            if (is_object($value) || is_array($value)) {
                $flatten[] = $this->flatten($value, $delimiter, $prepend . $key . $delimiter);
            } else {
                $flatten[] = [
                    $prepend . $key => $value,
                ];
            }
        }

        return array_merge(...$flatten);
    }
}
