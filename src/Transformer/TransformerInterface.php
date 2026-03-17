<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Transformer;

interface TransformerInterface
{
    /**
     * Transform a value.
     *
     * If the value is not of the expected type, the transformer must return the unchanged value.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public function transform(mixed $value): mixed;
}
