<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Transformer\String;

use ExtendsSoftware\ExaPHP\Transformer\TransformerInterface;

use function is_string;
use function strip_tags;

class StripHtmlTagsTransformer implements TransformerInterface
{
    /**
     * @inheritDoc
     */
    public function transform(mixed $value): mixed
    {
        if (!is_string($value)) {
            return $value;
        }

        return strip_tags($value);
    }
}
