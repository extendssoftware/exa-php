<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Result\Valid;

use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;

class ValidResult implements ResultInterface
{
    /**
     * @inheritDoc
     */
    public function isValid(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        return null;
    }
}
