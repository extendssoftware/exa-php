<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Other;

use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;

class ProxyValidator implements ValidatorInterface
{
    /**
     * OptionalValidator constructor.
     *
     * @param ValidatorInterface $validator
     */
    public function __construct(private readonly ValidatorInterface $validator)
    {
    }

    /**
     * @inheritDoc
     */
    public function validate(mixed $value, mixed $context = null): ResultInterface
    {
        return $this->validator->validate($value, $context);
    }
}
