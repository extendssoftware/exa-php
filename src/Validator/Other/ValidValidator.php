<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Other;

use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;

class ValidValidator implements ValidatorInterface
{
    /**
     * @inheritDoc
     */
    public function validate($value, mixed $context = null): ResultInterface
    {
        return new ValidResult();
    }
}
