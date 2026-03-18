<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Transformer\String;

use ExtendsSoftware\ExaPHP\Transformer\TransformerInterface;

use function preg_replace;

class AsciiLettersOnlyTransformer implements TransformerInterface
{
    /**
     * @inheritDoc
     */
    public function transform(mixed $value): mixed
    {
        if (!is_string($value)) {
            return $value;
        }

        return preg_replace('/[^a-z]+/i', '', $value);
    }
}
