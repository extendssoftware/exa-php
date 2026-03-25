<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Result\Valid;

use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;

readonly class ValidResult implements ResultInterface
{
    /**
     * ValidResult constructor.
     *
     * @param mixed $value
     */
    public function __construct(private mixed $value = null) {}

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
        return $this->value;
    }

    /**
     * @inheritDoc
     */
    public function getValue(): mixed
    {
        return $this->value;
    }
}
