<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Result;

use JsonSerializable;

interface ResultInterface extends JsonSerializable
{
    /**
     * If result is valid.
     *
     * @return bool
     */
    public function isValid(): bool;
}
