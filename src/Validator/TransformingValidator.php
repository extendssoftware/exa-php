<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator;

use ExtendsSoftware\ExaPHP\Transformer\TransformerInterface;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;

class TransformingValidator extends AbstractValidator implements ValidatorInterface
{
    /**
     * TransformingValidator constructor.
     *
     * @param TransformerInterface $transformer
     * @param ValidatorInterface   $validator
     */
    public function __construct(
        private readonly TransformerInterface $transformer,
        private readonly ValidatorInterface $validator,
    ) {}

    /**
     * @inheritDoc
     */
    public function validate(mixed $value, mixed $context = null): ResultInterface
    {
        $value = $this->transformer->transform($value);

        return $this->validator->validate($value, $context);
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    protected function getTemplates(): array
    {
        return [];
    }
}
