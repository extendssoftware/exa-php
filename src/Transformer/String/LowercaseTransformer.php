<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Transformer\String;

use ExtendsSoftware\ExaPHP\Transformer\TransformerInterface;

use function is_string;
use function mb_strtolower;

class LowercaseTransformer implements TransformerInterface
{
    /**
     * @inheritDoc
     */
    public function transform(mixed $value): mixed
    {
        if (!is_string($value)) {
            return $value;
        }

        return mb_strtolower($value);
    }
}
