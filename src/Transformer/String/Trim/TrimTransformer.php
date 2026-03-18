<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Transformer\String\Trim;

use ExtendsSoftware\ExaPHP\Transformer\TransformerInterface;

use function is_string;
use function trim;

class TrimTransformer implements TransformerInterface
{
    /**
     * @inheritDoc
     */
    public function transform(mixed $value): mixed
    {
        if (!is_string($value)) {
            return $value;
        }

        return trim($value);
    }
}
