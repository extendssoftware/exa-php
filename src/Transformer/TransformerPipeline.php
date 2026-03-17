<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Transformer;

readonly class TransformerPipeline implements TransformerInterface
{
    /**
     * TransformingValidator constructor.
     *
     * @param TransformerInterface[] $transformers
     */
    public function __construct(private array $transformers) {}

    /**
     * @inheritDoc
     */
    public function transform(mixed $value): mixed
    {
        foreach ($this->transformers as $transformer) {
            $value = $transformer->transform($value);
        }

        return $value;
    }
}
