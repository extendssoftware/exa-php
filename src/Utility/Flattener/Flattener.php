<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Utility\Flattener;

class Flattener implements FlattenerInterface
{
    /**
     * @inheritDoc
     */
    public function flatten(array $data, string $delimiter, string $prepend = ''): array
    {
        $flatten = [];
        foreach ($data as $key => $value) {
            if (is_array($value) && !empty($value)) {
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
