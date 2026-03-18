<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Transformer\String\NormalizeWhitespace;

use ExtendsSoftware\ExaPHP\Transformer\TransformerInterface;

use function is_string;
use function preg_replace;
use function trim;

class NormalizeWhitespaceTransformer implements TransformerInterface
{
    /**
     * @inheritDoc
     */
    function transform(mixed $value): mixed
    {
        if (!is_string($value)) {
            return $value;
        }

        $value = preg_replace('/\s+/u', ' ', $value);
        // @codeCoverageIgnoreStart
        if ($value === null) {
            return null;
        }
        // @codeCoverageIgnoreEnd

        return trim($value);
    }
}
