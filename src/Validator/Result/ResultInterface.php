<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Result;

use ExtendsSoftware\ExaPHP\Validator\Result\Exception\ResultNotValid;
use JsonSerializable;

interface ResultInterface extends JsonSerializable
{
    /**
     * If the result is valid.
     *
     * @return bool
     */
    public function isValid(): bool;

    /**
     * Get the validated valid value.
     *
     * @return mixed
     * @throws ResultNotValid
     */
    public function getValue(): mixed;
}
