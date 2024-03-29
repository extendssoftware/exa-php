<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator;

use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;

readonly class InterruptValidator implements ValidatorInterface
{
    /**
     * Set validator and interrupt flag.
     *
     * @param ValidatorInterface $validator
     * @param bool               $interrupt
     */
    public function __construct(private ValidatorInterface $validator, private ?bool $interrupt = null)
    {
    }

    /**
     * @inheritDoc
     */
    public function validate($value, mixed $context = null): ResultInterface
    {
        return $this->validator->validate($value, $context);
    }

    /**
     * Return the interrupt flag.
     *
     * @return bool
     */
    public function mustInterrupt(): bool
    {
        return $this->interrupt ?? false;
    }
}
