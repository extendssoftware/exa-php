<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator;

use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;

interface ValidatorInterface
{
    /**
     * Validate value and, optional, context against validators.
     *
     * The context will be passed to the current validator that is asserted.
     *
     * @param mixed $value
     * @param mixed $context
     *
     * @return ResultInterface
     */
    public function validate(mixed $value, mixed $context = null): ResultInterface;
}
