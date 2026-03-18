<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Transformer\String\DigitsOnly;

use ExtendsSoftware\ExaPHP\Transformer\TransformerInterface;

use function is_string;
use function preg_replace;

class DigitsOnlyTransformer implements TransformerInterface
{
    /**
     * @inheritDoc
     */
    public function transform(mixed $value): mixed
    {
        if (!is_string($value)) {
            return $value;
        }

        return preg_replace('/\D+/', '', $value);
    }
}
